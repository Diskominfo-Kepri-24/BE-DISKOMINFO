<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    
    public function addComment(Request $request, News $berita){

        $id_user = $request->user()->id;

        $request->validate([
            
        ]);

    }

    public function updateComment(Request $request, News $berita, $idComment){

        
    }

    public function deleteComment(Request $request, News $berita, $idComment){

        

    }

}
