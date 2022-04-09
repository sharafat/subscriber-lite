<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SubscriberField extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    public $table = 'subscriber_fields';

    protected $guarded = ['id'];
}
