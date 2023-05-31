<?php

namespace App\Modules\Code\Models;

use App\Modules\Voyage\Models\OtherDelay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Code extends Model
{
    use HasFactory;
    protected $guarded=["id"];
    protected function serializeDate(DateTimeInterface $date)
    {
    return $date->format('Y-m-d H:i');
    }
    public function otherDelays()
    {
        return $this->hasMany(OtherDelay::class);
    }
}
