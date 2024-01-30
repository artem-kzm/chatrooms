<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

/**
 * @property int id
 * @property int account_id
 * @property string name
 * @property string friendly_url
 * @property int max_duration
 * @property \Illuminate\Support\Carbon created_at
 */
class Room extends Model
{
    use HasFactory;

    /** @var bool */
    public $timestamps = false;

    /** @var string[] */
    protected $casts = [
        'created_at' => 'datetime'
    ];

    /** @var string[] */
    protected $fillable = [
        'name',
        'friendly_url',
        'max_duration',
    ];

    public function belongsToAccount(Account $account): bool
    {
        return $this->account_id === $account->id;
    }

    public function account(): Relations\BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
