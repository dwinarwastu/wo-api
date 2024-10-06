<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKatalog extends Model
{
    use HasFactory;
    protected $table = 'detail_katalog';
    protected $primaryKey = 'id_detail_katalog';
    protected $fillable = [
        'katalog_id',
        'judul_variasi',
        'harga',
        'gambar',
    ];
}
