<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cities() {
        return $this->hasMany(City::class);
    }

    public function city($cityId) {
        return $this->cities()->where('id', $cityId)->first();
    }

    public function researches() {
        return $this->hasMany(Research::class);
    }

    public function research($researchId) {
        return $this->researches()->where('research_id', $researchId)->first();
    }

    public function researchesQueue() {
        return $this->hasOne(ResearchQueue::class);
    }

    public function unreadMessagesNumber() {
        return $this->hasMany(Message::class)->where('is_read', 0)->count();
    }
}
