# HGA Mailgun Webhooks

Provides a seamless way of tracking sent emails.

**Install**

    composer require hgacreative/mailgun-webhooks

 **Migration**

    php artisan vendor:publish

publish the tag named: *mailgunWebhooks-migrations*

    php artisan migrate

 **Creating EmailTracking models**
Import

    use HgaCreative\MailgunWebhooks\Models\EmailTracking;

Create the entry with the following data when you send emails

            EmailTracking::create([
            'to' => $name,
            'email' => $email,
            'message_id' => $response->http_response_body->id,
            'sent' => $sent,
            'bounced' => $bounced
        ]);
