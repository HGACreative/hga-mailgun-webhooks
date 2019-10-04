<?php

namespace HgaCreative\MailgunWebhooks\Models;

use HgaCreative\MailgunWebhooks\Traits\AutomateUuid;

use Illuminate\Database\Eloquent\Model;

/**
 * @since 1.0.0
 */
class EmailTracking extends Model
{
    use AutomateUuid;

    /**
     * The name of the table this model references
     * @var string
     */
    public $table = 'email_tracking';

    /**
     * The primary key `id` does not autoincrement
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Override the default primary key type
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attribues which are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'to',
        'email',
        'message_id',
        'sent',
        'bounced',
        'delivered',
        'opened',
        'opens',
        'clicked',
        'clicks',
        'unsubscribed',
        'complained',
        'permanent_fail',
        'temporary_fail',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sent'              => 'boolean',
        'bounced'           => 'boolean',
        'delivered'         => 'boolean',
        'opened'            => 'boolean',
        'opens'             => 'integer',
        'clicked'           => 'boolean',
        'clicks'            => 'integer',
        'unsubscribed'      => 'boolean',
        'complained'        => 'boolean',
        'permanent_fail'    => 'boolean',
        'temporary_fail'    => 'boolean',
    ];

    /**
     * The attributes to append to each model instance
     *
     * @var array
     */
    protected $appends = ['name'];

    /**
     * Mutate the name attribute
     *
     * @return null|string;
     */
    public function setNameAttribute()
    {
        return $this->attributes['to'];
    }

    /**
     * Mutate the name attribute
     *
     * @return null|string;
     */
    public function getNameAttribute()
    {
        return $this->attributes['to'];
    }

    /**
     * Mutate the message_id attribute
     * Replace the < and the > from the following looking id:
     * <20190524083106.1.97D2CAF2DE7AA8D6@automated-mail.cpp.live>
     * The reason we do this is because this is ommited when you recieve the webhook request
     * It makes it easier to compare the ids and get a match and also enforces clean data storage within the database
     * @param string $value the api id to mutate
     */
    public function setMessageIdAttribute($value)
    {
        $this->attributes['message_id'] = preg_replace('/[<^>]/', '', $value);
    }

}
