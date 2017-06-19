<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'payment_log';
}
