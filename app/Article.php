<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
	protected $table = 'articles';

    
    protected $fillable = [
        'title',
        'desc',
        'url',
        'issue_id',
    ];


    public function issue()
  {
    return $this->hasOne('App\Issue', 'id', 'issue_id');
  }

}