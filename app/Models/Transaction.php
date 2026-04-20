<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi', 'customer_id', 'service_id', 'berat', 'tanggal_masuk',
        'tanggal_ambil', 'sub_total', 'diskon', 'total', 'status',
        'jenis_pembayaran', 'status_bayar'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_ambil' => 'date',
        'berat' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // HAPUS detailTransaksi karena tidak ada tabel detail_transaksi
    // Ini laundry per service, bukan multiple item

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'selesai' => 'badge-success-solid',
            'proses' => 'badge-warning-solid',
            'baru' => 'badge-info-solid',
            default => 'badge-secondary'
        };
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($transaction) {
            $last = self::latest('id')->first();
            $number = $last ? (int) substr($last->kode_transaksi, -4) + 1 : 1;
            $transaction->kode_transaksi = 'TRX-' . date('Ymd') . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }
}