<?php

namespace App\Modules\Utilisateur\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use DateTimeInterface;


class Utilisateur extends Authenticatable
{
    use  Notifiable, HasApiTokens ,HasFactory;
    protected $guarded=["id"];
    protected function serializeDate(DateTimeInterface $date)
    {
    return $date->format('Y-m-d H:i');
    }
    public function actions(){
        return $this->hasMany(Action::class);
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    protected $hidden = [
        'password',
    ];
}
