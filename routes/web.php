<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'webhooks'], function() {
    Route::post('mailgun', '\HgaCreative\MailgunWebhooks\MailgunWebhooks@handle')->name('webhooks.mailgun');
});
