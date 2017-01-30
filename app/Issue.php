<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
	protected $table = 'issue';

    
    protected $fillable = [
        'name',
        'season',
        'year',
    ];


}