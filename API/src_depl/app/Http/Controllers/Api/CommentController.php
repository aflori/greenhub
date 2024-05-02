<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index()
    {
        return Comment::all();
    }

    public function store(Request $request)
    {
        $newComment = new Comment();
        foreach ($request->request as $columnName => $columnValue) {
            $newComment->$columnName = $columnValue;
        }
        $newComment->save();
        return $newComment;
    }

    public function show(Comment $comment ) {
        return $comment;
    }

    public function update(Request $request, Comment $comment)
    {
        if ( isset($request->id) ) {
            return response()->json(["error" => "cannot change id"], 404);
        }

        foreach ($request->request as $attributeName => $attributeValue) {
            $comment->$attributeName = $attributeValue;
        }
        $comment->save();
        return $comment;
    }

    public function destroy(Comment $comment) {
        $comment->delete();
        return $comment;
    }
}
