<?php

namespace App\Http\Controllers\PremiumContent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Article;

class PremiumContentController extends Controller
{
    protected $article;

    public function __construct(Article $article)
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }
        $this->middleware(['auth','web','premium','2fa']);
        $this->article = $article;
    }
    public function index()
    {
        $articles = $this->article->all();
        return view('premiumcontent.content.index', [
        'articles' => $articles,
      ]);
    }

    public function show($slug)
    {
        $article = $this->article->whereSlug($slug)->first();
        return view('premiumcontent.content.show', [
        'article' => $article,
      ]);
    }
}
