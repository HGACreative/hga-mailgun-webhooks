<?php

declare(strict_types=1);

namespace HgaCreative\MailgunWebhooks\Contracts;

use HgaCreative\MailgunWebhooks\Models\EmailTracking;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface MailgunWebhooks
{

    /**
    * Handles all incoming requests
    *
    * @param  Request  $request
    * @return \Illuminate\Http\Response
    */
    public function handle(Request $request): Response;

    /**
    * Executes the relevant function for the event
    *
    * @param  string $event
    * @param  int $statusCode
    * @return void
    */
    public function executeEvent(string $event, int $statusCode): void;

    /**
    * Action a delivered webhook
    *
    * @return void
    */
    public function delivered(): void;

    /**
     * Action an opened webhook
     *
     * @return void
     */
    public function opened(): void;

    /**
     * Action a clicked webhook
     *
     * @return void
     */
    public function clicked(): void;

    /**
     * Action an unsubscribed webhook
     *
     * @return void
     */
    public function unsubscribed(): void;

    /**
     * Action an permanent_fail
     *
     * @return void
     */
    public function permanent_fail(): void;

    /**
     * Action an temporary_fail
     *
     * @return void
     */
    public function temporary_fail(): void;

    /**
     * Action an complained
     *
     * @return void
     */
    public function complained(): void;

    /**
     * Sets the email tracking model to the global variable
     * finds via the api_id
     * if api_id matches, a valid model is set else null is set
     * @param  Request $request
     * @return void
     */
    public function setEmailTracking(Request $request): void;

    /**
     * returns the emailTracking global variable
     * calls setEmailTracking if the variable is null
     *
     * @param  Request $request
     * @return EmailTracking the model
     */
    public function getEmailTracking($request): ?EmailTracking;

    /**
     * Returns the request to be sent to the mail server
     * @return JsonResponse
     */
    public function getSuccessfulResponse($request): JsonResponse;

    /**
     * Returns the request to be sent to the mail server
     * @return JsonResponse
     */
    public function getBadResponse($request): JsonResponse;

}
