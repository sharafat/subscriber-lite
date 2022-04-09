<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    use HasFactory, SoftDeletes;

    public const STATE_ACTIVE = 'active';
    public const STATE_UNSUBSCRIBED = 'unsubscribed';
    public const STATE_JUNK = 'junk';
    public const STATE_BOUNCED = 'bounced';
    public const STATE_UNCONFIRMED = 'unconfirmed';
    public const STATES = [
        self::STATE_ACTIVE,
        self::STATE_UNSUBSCRIBED,
        self::STATE_JUNK,
        self::STATE_BOUNCED,
        self::STATE_UNCONFIRMED,
    ];

    protected $guarded = ['id'];

    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(Field::class, 'subscriber_fields')
            ->withPivot('value');
    }
}
