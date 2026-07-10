<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisionLog extends Model
{
    protected $fillable = ['actual_id', 'revised_by', 'nilai_lama', 'nilai_baru', 'alasan'];

    public function actual()
    {
        return $this->belongsTo(Actual::class);
    }

    public function revisedBy()
    {
        return $this->belongsTo(User::class, 'revised_by');
    }
}