<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_photo', 'country_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function competitions()
    {
        return $this->belongsToMany(Competition::class, 'competition_team', 'team_id', 'competition_id');
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

}
