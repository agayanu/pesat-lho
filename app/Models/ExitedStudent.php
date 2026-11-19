<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitedStudent extends Model
{
    use HasFactory;

    protected $table = 'exited_students';

    protected $fillable = [
        'original_student_id',
        'id_siswa',
        'name',
        'gender',
        'classes',
        'program',
        'studentday',
        'exit_date',
        'reason',
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
