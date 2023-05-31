<?php

namespace App\Modules\Voyage\Models;

use App\Modules\Crane\Models\Crane;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class CraneVoyage extends Model
{
    protected $table="crane_voyages";
    protected $guarded=["id"];
    protected function serializeDate(DateTimeInterface $date)
    {
    return $date->format('Y-m-d H:i');
    }
    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }
    public function crane()
    {
        return $this->belongsTo(Crane::class);
    }
    protected $casts = [
        'cbd' => 'datetime:d/m/Y H:i',
        'dgbohc_bfl_from' => 'datetime:d/m/Y H:i',
        'dgbohc_bfl_to' => 'datetime:d/m/Y H:i',
        'dss_bfl_from' => 'datetime:d/m/Y H:i',
        'dss_bfl_to' => 'datetime:d/m/Y H:i',
        'ffl' => 'datetime:d/m/Y H:i',
        'fll' => 'datetime:d/m/Y H:i',
        'sfl' => 'datetime:d/m/Y H:i',
        'sll' => 'datetime:d/m/Y H:i',
        'tfl' => 'datetime:d/m/Y H:i',
        'tll' => 'datetime:d/m/Y H:i',
        'fofl' => 'datetime:d/m/Y H:i',
        'foll' => 'datetime:d/m/Y H:i',
        'fifl' => 'datetime:d/m/Y H:i',
        'fill' => 'datetime:d/m/Y H:i',
        'sifl' => 'datetime:d/m/Y H:i',
        'sill' => 'datetime:d/m/Y H:i',
        'sevfl' => 'datetime:d/m/Y H:i',
        'sevll' => 'datetime:d/m/Y H:i',
        'eifl' => 'datetime:d/m/Y H:i',
        'eill' => 'datetime:d/m/Y H:i',
        'nfl' => 'datetime:d/m/Y H:i',
        'nll' => 'datetime:d/m/Y H:i',
        'tenfl' => 'datetime:d/m/Y H:i',
        'tenll' => 'datetime:d/m/Y H:i',
        'lgbohc_all_from' => 'datetime:d/m/Y H:i',
        'lgbohc_all_to' => 'datetime:d/m/Y H:i',
        'lss_all_from' => 'datetime:d/m/Y H:i',
        'lss_all_to' => 'datetime:d/m/Y H:i',
        'cbu' => 'datetime:d/m/Y H:i',
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];
}
