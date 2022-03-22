<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Student extends Model
{

    use Notifiable, HasApiTokens;


    protected $fillable = [
        'name',
        'account',
        'password',
        'school_id'
    ];


    protected $hidden = ['password', 'remember_token'];

    public function findForPassport($username)
    {
        return $this->where('account', $username)->first();
    }
}
