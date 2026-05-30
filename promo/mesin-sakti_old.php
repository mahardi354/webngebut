<?php
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Ambil Data
    $nama        = strip_tags($_POST['nama_pengirim']);
    $wa_klien    = strip_tags($_POST['wa_pengirim']);
    $paket       = strip_tags($_POST['jenis_website']); // Akan berisi "Paket Penyelamat (1.499.000)" dst.
    $pesan_klien = strip_tags($_POST['pesan_klien']);
    
    // --- KONFIGURASI ---
    $email_tujuan  = "didikmahardi@gmail.com"; // GANTI DENGAN EMAIL BOSS
    $nomor_wa_boss = "625227272999";      // GANTIKAN NO WA BOSS (AWALI 62)
    $nama_web      = "WebNgebut";

    // --- JALUR 1: EMAIL (ARSIP DATA) ---
    $subjek = "🚀 ORDER BARU: $nama ($paket)";
    $isi_email = "Ada orderan baru masuk, Boss!\n\n";
    $isi_email .= "Nama: $nama\n";
    $isi_email .= "WhatsApp: $wa_klien\n";
    $isi_email .= "Paket: $paket\n";
    $isi_email .= "Kebutuhan: $pesan_klien\n";
    $headers = "From: webngebut@mahardi.com"; // Email domain hosting Boss

    mail($email_tujuan, $subjek, $isi_email, $headers);

    // --- JALUR 2: WHATSAPP (CLOSING) ---
    $nama_pendek = explode(' ', trim($nama))[0];
    
    // Kalimat Profesional dengan Harga Cantik
    $kalimat_wa = "Halo WebNgebut, saya *{$nama_pendek}*, sudah isi form di web dan mau pesan *{$paket}* untuk usaha *{$pesan_klien}* saya.";
    
    $url_wa = "https://api.whatsapp.com/send?phone={$nomor_wa_boss}&text=" . urlencode($kalimat_wa);

    header("Location: $url_wa");
    exit;
}
?>