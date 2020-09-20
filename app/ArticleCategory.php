<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    protected $fillable = ['name','description','slug'];

    public function article()
    {
      return $this->hasOne('App\Article');
    }
}
