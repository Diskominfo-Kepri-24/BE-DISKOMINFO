<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\UserMagang;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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


    public function getBeritaBySlug(Request $request, string $slug)
    {

        $berita = News::query()->where('slug', $slug)->first();
        if(!$berita){
            return response()->json([
                'message' => "Not Found"
            ], 404);
        }

        return response()->json([
            'data' => $berita
        ]);

    }

    public function updateBerita(Request $request, string $slug)
    {
    }
}
