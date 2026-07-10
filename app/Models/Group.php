<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['kode_group'];

    public function metrics()
    {
        return $this->hasMany(Metric::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}