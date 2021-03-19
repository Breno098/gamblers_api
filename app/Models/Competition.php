<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'active', 'name_photo', 'season'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'competition_team', 'team_id', 'Competition_id');
    }
}
