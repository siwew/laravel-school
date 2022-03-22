<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FollowList extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_id',
        'status',
    ];
}
