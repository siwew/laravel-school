<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SchoolTeacher extends Model
{
    protected $fillable = [
        'school_id',
        'teacher_id',
        'status',
    ];
}
