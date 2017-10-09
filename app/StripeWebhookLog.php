<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeWebhookLog extends Model {

    protected $table = 'stripe_webhook_log';
    protected $primaryKey = 'stripe_log_id';
    public $timestamps = false;
    public $incrementing = false;
    
}
