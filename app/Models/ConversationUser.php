<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class ConversationUser extends Model
{
    use HasFactory;

    protected $table = 'conversation_user';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id', 'conversation_id', 'user_id', 'joined_at', 'role'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id ??= (string) Str::uuid();
            $model->joined_at ??= now();
        });
    }

    /* Quan hệ */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class)
        ->withPivot(['role', 'joined_at'])
        ->withTimestamps();
    }
}
