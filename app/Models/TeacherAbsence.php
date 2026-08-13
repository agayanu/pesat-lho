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
        'teacher_id',
        'teacher_name',
        'class_code',
        'status',
        'substitute_teacher_id',
        'substitute_teacher',
        'task_description',
        'piket_user',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function absentTeacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function substituteTeacher()
    {
        return $this->belongsTo(User::class, 'substitute_teacher_id');
    }

    public function getTeacherDisplayNameAttribute()
    {
        return $this->teacher->name ?? $this->teacher_name ?? '-';
    }

    public function getSubstituteDisplayNameAttribute()
    {
        return $this->substituteTeacher->name ?? $this->substitute_teacher ?? null;
    }
}
