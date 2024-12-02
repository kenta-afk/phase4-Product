<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    use HasFactory; 
    // このモデルが関連付けられるテーブル名 
    protected $table = 'hosts'; 
    
    // 複数代入可能な属性 
    protected $fillable = [
         'host_name', 
         'slack_id'
    ];
}
