<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\UserMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{

    public function storeDocument(Request $request){
        
        $id_user = $request->user()->id;
        $user_magang = UserMagang::query()->where('id_user', $id_user)->first();

        $request->validate([
            "cv" => "required|mimes:pdf|max:4096",
            "transkip_nilai" => "required|mimes:pdf|max:4096",
            "surat_rekomendasi" => "required|mimes:pdf|max:4096",
            "ktp" => "required|mimes:jpg,jpeg,png|max:2048",
        ]);

        // cv
        $cv = $request->file('cv');
        $cvName = time() . "_" . "cv" . "_" . $cv->hashName();
        $cv->storeAs('public', $cvName);
        
        // transkip nilai
        $transkipNilai = $request->file('transkip_nilai');
        $transkipNilaiName = time() . "_" . "transkipNilai" . "_" . $transkipNilai->hashName();
        $transkipNilai->storeAs('public', $transkipNilaiName);

        // surat rekomendasi
        $suratRekomendasi = $request->file('surat_rekomendasi');
        $suratRekomendasiName = time() . "_" . "suratRekomendasi" . "_" . $suratRekomendasi->hashName();
        $suratRekomendasi->storeAs('public', $suratRekomendasiName);

        // ktp
        $ktp = $request->file('ktp');
        $ktpName = time() . "_" . "ktp" . "_" . $ktp->hashName();
        $ktp->storeAs('public', $ktpName);


        // sertifikat
        $sertifikatName = null;
        if($request->hasFile('sertifikat')){
            $sertifikat = $request->file('sertifikat');
            $sertifikatName = time() . "_" . "sertifikat" . "_" . $sertifikat->hashName();
            $sertifikat->storeAs('public', $sertifikatName);
        }
        
        // dokumen tambahan
        $dokumen_tambahanName = null;
        if($request->hasFile('dokumen_tambahan')){
            $dokumen_tambahan = $request->file('dokumen_tambahan');
            $dokumen_tambahanName = time() . "_" . "dokumen_tambahan" . "_" . $dokumen_tambahan->hashName();
            $dokumen_tambahan->storeAs('public', $dokumen_tambahanName);
        }

        if(!is_null($sertifikatName)){
            $sertifikatName = 'storage/' . $sertifikatName;
        }
        if(!is_null($dokumen_tambahanName)){
            $dokumen_tambahanName = 'storage/' . $dokumen_tambahanName;
        }

        $data = Dokumen::query()->create([
            'cv' => 'storage/' . $cvName,
            'transkip_nilai' => 'storage/' . $transkipNilaiName,
            'id_user_magang' => $user_magang->id,
            'surat_rekomendasi' => 'storage/' . $suratRekomendasiName,
            'ktp' => 'storage/' . $ktpName,
            'sertifikat' => $sertifikatName,
            'dokumen_tambahan' => $dokumen_tambahanName,
        ]);

        return response()->json([
            'data' => $data 
        ]);

    }

    public function getDocument(Request $request){

        $id_user = $request->user()->id;
        $user_magang = UserMagang::query()->where('id_user', $id_user)->first();

        $document = Dokumen::query()->where('id_user_magang', $user_magang->id)->first();

        return response()->json([
            'data' => $document,
        ]);

    }

    public function deleteAllDocuments(Request $request){

        $id_user = $request->user()->id;
        $user_magang = UserMagang::query()->where('id_user', $id_user)->first();

        // $document = Dokumen::where('id_user_magang', $user_magang->id)->first();

        $document = Dokumen::query()->where('id_user_magang', $user_magang->id)->first();

        $deleted = Storage::disk('public')->delete([
            substr($document->cv, 8),
            substr($document->transkip_nilai, 8),
            substr($document->surat_rekomendasi, 8),
            substr($document->ktp, 8),
        ]);        

        if(!is_null($document->sertifikat)){
            Storage::disk('public')->delete(substr($document->sertifikat, 8));
        }
        if(!is_null($document->dokumen_tambahan)){
            Storage::disk('public')->delete(substr($document->dokumen_tambahan, 8));
        }

        $document->delete();

        return response()->json([
            'message' => "Berhasil menghapus dokumen"
        ]);


    }


}
