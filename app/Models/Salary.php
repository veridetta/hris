<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Salary extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'jabatan_id',
        'salary',
        'insentif',
        'lembur',
        'potongan',
    ];
    public function jabatans()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
