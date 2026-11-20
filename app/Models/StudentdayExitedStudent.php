<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentdayExitedStudent extends Model
{
    use HasFactory;

    protected $table = 'studentday_exited_students';

    protected $fillable = [
        'student_id',
        'studentday_id',
        'id_siswa',
        'student_name',
        'student_class',
        'studentday_name',
        'exit_date',
        'reason',
        'notes',
        'user',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function studentday()
    {
        return $this->belongsTo(Studentday::class, 'studentday_id');
    }
}
