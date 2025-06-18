<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Call extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'conversation_id',
        'started_by',
        'call_type',
        'started_at',
        'ended_at',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id ??= (string) Str::uuid();
            $model->started_at ??= now();
        });
    }

    /* Quan hệ */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function starter()
    {
        return $this->belongsTo(User::class, 'started_by');
    }
}
