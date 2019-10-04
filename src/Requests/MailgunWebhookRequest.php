<?php

namespace HgaCreative\MailgunWebhooks\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Str;

/**
 * @since 1.0.0
 */
class MailgunWebhookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Str::startsWith(request()->headers->all()['user-agent'][0], 'mailgun/')
                || request()->headers->all()['host'][0] == 'cheshireairportparking.test'
                || request()->headers->all()['host'][0] == 'cpp.live');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
