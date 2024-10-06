<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Katalog extends Model
{
    use HasFactory;

    protected $table = 'katalog';
    protected $primaryKey = 'id_katalog';
    protected $fillable = [
        'id_katalog',
        'detail_penjual_id',
        'judul',
        'deskripsi',
        'metode_bayar',
    ];

    public function detailKatalog()
    {
        return $this->belongsToMany(DetailKatalog::class, 'katalog', 'id_katalog', 'id_katalog', 'id_katalog', 'id_detail_katalog');
    }
}
