<?php

namespace HgaCreative\MailgunWebhooks\tests;

use HgaCreative\MailgunWebhooks\Models\EmailTracking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MailgunWebhooksTest extends MailgunWebhooksTestCase
{
    use RefreshDatabase;

    /**
     * General email to utilise for the tests
     * @var EmailTracking
     */
    protected $emailTracking;

    /**
     * The payload which would come from mailgun post request
     * @var array
     */
    protected $data;

    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations'));

        $this->withFactories(__DIR__.'/../database/factories');

        $this->emailTracking = factory(EmailTracking::class)->create();

        $json = '{"signature":{"timestamp":"1558689370","token":"f6c1fbef4900fea71c293d5ed680d9d1df3328f0e892631963","signature":"00b5548158d18a514511ba2c714caa28e7e2b97b8a5817f188225d91666a9acc"},"event-data":{"tags":[],"timestamp":1558689370.0093,"storage":{"url":"https:\/\/storage.eu.mailgun.net\/v3\/domains\/automated-mail.test.live\/messages\/eyJwIjpmYWxzZSwiayI6ImU0NDI3ODg3LTFkYzQtNDQ1ZS1iNzFkLTE1YjQ2MzRlYTAwNyIsInMiOiIzNzNjYTk1MGVjIiwiYyI6ImIifQ==","key":"eyJwIjpmYWxzZSwiayI6ImU0NDI3ODg3LTFkYzQtNDQ1ZS1iNzFkLTE1YjQ2MzRlYTAwNyIsInMiOiIzNzNjYTk1MGVjIiwiYyI6ImIifQ=="},"recipient-domain":"live.co.uk","id":"keyP8fYaT2ygVbukUFBmzg","campaigns":[],"user-variables":[],"flags":{"is-routed":false,"is-authenticated":true,"is-system-test":false,"is-test-mode":false},"log-level":"info","envelope":{"sending-ip":"141.194.33.19","sender":"test@automated-mail.test.com","transport":"smtp","targets":"test@live.co.uk"},"message":{"headers":{"to":"test@live.co.uk","message-id":"20190524091609.1.F80279C0D062D6F5@automated-mail.test.com","from":"Test <test@automated-mail.test.com>","subject":"test"},"attachments":[],"size":6250},"recipient":"test@test.com","event":"delivered","delivery-status":{"tls":true,"mx-host":"eur.olc.protection.outlook.com","attempt-no":1,"description":null,"session-seconds":0.6347851753234863,"utf8":true,"code":250,"message":"OK","certificate-verified":true}}}';

        $data = json_decode($json, true);
        $data['event-data']['message']['headers']['message-id'] = $this->emailTracking->message_id;
        $this->data = $data;
    }

    /** @test */
    public function test_can_trigger_delivered()
    {
        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $this->assertEquals($this->emailTracking->fresh()->delivered, true);
    }

    /** @test */
    public function test_can_trigger_opened()
    {
        $this->data['event-data']['event'] = 'opened';

        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $this->assertEquals($this->emailTracking->fresh()->opened, true);
    }

    /** @test */
    public function test_can_trigger_opened_and_opens_is_incremented_by_1()
    {
        $this->data['event-data']['event'] = 'opened';

        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $this->assertEquals($this->emailTracking->fresh()->opens, 1);
    }

    /** @test */
    public function test_can_trigger_opened_and_opens_is_incremented_by_2()
    {
        $this->data['event-data']['event'] = 'opened';

        $this->post(route("webhooks.mailgun"), $this->data);
        $this->post(route("webhooks.mailgun"), $this->data);
        $this->assertEquals($this->emailTracking->fresh()->opens, 2);
    }

    /** @test */
    public function test_can_trigger_clicked()
    {
        $this->data['event-data']['event'] = 'clicked';

        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $this->assertEquals($this->emailTracking->fresh()->clicked, true);
    }

    /** @test */
    public function test_can_trigger_clicked_and_clicks_is_incremented_by_1()
    {
        $this->data['event-data']['event'] = 'clicked';

        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $this->assertEquals($this->emailTracking->fresh()->clicks, 1);
    }

    /** @test */
    public function test_can_trigger_clicked_and_clicks_is_incremented_by_2()
    {
        $this->data['event-data']['event'] = 'clicked';

        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $this->assertEquals($this->emailTracking->fresh()->clicks, 2);
    }

    /** @test */
    public function test_can_trigger_unsubscribed()
    {
        $this->data['event-data']['event'] = 'unsubscribed';

        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $this->assertEquals($this->emailTracking->fresh()->unsubscribed, true);
    }

    /** @test */
    public function test_can_trigger_permanent_fail()
    {
        $this->data['event-data']['event'] = 'failed';
        $this->data['event-data']['delivery-status']['code'] = 605;

        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $this->assertEquals($this->emailTracking->fresh()->permanent_fail, true);
    }

    /** @test */
    public function test_can_trigger_temporary_fail()
    {
        $this->data['event-data']['event'] = 'failed';
        $this->data['event-data']['delivery-status']['code'] = 452;

        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $this->assertEquals($this->emailTracking->fresh()->temporary_fail, true);
    }

    /** @test */
    public function test_can_trigger_complained()
    {
        $this->data['event-data']['event'] = 'complained';

        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $this->assertEquals($this->emailTracking->fresh()->complained, true);
    }

    /** @test */
    public function test_can_trigger_bad_request()
    {
        $this->data['event-data']['event'] = "foo";

        $response = $this->post(route("webhooks.mailgun"), $this->data);
        $response->assertStatus(400);
    }
}
