<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Session;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
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
        'password'=> 'hashed',
    ];
    //define the relationship between the user and projects
    public function projects()
{
    return $this->belongsToMany(Project::class)->withPivot('role', 'hours', 'last_activity')->withTimestamps();
}

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    //to calculate the hours by user 
public function calculateHours(){
    $session=Session::find($session->id);
    $startTime=new Carbon($session->start_time);
    $endTime=new Carbon($session->end_time);
    $duration = $startTime->diff($endTime);
    return $duration->h.$duration->i.$duration;
    
}
public function tasks(){
    return $this->hasManyThrough(Task::class,Project::class);
}
}
