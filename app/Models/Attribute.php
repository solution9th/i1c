<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'name', 'display', 'description',
        'type', 'min', 'max', 'size', 'confine',
        'must'];
}
