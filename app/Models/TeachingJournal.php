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
        'teacher_name',
        'material',
        'activity',
        'user',
    ];
}
