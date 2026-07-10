<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected function casts(): array
    {
        return [
            'periode_mulai' => 'date',
            'periode_selesai' => 'date',
        ];
    }
    protected $fillable = ['metric_id', 'nilai_target', 'periode_tipe', 'periode_mulai', 'periode_selesai', 'set_by'];

    public function metric()
    {
        return $this->belongsTo(Metric::class);
    }

    public function setBy()
    {
        return $this->belongsTo(User::class, 'set_by');
    }
}