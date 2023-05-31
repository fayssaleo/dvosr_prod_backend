<?php

namespace App\Modules\Crane\Models;

use App\Modules\Voyage\Models\CraneVoyage;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Crane extends Model
{
    protected $guarded=["id"];
    protected function serializeDate(DateTimeInterface $date)
    {
    return $date->format('Y-m-d H:i');
    }
    public function craneVoyages()
    {
        return $this->hasMany(CraneVoyage::class);
    }
}
