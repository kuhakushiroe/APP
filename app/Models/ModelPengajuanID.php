<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelPengajuanID extends Model
{
    //
    protected $table = 'pengajuan_id';
    protected $fillable = [
        'id',
        'nrp',
        'jenis_pengajuan_id',
        'upload_id_lama',
        'status_upload_id_lama',
        'catatan_upload_id_lama',
        'upload_request',
        'status_upload_request',
        'catatan_upload_request',
        'upload_foto',
        'status_upload_foto',
        'catatan_upload_foto',
        'upload_ktp',
        'status_upload_ktp',
        'catatan_upload_ktp',
        'upload_skd',
        'status_upload_skd',
        'catatan_upload_skd',
        'upload_bpjs',
        'status_upload_bpjs',
        'catatan_upload_bpjs',
        'status_pengajuan',
        'tgl_pengajuan',
        'tgl_induksi',
        'exp_id',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
