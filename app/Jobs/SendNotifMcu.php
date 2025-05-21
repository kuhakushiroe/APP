<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotifMcu implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pesanText;
    protected $nomorList;
    protected $token;
    protected $namaUser;

    public function __construct(string $pesanText, array $nomorList, string $token, string $namaUser)
    {
        $this->pesanText = $pesanText;
        $this->nomorList = $nomorList;
        $this->token = $token;
        $this->namaUser = $namaUser;
    }

    public function handle()
    {
        $waktu = now()->format('d-m-Y H:i:s') . ' ' . $this->getZonaWaktuLabel();

        $fullPesan = $this->pesanText . "\n\n👤 Oleh: *{$this->namaUser}*\n⏰ Waktu: *{$waktu}*";

        foreach ($this->nomorList as $nohp) {
            // Panggil fungsi pesan, token sudah dipastikan valid
            $result = pesan($nohp, $fullPesan, $this->token);

            // Optional: Log hasil kirim pesan
            //Log::info("Kirim pesan ke $nohp: " . json_encode($result));
        }
    }

    private function getZonaWaktuLabel(): string
    {
        return match (config('app.timezone')) {
            'Asia/Jakarta' => 'WIB',
            'Asia/Makassar' => 'WITA',
            'Asia/Jayapura' => 'WIT',
            default => '',
        };
    }
}
