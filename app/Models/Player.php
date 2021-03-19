<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'position', 'country_id', 'team_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
}
