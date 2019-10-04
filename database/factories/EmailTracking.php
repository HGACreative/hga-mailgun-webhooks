<?php


use Faker\Generator as Faker;

$factory->define(HgaCreative\MailgunWebhooks\Models\EmailTracking::class, function (Faker $faker) {
    return [
        'to' => $faker->name,
        'email' => $faker->email,
        'message_id' => $faker->uuid . '@automated-mail.test.com'
    ];
});
