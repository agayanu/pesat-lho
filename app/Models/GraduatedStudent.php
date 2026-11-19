<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GraduatedStudent extends Model
{
    use HasFactory;

    protected $table = 'graduated_students';

    protected $fillable = [
        'original_student_id',
        'id_siswa',
        'name',
        'gender',
        'classes',
        'program',
        'studentday',
        'graduation_date',
        'notes',
        'user',
    ];

    public function studentdayRef()
    {
        return $this->belongsTo(Studentday::class, 'studentday');
    }

    public function getStudentdayNameAttribute()
    {
        return $this->studentdayRef->name ?? $this->studentday ?? '-';
    }
}
