<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialActivityReport extends Model
{
    use HasFactory;

    protected $table = 'special_activity_reports';

    protected $fillable = [
        'date',
        'unit_name',
        'class_or_participants',
        'material_activity',
        'pic_teacher',
        'notes',
        'user',
    ];
}
