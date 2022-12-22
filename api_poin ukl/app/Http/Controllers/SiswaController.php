<?php

namespace App\Http\Controllers;

use App\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index($limit = 10, $offset = 0)
    {
        $data["count"] = Siswa::count();
        $siswa=array();

        foreach (Siswa::take($limit)->skip($offset)->get() as $p ){
            $item =[
                "id" => $p->id,
                "nis" => $p->nis,
                "nama_siswa" =>$p->nama_siswa,
                "kelas" =>$p->kelas,
                "create_at" => now(),
                "update_at" => now(),

            ];

            array_push($siswa, $item);
        }

        $data["siswa"]=$siswa;
        $data["status"]=1;
        return response($data);
    }

    public function store(request $request)
    {
        $siswa = new Siswa([
            'nis'           =>$request->nis,
            'nama_siswa'    => $request->nama_siswa,
            'kelas'         =>$request->kelas,
        ]);

        $siswa->save();
        return response($siswa);
    }

    public function show($id)
    {
        $siswa = Siswa::where('id', $id)->get();

        $dataSiswa = array();
        foreach($siswa as $p){
            $item = [
                "id"           => $p->id,
                "nis"          => $p->nis,
                "nama_siswa"   => $p->nama_siswa,
                "kelas"        => $p->kelas,
                "poin"         => $p->poins,
            ];

            array_push ($siswa, $item);
        }

        $data["dataSiswa"] = $dataSiswa;
        $data["status"]= 1;
        return response($data);
    }

    public function update( $id, Request $request)
    {
        // try {
            $data = Siswa::where('id', $id)->first();
            $data->nis            = $request->input('nis');
            $data->nama_siswa     = $request->input('nama_siswa');
            $data->kelas          = $request->input('kelas');
            $data->save();
            return response()->json([
                'status' =>'1',
                'message' =>'data berhasil di ubah'
            ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => '0',
        //         'message' =>'Data  gagal diubah'
        //     ]);
        // }
    }

    public function destroy($id)
    {
        $siswa = Siswa::where('id', $id)->first();
        $siswa->delete();
        return response()->json([
        'status'  => '1',
        'message' => 'Delete Data Berhasil'
      ]);
    }
}
