<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function tasks(){
        return $this->hasMany(\App\Task::class);
    }
}
