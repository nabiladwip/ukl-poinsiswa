<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table ='pelanggaran';
    protected $fillable=[
        'nama_pelanggaran',
        'kategori',
        'poin',
    ];
    public function poin_siswa(){
        return $this->hasMany('App\Poin_Siswa','id_pelanggaran', 'id');
    }
}
