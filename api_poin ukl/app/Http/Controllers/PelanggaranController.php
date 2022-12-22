<?php

namespace App\Http\Controllers;

use App\Pelanggaran;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    public function index($limit = 10, $offset = 0)
    {
        $data["count"] = Pelanggaran::count();
        $pelanggaran = array();

        foreach (Pelanggaran::take($limit)->skip($offset)->get() as $p)
        {
            $item =[
                "id"                =>$p->id,
                "nama_pelanggaran"  =>$p->nama_pelanggaran,
                "kategori"          =>$p->kategori,
                "poin"              =>$p->poin,
            ];

            array_push($pelanggaran, $item);
        }

        $data["pelanggaran"] = $pelanggaran;
        $data["status"]=1;
        return response($data);
    }


    public function store(Request $request)
    {
        $pelanggaran = new Pelanggaran([
            'nama_pelanggaran'  => $request->nama_pelanggaran,
            'kategori'          => $request->kategori,
            'poin'              => $request ->poin,
        ]);

        $pelanggaran->save();
        return response($pelanggaran);
    }

    public function show($id)
    {
        $pelanggaran = Pelanggaran::where('id', $id)->get();

        $data_pelanggaran = array();
        foreach($pelanggaran as $p){
            $item=[
                "id"                =>$p->id,
                "nama_pelanggaran"  => $p->nama_pelanggaran,
                "kategori"          => $p->kategori,
                "poin"              => $p->poin,
            ];
            array_push($data_pelanggaran, $item);
        }

        $data["data_pelanggaran"] = $data_pelanggaran;
        $data['status'] =1;
        return response($data);
    }

    public function update( $id, Request $request)
    {
        // try {
            $data = Pelanggaran::where('id', $id)->first();
            $data->nama_pelanggaran          = $request->input('nama_pelanggaran');
            $data->kategori     = $request->input('kategori');
            $data->poin          = $request->input('poin');
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
        $pelanggaran = Pelanggaran::where('id', $id)->first();

        $pelanggaran->delete();
        return response()->json([
            'status'=>'1',
            'message'=>'Delete data Berhasil'
        ]);
    }
}
