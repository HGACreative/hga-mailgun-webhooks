<?php

declare(strict_types=1);

namespace HgaCreative\MailgunWebhooks;

use Illuminate\Support\Facades\Storage;
use HgaCreative\MailgunWebhooks\Models\EmailTracking;
use HgaCreative\MailgunWebhooks\Requests\MailgunWebhookRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class MailgunWebhooks implements Contracts\MailgunWebhooks
{

    /**
     * The email tracking entry which will be updated
     * @var null|EmailTracking
     */
    protected $emailTracking;

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request): Response
    {
        if (is_null($this->emailTracking = $this->getEmailTracking($request))) {
            return $this->getBadResponse();
        }

        $this->executeEvent(
            $request->toArray()['event-data']['event'],
            $request->toArray()['event-data']['delivery-status']['code']
        );

        if ($this->emailTracking->save()) {
            return $this->getSuccessfulResponse();
        } else {
            return $this->getBadResponse();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function executeEvent(string $event, int $statusCode): void
    {
        switch ($event) {
            case "delivered":
                $this->delivered();
            break;
            case "opened":
                $this->opened();
            break;
            case "clicked":
                $this->clicked();
            break;
            case "unsubscribed":
                $this->unsubscribed();
            break;
            case "failed":
                if ($statusCode == 605) {
                    $this->permanent_fail();
                } else {
                    $this->temporary_fail();
                }
            break;
            case "complained":
                $this->complained();
            break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delivered(): void
    {
        $this->emailTracking->delivered = true;
    }

    /**
     * {@inheritdoc}
     */
    public function opened(): void
    {
        $this->emailTracking->opened = true;
        $this->emailTracking->opens++;
    }

    /**
     * {@inheritdoc}
     */
    public function clicked(): void
    {
        $this->emailTracking->clicked = true;
        $this->emailTracking->clicks++;
    }

    /**
     * {@inheritdoc}
     */
    public function unsubscribed(): void
    {
        $this->emailTracking->unsubscribed = true;
    }

    /**
     * {@inheritdoc}
     */
    public function permanent_fail(): void
    {
        $this->emailTracking->delivered = false;
        $this->emailTracking->bounced = true;
        $this->emailTracking->permanent_fail = true;
    }

    /**
     * {@inheritdoc}
     */
    public function temporary_fail(): void
    {
        $this->emailTracking->delivered = false;
        $this->emailTracking->bounced = false;
        $this->emailTracking->temporary_fail = true;
    }

    /**
     * {@inheritdoc}
     */
    public function complained(): void
    {
        $this->emailTracking->complained = true;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmailTracking(Request $request): void
    {
        $message_id = $request['event-data']['message']['headers']['message-id'];
        $this->emailTracking = EmailTracking::where('message_id', $message_id)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailTracking(Request $request): ?EmailTracking
    {
        if (is_null($this->emailTracking)) {
            $this->setEmailTracking($request);
        }

        return $this->emailTracking;
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessfulResponse(Request $request): JsonResponse
    {
        return response()->json([
             'status' => 'success',
             'message' => ['event' => 'Data updated successfully!'],
             'data' => $request->toArray()
         ], 200);
    }

    /**
     * {@inheritdoc}
     */
    public function getBadResponse(Request $request): JsonResponse
    {
        return response()->json([
             'status' => 'fail',
             'message' => ['event' => 'Unable to process request, bad data given.'],
             'data' => $request->toArray()
         ], 400);
    }
}
