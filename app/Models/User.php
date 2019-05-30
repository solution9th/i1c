<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const GENERAL = 1;
    const ADMIN   = 2;

    protected $fillable = ['email', 'name', 'mobile', 'infomation', 'type'];

    public function setInfomationAttribute($value)
    {
        $this->infomation = json_encode($value);
    }

    public function getInfomationAttribute()
    {
        return json_decode($this->infomation, true);
    }
}
