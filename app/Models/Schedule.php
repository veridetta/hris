<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Schedule extends Model
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $fillable = [
        'employees_id',
        'shifts_id',
        'dates',
    ];
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function shits()
    {
        return $this->hasMany(Shift::class);
    }
}
