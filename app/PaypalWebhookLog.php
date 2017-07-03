<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaypalWebhookLog extends Model
{
    protected $table = "paypal_webhook_log";
    public $timestamps = false;
}
