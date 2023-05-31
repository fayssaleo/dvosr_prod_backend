<?php

namespace App\Modules\ActionDetail\Models;

use App\Modules\Utilisateur\Models\Action;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ActionDetail extends Model
{
    use HasFactory;
    protected function serializeDate(DateTimeInterface $date)
    {
    return $date->format('Y-m-d H:i');
    }
    public function action()
    {
        return $this->belongsTo(Action::class);
    }
    protected $casts = [

        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:Y-m-d H:i',

    ];
    
}
