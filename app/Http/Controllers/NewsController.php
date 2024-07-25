<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use App\Models\UserMagang;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{

    public function storeBerita(Request $request)
    {

        $id_user = $request->user()->id;

        $request->validate([
            "tanggal" => "date|required",
            "slug" => "required|unique:news,slug",
            "judul" => "required",
            "isi_berita" => "required",
            "kategori" => "required",
        ]);

        $gambarName = null;
        if ($request->hasFile("gambar")) {

            $request->validate([
                "gambar" => "mimes:png,jpg,jpeg|max:4096"
            ]);

            $gambar = $request->file('gambar');
            $gambarName = time() . "_" . "gambar" . "_" . $gambar->hashName();
            $gambar->storeAs("public/berita", $gambarName);
        }

        $data = News::query()->create([
            "tanggal" => $request->tanggal,
            "slug" => $request->slug,
            "judul" => $request->judul,
            "gambar" => is_null($gambarName) ? null : "storage/berita/" . $gambarName,
            "isi_berita" => $request->isi_berita,
            "kategori" => $request->kategori,
            "id_user" => $id_user,
        ]);

        return response()->json([
            "data" => $data
        ]);
    }

    public function searchBerita(Request $request)
    {

        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $berita = News::query()->orderBy('tanggal', 'desc');

        $berita = $berita->where(function (Builder $builder) use ($request) {

            $judul = $request->input('judul');
            if ($judul) {
                $builder->where('judul', 'like', '%' . $judul . '%');
            }

            $kategori = $request->input('kategori');
            if ($kategori) {
                $builder->where('kategori', 'like', '%' . $kategori . '%');
            }
        });

        $berita = $berita->paginate(perPage: $size, page: $page);

        return response()->json([
            'data' => $berita,
        ]);
    }


    public function getBeritaBySlug(Request $request, News $berita)
    {
        if(!$berita){
            return response()->json([
                'message' => "Not Found"
            ], 404);
        }

        // ambil comments berdasarkan slug
        $comments = Comment::join('news', 'news.id', '=', 'comments.id_berita')
                        ->join('users', 'users.id', '=', 'comments.id_user')
                        ->select('comments.*', 'news.judul as berita_judul', 'users.name as user_name')
                        ->where('news.id', $berita->id)
                        ->get();

        return response()->json([
            'data' => $berita,
            "komentar" => $comments
        ]);

    }

    public function updateBerita(Request $request, News $berita)
    {

        $id_user = $request->user()->id;

        $rules = [
            "tanggal" => "date|required",
            "judul" => "required",
            "isi_berita" => "required",
            "kategori" => "required",
        ];

        if($request->slug != $berita->slug){
            $rules['slug'] = "required|unique:news,slug";
        }

        $request->validate($rules);

        if(is_null($berita)){
            return response()->json([
                "data" => "Data tidak ditemukan"
            ], 404);
        }

        $gambarName = null;
        if ($request->hasFile("gambar")) {

            if(!is_null($berita->gambar)){
                Storage::disk('public')->delete(substr($berita->gambar, 8));
            }

            $request->validate([
                "gambar" => "mimes:png,jpg,jpeg|max:4096"
            ]);

            $gambar = $request->file('gambar');
            $gambarName = time() . "_" . "gambar" . "_" . $gambar->hashName();
            $gambar->storeAs("public/berita", $gambarName);

            $berita->gambar = "storage/berita/" . $gambarName;
        }

        $berita->judul = $request->judul;
        $berita->tanggal = $request->tanggal;
        $berita->slug = $request->slug;
        $berita->isi_berita = $request->isi_berita;
        $berita->id_user = $id_user;

        $berita->save();

        return response()->json([
            "data" => $berita,
            "status" => "Berita Berhasil diubah"
        ]);
    }

    public function deleteBerita(Request $request, News $berita){

        if(is_null($berita)){
            return response()->json([
                "data" => "Data tidak ditemukan"
            ], 404);
        }

        if(!is_null($berita->gambar)){

            Storage::disk('public')->delete(substr($berita->gambar, 8));

        }

        
        $isSuccess = $berita->delete();

        return response()->json([
            "status" => $isSuccess
        ]);

    }

}
