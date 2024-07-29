<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{

    public function getAgendas()
    {

        $agenda = Agenda::query()->get();

        return response()->json([
            "agenda" => $agenda,
        ]);
    }

    public function getAgenda($slug)
    {

        $agenda = Agenda::query()->where("slug", $slug)->firstOrFail();

        return response()->json([
            "agenda" => $agenda
        ]);
    }


    public function addAgenda(Request $request)
    {

        $request->validate([
            "judul" => "required",
            "slug" => "required|unique:agendas,slug",
            "status" => "required",
            "tanggal_mulai" => "required",
            "tanggal_selesai" => "required",
            "tipe_acara" => "required",
            "isi_agenda" => "required"
        ]);

        $request->validate([
            "gambar" => "mimes:png,jpg,jpeg|max:4096"
        ]);

        $gambar = $request->file("gambar");
        $gambarName = time() . "_" . "agenda" . "_" . $gambar->hashName();
        $gambar->storeAs("public/agenda", $gambarName);


        $data = Agenda::query()->create([
            "judul" => $request->judul,
            "slug" => $request->slug,
            "status" => $request->status,
            "tanggal_mulai" => $request->tanggal_mulai,
            "tanggal_selesai" => $request->tanggal_selesai,
            "tipe_acara" => $request->tipe_acara,
            "isi_agenda" => $request->isi_agenda,
            "foto" => is_null($gambarName) ? null : "storage/agenda/" . $gambarName,
        ]);

        return response()->json([
            "agenda" => $data,
            "status" => "Data agenda berhasil ditambah"
        ]);
    }

    public function updateAgenda(Request $request, $slug)
    {

        $agenda = Agenda::query()->where('slug', $slug)->first();

        if(is_null($agenda)){
            return response()->json([
                "message" => "Agenda tidak ditemukan"
            ], 404);
        }

        $rules = [
            "judul" => "required",
            "status" => "required",
            "tanggal_mulai" => "required",
            "tanggal_selesai" => "required",
            "tipe_acara" => "required",
            "isi_agenda" => "required"
        ];

        if ($request->slug != $agenda->slug) {
            $rules['slug'] = "required|unique:agendas,status";
        }

        $request->validate($rules);

        $gambarName = null;
        if ($request->hasFile("gambar")) {

            if (!is_null($agenda->foto)) {
                Storage::disk('public')->delete(substr($agenda->foto, 8));
            }

            $request->validate([
                "gambar" => "mimes:png,jpg,jpeg|max:4096"
            ]);

            $gambar = $request->file("gambar");
            $gambarName = time() . "_" . "agenda" . "_" . $gambar->hashName();
            $gambar->storeAs("public/agenda", $gambarName);

            $agenda->foto = "storage/agenda/" . $gambarName;
        }

        $agenda->judul = $request->judul;
        $agenda->slug = $request->slug;
        $agenda->status = $request->status;
        $agenda->tanggal_mulai = $request->tanggal_mulai;
        $agenda->tanggal_selesai = $request->tanggal_selesai;
        $agenda->tipe_acara = $request->tipe_acara;
        $agenda->isi_agenda = $request->isi_agenda;

        $agenda->save();

        return response()->json([
            "agenda" => $agenda,
            "status" => "Agenda berhasil diubah"
        ]);
    }

    public function deleteAgenda($slug)
    {

        $agenda = Agenda::query()->where("slug", $slug)->first();

        if(is_null($agenda)){
            return response()->json([
                "message" => "Agenda tidak ditemukan"
            ], 404);
        }

        if (!is_null($agenda->foto)) {
            Storage::disk('public')->delete(substr($agenda->foto, 8));
        }

        $isSuccess = $agenda->delete();

        return response()->json([
            "status" => $isSuccess
        ]);
    }
}
