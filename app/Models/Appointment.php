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
        'visitor_id',
        'host_id',
        'room_id',
        'appointment_date',
        'purpose',
        'status'
    ];

}
