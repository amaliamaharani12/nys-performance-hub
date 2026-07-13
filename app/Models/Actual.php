<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actual extends Model
{
    protected $fillable = ['metric_id', 'periode', 'nilai_actual', 'input_by', 'sumber', 'status', 'catatan'];

    protected function casts(): array
    {
        return [
            'periode' => 'date',
        ];
    }

    public function metric()
    {
        return $this->belongsTo(Metric::class);
    }

    public function inputBy()
    {
        return $this->belongsTo(User::class, 'input_by');
    }

    public function revisionLogs()
    {
        return $this->hasMany(RevisionLog::class);
    }
}