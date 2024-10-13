<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjual extends Model
{
    use HasFactory;

    protected $table = 'detail_penjual';
    protected $primaryKey = 'id_detail_penjual';

    public function user()
    {
        return $this->hasOne(User::class, 'id_user', 'user_id');
    }

}
