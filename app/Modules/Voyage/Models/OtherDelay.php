<?php

namespace App\Modules\Voyage\Models;

use App\Modules\Code\Models\Code;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;


class OtherDelay extends Model
{
    protected $table="other_delays";
    protected $guarded=["id"];
    protected function serializeDate(DateTimeInterface $date)
    {
    return $date->format('Y-m-d H:i');
    }
    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }
    public function code()
    {
        return $this->belongsTo(Code::class);
    }
    protected $casts = [
        'from' => 'datetime:d/m/Y H:i',
        'to' => 'datetime:d/m/Y H:i',
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];
}
