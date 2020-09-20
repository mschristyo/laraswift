<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ArticleCategory;
use Str;

class ArticleCategoryController extends Controller
{
    protected $articleCategory;
    public function __construct(ArticleCategory $articleCategory)
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }
        $this->middleware(['auth','web','role:admin','2fa']);

        $this->category = $articleCategory;
    }

    public function index()
    {
        $categories = $this->category->all();
        return view('premiumcontent.articleCategory.index', [
        'categories' => $categories
      ]);
    }

    public function create()
    {
        $categories = $this->category->all();
        return view('premiumcontent.articleCategory.create', [
        'categories' => $categories,
      ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'name' => 'required|min:4|regex:/^[A-Za-z0-9_.,() ]+$/',
          'description' => 'regex:/^[A-Za-z0-9_.,() ]+$/|nullable|',
        ],[
          'name.regex' => 'Invalid Entry! The name only letter and numbers are allowed',
          'description.regex' => 'Invalid Entry! The description only letter and numbers are allowed',
        ]);

        $category = $this->category->create([
        'name' => $request->name,
        'description' => $request->description,
        'slug' => Str::slug(strtolower($request->name), '-'),
      ]);
        if ($category) {
            return redirect()->back()->with('success', 'Category added successfully');
        } else {
            return redirect()->back()->with('error', 'Ops! an error occured');
        }
    }


    public function edit($slug)
    {
        $category = $this->category->whereSlug($slug)->first();
        return view('premiumcontent.articleCategory.edit', [
        'category' => $category,
      ]);
    }

    public function update(Request $request, $slug)
    {
        $this->validate($request, [
          'name' => 'required|regex:/^[A-Za-z0-9_.,() ]+$/',
          'description' => 'regex:/^[A-Za-z0-9_.,() ]+$/',
        ],[
          'name.regex' => 'Invalid Entry! The name only letter and numbers are allowed',
          'description.regex' => 'Invalid Entry! The description only letter and numbers are allowed',
        ]);
        $category = $this->category->whereSlug($slug)->update([
        'name' => $request->name,
        'description' => $request->description,
      ]);
        if ($category) {
            return redirect()->back()->with('success', 'Category updated successfully');
        } else {
            return redirect()->back()->with('error', 'Ops! an error occured try again');
        }
    }

    public function delete()
    {
    }
}
