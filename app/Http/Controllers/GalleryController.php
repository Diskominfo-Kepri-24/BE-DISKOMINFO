<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    
    public function getImages(){

        $galleries = Gallery::query()->get();

        return response()->json([
            "images" => $galleries
        ]);

    }

    public function getImageById(Gallery $gallery){

        return response()->json([
            "gallery" => $gallery
        ]);

    }

    public function storeImage(Request $request){
        
        $request->validate([
            "image" => "required|mimes:png,jpg,jpeg|max:4096",
            "title" => "required"
        ]);

        $image = $request->file('image');
        $imageName = time() . "_" . "gallery" . "_" . $image->hashName();
        $image->storeAs("public/gallery", $imageName);

        $gallery = Gallery::query()->create([
            "title" => $request->title,
            "image" => "storage/gallery/" . $imageName,
        ]);

        return response()->json([
            "gallery" => $gallery,
            "status" => "Berhasil ditambahkan"
        ]);

    }

    public function updateImage(Request $request, Gallery $gallery){

        $request->validate([
            "image" => "required|mimes:png,jpg,jpeg|max:4096",
            "title" => "required"
        ]);

        Storage::disk('public')->delete(substr($gallery->image, 8));

        $image = $request->file('image');
        $imageName = time() . "_" . "gallery" . "_" . $image->hashName();
        $image->storeAs("public/gallery", $imageName);

        $gallery->title = $request->title;
        $gallery->image = "storage/gallery/" . $imageName;
        $gallery->save();

        return response()->json([
            "data" => $gallery,
            "status" => "Data gallery berhasil diubah"
        ]);
        
    }

    public function deleteImage(Gallery $gallery){

        if(is_null($gallery)){
            return response()->json([
                "data" => "Data tidak ditemukan"
            ], 404);
        }

        Storage::disk('public')->delete(substr($gallery->image, 8));

        $isSuccess = $gallery->delete();

        return response()->json([
            "status" => $isSuccess
        ]);

    }

    

}
