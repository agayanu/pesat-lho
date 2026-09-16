<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLhoAttachment extends Model
{
    use HasFactory;

    protected $table = 'daily_lho_attachments';

    protected $fillable = [
        'date',
        'daily_lho_report_id',
        'role',
        'file_label',
        'file_path',
        'file_name',
        'file_type',
        'extension',
        'file_size',
        'uploaded_by',
    ];

    public function report()
    {
        return $this->belongsTo(DailyLhoReport::class, 'daily_lho_report_id');
    }

    public function isImage(): bool
    {
        return $this->file_type === 'image' || in_array(strtolower($this->extension), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    }

    public function isDocument(): bool
    {
        return !$this->isImage();
    }
}
