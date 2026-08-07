<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAbsence extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_absences';

    protected $fillable = [
        'date',
        'class_code',
        'jam_ke',
        'student_id',
        'status',
        'user',
        'is_edited_by_piket',
        'piket_user',
        'edit_reason',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
