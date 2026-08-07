<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLhoReport extends Model
{
    use HasFactory;

    protected $table = 'daily_lho_reports';

    protected $fillable = [
        'date',
        'ph_user',
        'ph_notes',
        'ph_file',
        'kadep_user',
        'kadep_global_notes',
        'kadep_ph_notes',
        'kadep_file',
        'kepsek_user',
        'kepsek_notes',
        'kepsek_file',
        'status',
    ];
}
