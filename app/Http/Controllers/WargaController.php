<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Warga;
use App\Http\Resources\UserResource;
use App\Http\Resources\WargaResource;
use App\Http\Resources\KesejahteraanResource;

class WargaController extends Controller
{
    public function show () {
        $user = Auth::user()->load('warga');

        return new UserResource($user);
    }

    public function updateProfile (Request $request) {
        $user = Auth::user()->load('warga');

        // insert new data from request, if null then insert existing one in db
        $user->username = $request->input('username', $user->username);
        $user->nama     = $request->input('nama', $user->nama);
        $user->nik  = $request->input('nik', $user->nik);
        $user->kecamatan = $request->input('kecamatan', $user->kecamatan);
        $user->kelurahan = $request->input('kelurahan', $user->kelurahan);
        $user->rw = $request->input('rw', $user->rw);
        $user->rt = $request->input('rt', $user->rt);

        $user->save();

        $warga = $user->warga;

        $user->warga()->update([
            'no_kk' => $request->input('no_kk', $warga->no_kk),
            'jenis_kelamin' => $request->input('jenis_kelamin', $warga->jenis_kelamin),
            'tanggal_lahir' => $request->input('tanggal_lahir', $warga->tanggal_lahir),
            'alamat' => $request->input('alamat', $warga->alamat),
            'no_hp' => $request->input('no_hp', $warga->no_hp),
            'flag_hamil'=> $request->input('flag_hamil', $warga->flag_hamil),
            'flag_paru' => $request->input('flag_paru', $warga->flag_paru),
            'flag_jantung' => $request->input('flag_jantung', $warga->flag_jantung),
            'flag_autoimun' => $request->input('flag_autoimun', $warga->flag_autoimun),
            'flag_diabet' => $request->input('flag_diabet', $warga->flag_diabet),
            'flag_ginjal' => $request->input('flag_ginjal', $warga->flag_ginjal),
            'flag_liver' => $request->input('flag_liver', $warga->flag_liver),
            'flag_hipertensi' => $request->input('flag_hipertensi', $warga->flag_hipertensi),
            'flag_perokok' => $request->input('flag_perokok', $warga->flag_perokok)
        ]);

        return response()->json([
            'message' => 'data warga berhasil di update',
            'data' => new UserResource($user->load('warga'))
        ]);
    }

    public function kondisiKesejahteraan (Request $request) {
        $user = Auth::user()->load('warga.kesejahteraan');

        $user->warga->kesejahteraan()->updateOrCreate(
            [
                'warga_id' => $user->warga->id
            ]
            ,
            [
            'penghasilan' => $request->input('penghasilan'),
            'flag_phk' => $request->input('flag_phk'),
            'flag_usaha' => $request->input('flag_usaha')
            ]
        );

        $user->refresh();

        $kesejahteraan = $user->warga->kesejahteraan;

        return response()->json([
            'message' => 'berhasil menginput data kesejahteraan',
            'data' => new KesejahteraanResource($kesejahteraan)
        ]);
    }
}
