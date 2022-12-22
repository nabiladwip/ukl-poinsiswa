<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pelanggaran;
use App\Siswa;
use App\User;
use App\Poin_Siswa;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data["jumlah siswa"] = Siswa::count();
        $data["jumlah petugas"] = User::count();
        $data["jumlah data pelanggaran"] = Pelanggaran::count();
        $data["Jumlah Pelanggaran hari ini"] = Poin_Siswa::count();

        return response($data);
    }
}
