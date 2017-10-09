<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeWebhookLog extends Model {

    protected $table = 'stripe_webhook_logs';
    protected $primaryKey = 'stripe_log_id';
    public $timestamp = false;
    public $incrementing = false;

}
