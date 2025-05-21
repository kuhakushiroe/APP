<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class QueueController extends Controller
{
    //
    public function runQueueOnce($token = null)
    {
        // ✅ Token keamanan sederhana
        if ($token !== 'rahasia123') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Jalankan queue:work --once
        Artisan::call('queue:work', [
            '--once' => true,
            '--sleep' => 3,
            '--tries' => 3,
            '--timeout' => 30,
        ]);

        // Ambil output Artisan (opsional)
        $output = Artisan::output();

        return response()->json([
            'message' => 'Queue job executed.',
            'output' => $output
        ]);
    }
}
