<?php

/**
 * Kullanıcıya özel bilgileri alarak e-imza üretir.
 * * @param string $veri İmzalanacak metin
 * @param string $adSoyad Kullanıcının Adı Soyadı (Common Name)
 * @param string $email Kullanıcının E-postası
 * @return array İmza bloğu ve teknik detaylar
 */
function kisiselEimzaOlustur($veri, $adSoyad, $email) {
    
    $configPath = __DIR__ . '/openssl.cnf'; // Sabit ayar dosyamız

    // 1. KULLANICIYA ÖZEL KİMLİK BİLGİLERİ (Distinguished Name)
    // Dosyayı editlemek yerine bu diziyi kullanıyoruz.
    $dn = array(
        "countryName" => "TR",
        "stateOrProvinceName" => "Istanbul",
        "localityName" => "Merkez",
        "organizationName" => "Sirket Adi A.S.",
        "organizationalUnitName" => "IT Departmani",
        "commonName" => $adSoyad, // <-- Dinamik Kullanıcı Adı Buraya Geliyor
        "emailAddress" => $email   // <-- Dinamik Email Buraya Geliyor
    );

    // 2. OpenSSL Ayarları
    $config = array(
        "digest_alg" => "sha256",
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
        "config" => $configPath
    );

    // 3. Private Key Oluştur
    $privKeyRes = openssl_pkey_new($config);
    if ($privKeyRes === false) die("Key Hatasi: " . openssl_error_string());

    // 4. Bu Key'i Kullanıcı Bilgileriyle Eşleştir (CSR Oluşturma)
    // Bu adım, anahtarın "Kime ait olduğunu" matematiksel olarak hazırlar.
    $csr = openssl_csr_new($dn, $privKeyRes, $config);

    // Private Key'i dışarı aktar
    openssl_pkey_export($privKeyRes, $privateKey, null, $config);

    // 5. Veriyi İmzala (Signature Oluşturma)
    // İmza, kullanıcının Private Key'i ile oluşturulur.
    $binarySignature = "";
    openssl_sign($veri, $binarySignature, $privateKey, OPENSSL_ALGO_SHA256);

    // 6. Formatlama (Base64 + 4 Satır)
    $base64Signature = base64_encode($binarySignature);
    $satirUzunlugu = ceil(strlen($base64Signature) / 4);
    if ($satirUzunlugu < 1) $satirUzunlugu = 1; // Güvenlik önlemi
    
    $parcalar = str_split($base64Signature, $satirUzunlugu);

    $cikti = "-----BEGIN USER SIGNATURE-----\n";
    foreach ($parcalar as $satir) {
        $cikti .= $satir . "\n";
    }
    $cikti .= "-----END USER SIGNATURE-----";

    return [
        'kullanici' => "$adSoyad ($email)",
        'imza_blogu' => $cikti,
        'public_key' => openssl_pkey_get_details($privKeyRes)['key'], // Doğrulama için saklanmalı
        'private_key' => $privateKey // (DİKKAT: Bunu kullanıcıya güvenli vermelisiniz)
    ];
}

// --- KULLANIM ÖRNEĞİ ---

$kullaniciAdi = "osman selvi";
$kullaniciMail = "osselvi@gmail.com";
$imzalanacakMetin = "Bu sözleşmeyi okudum ve onaylıyorum. Tarih: " . date("d.m.Y");

// Fonksiyonu çağır
$sonuc = kisiselEimzaOlustur($imzalanacakMetin, $kullaniciAdi, $kullaniciMail);

// Ekrana Bas
echo "<h3>İmza Sahibi: " . $sonuc['kullanici'] . "</h3>";
echo "<b>Oluşturulan Benzersiz E-İmza:</b><br>";
echo "<pre>" . $sonuc['imza_blogu'] . "</pre>";

// Veritabanına ne kaydedeceksiniz?
// 1. $sonuc['imza_blogu'] -> Belgenin altına
// 2. $sonuc['public_key'] -> Kullanıcılar tablosuna (Doğrulama için)
?>