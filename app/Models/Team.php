<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'team_type_id', 'owner_id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function teamType()
    {
        return $this->belongsTo(TeamType::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
