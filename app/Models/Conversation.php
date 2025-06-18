<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Conversation extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'type',
        'created_by',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id ??= (string) Str::uuid();
        });
    }

    /* Quan hệ */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'conversation_user')
            ->withPivot('joined_at', 'role');
           
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    public function users()
    {
        return $this->participants(); // alias cho dễ dùng
    }

    public function latestMessage()
{
    return $this->hasOne(Message::class)->latestOfMany();
}

}
