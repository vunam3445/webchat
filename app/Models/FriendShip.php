<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FriendShip extends Model
{
    protected $table = 'friendships';

    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
    ];

    public $incrementing = false; // Vì không dùng cột id tự tăng
    protected $keyType = 'string';

    /**
     * Người gửi lời mời kết bạn (chính là user hiện tại)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Người được gửi lời mời kết bạn
     */
    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
