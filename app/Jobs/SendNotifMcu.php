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

    public $pesanText;
    public $nomors;
    public $token;
    /**
     * Create a new job instance.
     */
    public function __construct($pesanText, $nomors, $token)
    {
        $this->pesanText = $pesanText;
        $this->nomors = $nomors;
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        foreach ($this->nomors as $nomor) {
            pesan($nomor, $this->pesanText, $this->token);
        }
    }
}
