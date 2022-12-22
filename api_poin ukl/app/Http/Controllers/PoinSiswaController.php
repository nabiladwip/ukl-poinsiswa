<?php

namespace App\Http\Controllers;


use DB;
use App\Poin_Siswa;
use App\Siswa;
use App\Pelanggaran;
use Illuminate\Http\Request;

class PoinSiswaController extends Controller
{
    public function index($limit = 10, $offset=0)
    {
        $data["count"]= Poin_Siswa::count();
        $poin = array();
        $dataPoin = DB::table('poin_siswa')
        ->join('siswa', 'siswa.id','=','poin_siswa.id_siswa')
        ->join('pelanggaran', 'pelanggaran.id','=','poin_siswa.id_pelanggaran')
        ->select('poin_siswa.id', 'siswa.nama_siswa','siswa.kelas','siswa.nis','pelanggaran.nama_pelanggaran'
        ,'pelanggaran.kategori', 'pelanggaran.poin','poin_siswa.tanggal')->get();

        foreach($dataPoin as $p)
        {
            $item =[
                "id"             => $p->id,
                "nama_siswa"=>$p->nama_siswa,
                "kelas" => $p->kelas,
                "nis" => $p->nis,
                "nama_pelanggaran" => $p->nama_pelanggaran,
                 "kategori"         => $p->kategori,
                 "poin"     => $p->poin,
                "tanggal"        =>$p->tanggal,
               
           
            ];

            array_push($poin, $item);
        }

    $data["dataPoin"]=$dataPoin;
    $dataPoin["status"]=1;
    return response($data);
    }

    public function store(Request $request)
    {
        $poin_siswa = new Poin_siswa([
            'id_siswa' => $request->id_siswa,
            'id_pelanggaran' => $request->id_pelanggaran,
            'tanggal'=> now(),
            'keterangan'=> $request->keterangan,
        ]);

        $poin_siswa->save();
        return response($poin_siswa);
    }

    public function show($id)
    {
        $poin = Poin_Siswa::where('id', $id)->get();

        $poin_siswa= array();
        foreach($poin as $p){
            $item([
                "id" => $p->id,
                "id_siswa" => $p->id_siswa,
                "id_pelanggaran" => $p->id_pelanggaran,
                "tanggal" => $p->tanggal,
                "keterangan" => $p->keterangan,
                "poin_siswa" => $p->pelanggarans->poin,
                "kategori" =>$p->pelanggarans->kategori,
            ]);

            array_push($poin_siswa, $item);
        }

        $data["poinSiswa"]= $poin_siswa;
        $data["status"]=1;
        return response($data);
    }

    public function detail($id)
    {
        $poin = Poin_Siswa::where('id_siswa', $id)->get();
        $poin_siswa = array();
        $total=0;
        foreach ($poin as $p) {
            $item = [
                "id"              => $p->id,
                "id_siswa"        => $p->id_siswa,
                "id_pelanggaran"  => $p->id_pelanggaran,
                "tanggal"         => $p->tanggal,
                "keterangan"      => $p->keterangan,
                "poin_siswa"      => $p->pelanggarans->poin,
                "kategori"        => $p->pelanggarans->kategori,
            ];
            array_push($poin_siswa, $item);
        }
        $data["total"]=$total;
        $data["poinSiswa"] = $poin_siswa;
        $data["status"] = 1;
        return response($data);
    }

    public function update($id, Request $request)
    {
        $poin = Poin_Siswa::where('id', $id)->first();

        $poin->id_siswa         = $request->id_siswa;
        $poin->id_pelanggaran   = $request->id_pelanggaran;
        $poin->keterangan       = $request->keterangan;
        $poin->updated_at       = now()->timestamp;

        $poin->save();
        
        return response($poin);
    }
    
    public function destroy($id)
    {
        $poin = Poin_Siswa::where('id', $id)->first();
        $poin->delete();
        return response()->json([
          'status'  => '1',
          'message' => 'Delete Data Berhasil'
        ]);
    }
    public function findPoin(Request $request)
    {
        $find = $request->find;
        $dataPoinSiswa = Poin_Siswa::with('pelanggarans')->whereHas('siswas', function($query) use($find){
            $query->where("nama_siswa", "like", "%$find%");});
            $detail = array();
            foreach($dataPoinSiswa->get() as $p)
            {
                $item=[
                    "tanggal" => $p->tanggal,
                    "nama_pelanggaran" => $p->pelanggarans->nama_pelanggaran,
                    "kategori" => $p->pelanggarans->kategori,
                    "poin"=> $p->pelanggarans->poin,
                ];
                
                array_push($detail, $item);
            }

            $data =$dataPoinSiswa->first();
            $nama_siswa=$data->siswas->nama_siswa;
            $nis =$data->siswas->nis;
            $kelas=$data->siswas->kelas;
            $status= 1;
            return response()->json(compact('nama_siswa', 'nis', 'kelas', 'detail'));
    }
}
