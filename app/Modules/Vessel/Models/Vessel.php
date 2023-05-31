<?php

namespace App\Modules\Vessel\Models;

use App\Modules\Utilisateur\Models\Action;
use App\Modules\Voyage\Models\Voyage;
use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    protected $guarded=["id"];
    public function voyage()
    {
        return $this->hasOne(Voyage::class);
    }
    public function actions(){
        return $this->hasMany(Action::class)->with("utilisateur")->get();
    }
    
    public function actions2(){
        return $this->hasMany(Action::class);
    }
    protected $casts = [
        'eta' => 'datetime:d/m/Y H:i',
        'etd' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
        'created_at' => 'datetime:d/m/Y H:i',

    ];
}
