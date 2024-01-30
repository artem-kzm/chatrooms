<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Str;

/**
 * @property int id
 * @property string email
 * @property string name
 * @property string developer_key
 *
 * @property-read Room[]|\Illuminate\Database\Eloquent\Collection rooms
 */
class Account extends Model
{
    use HasFactory;

    /** @var bool */
    public $timestamps = false;

    public function generateAndSetDeveloperKey(): void
    {
        $this->developer_key = Str::random(64);
    }

    public function rooms(): Relations\HasMany
    {
        return $this->hasMany(Room::class);
    }
}
