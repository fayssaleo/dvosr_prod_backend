<?php

namespace App\Modules\Utilisateur\Models;

use App\Modules\ActionDetail\Models\ActionDetail;
use App\Modules\Vessel\Models\Vessel;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Action extends Model
{
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
    public function voyage()
    {
        return $this->belongsTo(Vessel::class);
    }
    public function actionDetails(){
        return $this->hasMany(ActionDetail::class);
    }
    protected $casts = [

        'created_at' => 'datetime:d/m/Y H:i',

    ];
    protected function serializeDate(DateTimeInterface $date)
    {
    return $date->format('Y-m-d H:i');
    }
}
