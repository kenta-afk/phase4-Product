<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // このモデルが関連付けられるテーブル名
    protected $table = 'appointments';

    // 複数代入可能な属性
    protected $fillable = [
        'status',
        'room_id',
        'visitor_name',
        'visitor_company',
        'date',
        'comment',
        'event_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'appointments_users');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
