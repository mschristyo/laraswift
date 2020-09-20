<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['slug','title','content','view','article_category_id'];

    public function articleCategory()
    {
      return $this->belongsTo('App\ArticleCategory','id');
    }
}
