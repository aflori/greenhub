<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogArticle;

class BlogArticleController extends Controller
{
    public function index()
    {
        return BlogArticle::all();
    }

    public function store(Request $request)
    {
        $newArticle = new BlogArticle();
        foreach ($request->request as $columnName => $columnValue) {
            $newArticle->$columnName = $columnValue;
        }
        $newArticle->save();
        return $newArticle;
    }

    public function show(BlogArticle $article ) {
        return $article;
    }

    public function update(Request $request, BlogArticle $article)
    {
        if ( isset($request->id) ) {
            return response()->json(["error" => "cannot change id"], 404);
        }

        foreach ($request->request as $attributeName => $attributeValue) {
            $article->$attributeName = $attributeValue;
        }
        $article->save();
        return $article;
    }

    public function destroy(BlogArticle $article) {
        $article->delete();
        return $article;
    }
}
