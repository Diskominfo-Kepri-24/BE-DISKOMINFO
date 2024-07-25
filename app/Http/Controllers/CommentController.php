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


        $user = User::find($id_user);

        $comment['name'] = $user->name;
        $comment['email'] = $user->email;

        return response()->json([
            "comment" => $comment,
        ]);
    }

    public function getComment(Request $request, News $berita){
        // ambil comments berdasarkan slug
        $comments = Comment::join('news', 'news.id', '=', 'comments.id_berita')
                        ->join('users', 'users.id', '=', 'comments.id_user')
                        ->select('comments.*', 'news.judul as berita_judul', 'users.name as user_name')
                        ->where('news.id', $berita->id)
                        ->orderBy("comments.tanggal", "desc")
                        ->get();

                        
        return response()->json([
            "komentar" => $comments
        ]);
    }

    public function updateComment(Request $request, Comment $komentar){
        
        if($request->user()->cannot('update', $komentar)){
            abort(403);
        }

        $request->validate([
            "isi_komentar" => "required"
        ]);

        $komentar->isi_komentar = $request->isi_komentar;

        $komentar->update();
        
        return response()->json([
            "komentar" => $komentar,
            "status" => "Komentar berhasil diubah"
        ]);
        
    }

    public function deleteComment(Request $request, Comment $komentar){

        if($request->user()->cannot('delete', $komentar)){
            abort(403);
        }

        $komentar->delete();

        return response()->json([
            "status" => "Komentar berhasil dihapus"
        ]);

    }

}
