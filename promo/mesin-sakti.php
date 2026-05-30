<?php
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Ambil Data
    $nama        = strip_tags($_POST['nama_pengirim']);
    $wa_klien    = strip_tags($_POST['wa_pengirim']);
    $paket       = strip_tags($_POST['jenis_website']); 
    $pesan_klien = strip_tags($_POST['pesan_klien']);
    
    // --- KONFIGURASI UTAMA ---
    $api_key_brevo = "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"; // <--- Masukkan API Key Brevo Boss
	
    $email_tujuan  = "team@webngebut.com"; 
    $email_pengirim = "team@webngebut.com"; // Email yang sudah terverifikasi di Brevo
    $nomor_wa_boss = "6281558199745";      
    $nama_web      = "WebNgebut";

    // --- JALUR 1: EMAIL VIA BREVO API (ANTI-SPOOFING) ---
    $subjek = "🚀 ORDER BARU: $nama ($paket)";
    $isi_email = "Ada orderan baru masuk, Boss!\n\n"
               . "Nama: $nama\n"
               . "WhatsApp: $wa_klien\n"
               . "Paket: $paket\n"
               . "Kebutuhan: $pesan_klien\n";

    // Kirim via Brevo API
    $data = array(
        "sender" => array("name" => "WebNgebut System", "email" => $email_pengirim),
        "to" => array(array("email" => $email_tujuan, "name" => "Boss WebNgebut")),
        "subject" => $subjek,
        "textContent" => $isi_email,
        "replyTo" => array("email" => $email_pengirim) // Bisa diganti email klien jika perlu
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.brevo.com/v3/smtp/email');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Api-Key: ' . $api_key_brevo;
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close($ch);

    // --- JALUR 2: WHATSAPP (CLOSING) ---
    $nama_pendek = explode(' ', trim($nama))[0];
    
    // Kalimat Profesional
    $kalimat_wa = "Halo WebNgebut, saya *{$nama_pendek}*, sudah isi form di web dan mau pesan *{$paket}* untuk usaha *{$pesan_klien}* saya.";
    
    $url_wa = "https://api.whatsapp.com/send?phone={$nomor_wa_boss}&text=" . urlencode($kalimat_wa);

    header("Location: $url_wa");
    exit;
}
?>