<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingJournal extends Model
{
    use HasFactory;

    protected $table = 'teaching_journals';

    protected $fillable = [
        'date',
        'class_code',
        'jam_ke',
        'teacher_id',
        'teacher_name',
        'material',
        'activity',
        'user',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function getTeacherDisplayNameAttribute()
    {
        return $this->teacher->name ?? $this->teacher_name ?? '-';
    }
}
