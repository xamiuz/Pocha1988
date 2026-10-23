<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentSetting extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'is_store_open',
        'closed_message',
        'is_qris_active',
        'qris_image',
        'qris_merchant_name',
        'qris_notes',
        'is_transfer_active',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'bank_2_name',
        'bank_2_account_number',
        'bank_2_account_name',
        'is_cod_active',
        'cod_notes',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_store_open' => 'boolean',
        'is_qris_active' => 'boolean',
        'is_transfer_active' => 'boolean',
        'is_cod_active' => 'boolean',
    ];

    /**
     * Get or initialize the singleton payment configuration.
     */
    public static function current(): self
    {
        $setting = static::query()->first();

        if (! $setting) {
            $setting = static::query()->create([
                'is_store_open' => true,
                'closed_message' => 'Mohon maaf, resto 1988 Pocha sedang tutup / istirahat saat ini. Pemesanan dan pembayaran sementara tidak dapat diproses.',
                'is_qris_active' => true,
                'qris_merchant_name' => '1988 POCHA SAMARINDA',
                'qris_notes' => 'Scan QRIS menggunakan BCA, Mandiri, GoPay, OVO, Dana, atau ShopeePay. Konfirmasi otomatis / unggah bukti transfer.',
                'is_transfer_active' => true,
                'bank_name' => 'Bank BCA',
                'bank_account_number' => '869-1988-1988',
                'bank_account_name' => '1988 Pocha Samarinda',
                'bank_2_name' => 'Bank Mandiri',
                'bank_2_account_number' => '148-00-1988-1988',
                'bank_2_account_name' => '1988 Pocha Samarinda',
                'is_cod_active' => true,
                'cod_notes' => 'Bayar tunai kepada kurir saat pesanan hangat tiba di lokasi Anda.',
            ]);
        }

        return $setting;
    }

    /**
     * Get URL for QRIS barcode image.
     */
    public function getQrisImageUrlAttribute(): string
    {
        if ($this->qris_image && Storage::disk('public')->exists($this->qris_image)) {
            return Storage::disk('public')->url($this->qris_image);
        }

        if ($this->qris_image && file_exists(public_path('storage/'.$this->qris_image))) {
            return asset('storage/'.$this->qris_image);
        }

        // Default dynamic dummy/placeholder QRIS if none uploaded yet
        return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=1988POCHA-SAMARINDA-QRIS-OFFICIAL';
    }
}
