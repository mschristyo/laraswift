<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Article;
use App\ArticleCategory;
use Purifier;
use Str;
use URL;

class ArticleController extends Controller
{
    protected $article;
    protected $articleCategory;
    public function __construct(Article $article, ArticleCategory $articleCategory)
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }
        $this->middleware(['auth','web','role:admin','2fa']);
        $this->article = $article;
        $this->articleCategory = $articleCategory;
    }
    public function index()
    {
        $articles = $this->article->all();
        return view('premiumcontent.article.index', [
        'articles' => $articles,
      ]);
    }

    public function create()
    {
        $categories = $this->articleCategory->all();
        return view('premiumcontent.article.create', [
        'categories' => $categories,
      ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|regex:/^[A-Za-z0-9_.,() ]+$/',
            'content' => 'required|string',
            'category' => 'numeric|required',
        ],[
          'regex' => 'Invalid Entry! The title only letter and numbers are allowed',
        ]);

        Article::create([
        'title' => $request->title,
        'content' => clean($request->content),
        'slug' => "article-".Str::random(30),
        'article_category_id' => $request->category,
      ]);
        return redirect()->back()->with('success', 'Article Created Successfully');
    }

    public function show($slug)
    {
        $article = $this->article
        ->whereSlug($slug)->first();
        return view('premiumcontent.article.show', [
      'article' => $article,
    ]);
    }

    public function edit($slug)
    {
        $article = $this->article->whereSlug($slug)->first();
        $categories = $this->articleCategory->all();
        return view('premiumcontent.article.edit', [
        'article' => $article,
        'categories' => $categories,
      ]);
    }

    public function update(Request $request, $slug)
    {
        $this->validate($request, [
          'title' => 'required|regex:/^[A-Za-z0-9_.,() ]+$/',
          'content' => 'required|string',
          'category' => 'numeric|required'
        ],[
          'regex' => 'Invalid Entry! The title only letter and numbers are allowed',
        ]);

        $this->article->whereSlug($slug)->update([
        'title' => $request->title,
        'content' => clean($request->content),
        'article_category_id' => $request->category
    ]);

        return redirect()->back()->with('success', 'Article Updated Successfully');
    }

    public function destroy($slug)
    {
        $article = $this->article->whereSlug($slug)->first();
        $article->delete();
        return redirect()->back()->with('success', 'Article Deleted Successfully');
    }

    public function articleImageUpload(Request $request)
    {
        $this->validate($request, [
           'image' => 'required|image|max:4098|mimes:jpeg,bmp,png,jpg',
        ]);

        $file = $request->file('image');
        $image = date('Ymdhis').'.'.$file->getClientOriginalExtension();

        $imagePath = "uploads/articleImages";
        $file->move($imagePath, $image);

        $url = URL::to('uploads/articleImages')."/".$image;

        echo json_encode($url);
    }
}
