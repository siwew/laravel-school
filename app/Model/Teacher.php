<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Teacher extends Model
{

    use Notifiable, HasApiTokens;


    protected $fillable = [
        'name',
        'email',
        'password'
    ];


    protected $hidden = ['password', 'remember_token'];

}
