<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studentday extends Model
{
    use HasFactory;

    protected $table = 'studentdays';

    protected $fillable = [
        'name',
        'teacher',
    ];

    public function teacherUser()
    {
        return $this->belongsTo(User::class, 'teacher');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'studentday');
    }

    public function exitedStudents()
    {
        return $this->hasMany(StudentdayExitedStudent::class, 'studentday_id');
    }
}
