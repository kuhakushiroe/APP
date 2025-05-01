<?php

if (!function_exists('pesan')) {
    function pesan($nohp, $pesan, $token)
    {
        $tokenValid = env('PESAN_TOKEN', 'abc25qc'); // Simpan di .env kalau mau

        if (empty($nohp)) {
            $nohp = '088212543694';
        }

        if ($token !== $tokenValid) {
            return ['success' => false, 'message' => 'Token salah'];
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://pesan.buanatechno.id/send-message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => [
                'message' => $pesan,
                'number' => $nohp,
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return ['success' => false, 'message' => $err];
        }

        return ['success' => true, 'response' => $response];
    }
}
