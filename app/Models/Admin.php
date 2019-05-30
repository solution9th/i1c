<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends \Illuminate\Foundation\Auth\User
{
    protected $fillable = ['email', 'name', 'userid'];
}
