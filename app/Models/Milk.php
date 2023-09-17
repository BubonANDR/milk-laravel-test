<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milk extends Model
{
    use HasFactory;

    protected $fillable=[
       'name',            //имя того кто залил порцию
       'volume',           //объем порции
       'bottle_num',      //номер цистерны
       'total_left'       //остаток цистерны относительно объема = 300л
    ];
}
