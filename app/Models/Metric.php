<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    protected $fillable = ['group_id', 'nama_item', 'satuan', 'arah_target', 'deskripsi', 'is_aktif'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function targets()
    {
        return $this->hasMany(Target::class);
    }

    public function actuals()
    {
        return $this->hasMany(Actual::class);
    }
}