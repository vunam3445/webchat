<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageRead;
use App\Models\Call;
use Domain\Friendship\Entity\Friendship;
use Laravel\Sanctum\HasApiTokens;  // thêm use này

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // Khóa chính là UUID thay vì int auto-increment
    protected $keyType = 'string';
    public $incrementing = false;

    // Các trường được phép gán hàng loạt (mass assignable)
    protected $fillable = [
        'id',
        'name',
        'phone',
        'email',
        'password',
        'avatar',
        'email_verified_at',
    ];

    // Trường bị ẩn khi chuyển sang mảng hoặc JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tự động cast các trường này sang kiểu tương ứng
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    // 1. Conversations được tạo bởi user
    public function createdConversations()
    {
        return $this->hasMany(Conversation::class, 'created_by');
    }

    // 2. Các cuộc trò chuyện mà user tham gia (nhiều-nhiều)
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user', 'user_id', 'conversation_id')
            ->withPivot('joined_at', 'role')
            ->withTimestamps();
    }

    // 3. Tin nhắn được gửi bởi user
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // 4. Lượt đọc tin nhắn của user
    public function messageReads()
    {
        return $this->hasMany(MessageRead::class, 'user_id');
    }

    // 5. Cuộc gọi mà user bắt đầu
    public function startedCalls()
    {
        return $this->hasMany(Call::class, 'started_by');
    }

    // Danh sách lời mời do user gửi
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'user_id');
    }

    // Danh sách lời mời mà user nhận
    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'friend_id');
    }

    // Danh sách bạn bè đã chấp nhận
    public function friends()
    {
        return User::whereIn('id', function ($query) {
            $query->selectRaw('CASE 
                WHEN user_id = ? THEN friend_id 
                WHEN friend_id = ? THEN user_id 
            END', [$this->id, $this->id])
                ->from('friends')
                ->where('status', 'accepted')
                ->where(function ($q) {
                    $q->where('user_id', $this->id)
                        ->orWhere('friend_id', $this->id);
                });
        });
    }
}
