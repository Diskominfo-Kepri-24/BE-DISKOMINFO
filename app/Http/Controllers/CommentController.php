<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    
    public function addComment(Request $request, News $berita){

        $id_user = $request->user()->id;

        $request->validate([
           "isi_komentar" => "required", 
        ]);

        $comment = Comment::query()->create([
            "id_berita" => $berita->id,
            "id_user" => $id_user,
            "tanggal" => date("Y-m-d h:i:s", time()),
            "isi_komentar" => $request->isi_komentar,
        ]);

        // $user = $comment->query()->where("slug", $berita->slug)->comment();
        // $user = User::query()->where("id", $id_user)
        //                     ->join("comments", "comments.id_user", "=", "users.id")
        //                     ->first();
        // $user = $comment->query()->find($id_user);

        $user = User::find($id_user);

        $comment['name'] = $user->name;
        $comment['email'] = $user->email;

        return response()->json([
            "comment" => $comment,
        ]);
    }

    public function updateComment(Request $request, News $berita, $idComment){

        
    }

    public function deleteComment(Request $request, News $berita, $idComment){

        

    }

}
