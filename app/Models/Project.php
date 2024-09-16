<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];
    public function users(){
        return $this->belongsToMany(User::class)->withPivot('role', 'hours', 'last_activity')->withTimestamps();;
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    
    // get the latest task
    public function latestTask()
    {
        return $this->hasOne(Task::class)->latestOfMany();
    }

    // to get the oldest task
    public function oldestTask()
    {
        return $this->hasOne(Task::class)->oldestOfMany();
    }
    public function getTheHighestPriorityTask($titleCondition){
        return $this->hasOne('Task::class')->ofMany('priority','high')
        ->where('title','like','%','$titleCondition','%');
    }    
}
