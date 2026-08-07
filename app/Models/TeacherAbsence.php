<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAbsence extends Model
{
    use HasFactory;

    protected $table = 'teacher_absences';

    protected $fillable = [
        'date',
        'teacher_name',
        'class_code',
        'status',
        'substitute_teacher',
        'task_description',
        'piket_user',
    ];
}
