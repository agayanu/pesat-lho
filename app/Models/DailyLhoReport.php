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
        'ph_handwriting_img',
        'kadep_user',
        'kadep_global_notes',
        'kadep_ph_notes',
        'kadep_file',
        'kadep_handwriting_img',
        'kepsek_user',
        'kepsek_notes',
        'kepsek_file',
        'kepsek_handwriting_img',
        'status',
    ];

    public function attachments()
    {
        return $this->hasMany(DailyLhoAttachment::class, 'daily_lho_report_id');
    }

    public function phAttachments()
    {
        return $this->hasMany(DailyLhoAttachment::class, 'daily_lho_report_id')->where('role', 'PH');
    }

    public function kadepAttachments()
    {
        return $this->hasMany(DailyLhoAttachment::class, 'daily_lho_report_id')->where('role', 'KADEP');
    }

    public function kepsekAttachments()
    {
        return $this->hasMany(DailyLhoAttachment::class, 'daily_lho_report_id')->where('role', 'KEPSEK');
    }
}
