<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name', 'pid', 'loginfield',
        'regfield', 'path', 'number',
        'user', 'logo', 'protocol',
        'description','regtitle', 'logintitle',
        'loginstart', 'loginend', 'regstart',
        'regend', 'secret'
    ];

    protected $appends = ['loginurl', 'regurl'];

    public function users()
    {
        return $this->belongsToMany(\App\User::class, 'user_project');
    }

    public function getLoginfieldAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getRegfieldAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getLoginurlAttribute()
    {
        return "{$this->path}/{$this->pid}/login";
    }

    public function getRegurlAttribute()
    {
        return "{$this->path}/{$this->pid}/signup";
    }
}
