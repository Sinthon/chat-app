<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'room_id',
        'user_id',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->user_id = auth()->user()->id;
            return true;
        });

        self::created(function ($model) {
            return true;
        });
    }

    /**
     * Get the user associated with the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the room associated with the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function room(): HasOne
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
}
