
<?php

function eImzaOlustur($veri) {
    // 1. Oluşturduğumuz ayar dosyasının yolunu tam olarak belirleyelim
    // __DIR__ : Şu anki PHP dosyasının bulunduğu klasördür.
    $configPath = __DIR__ . '/openssl.cnf';

    // Dosya orada mı diye son bir kontrol yapalım
    if (!file_exists($configPath)) {
        die("Hata: openssl.cnf dosyası bulunamadı! Lütfen imza.php ile aynı klasöre openssl.cnf dosyasını oluşturun.");
    }

    $config = array(
        "digest_alg" => "sha256",
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
        "config" => $configPath // <-- Kritik ayar burası
    );
    
    // Anahtarları üret
    $res = openssl_pkey_new($config);

    if ($res === false) {
        // Hata hala varsa sebebini tam olarak görelim
        die("OpenSSL Hatası: " . openssl_error_string());
    }

    // Private Key'i dışarı aktarırken de config dosyasını veriyoruz
    openssl_pkey_export($res, $privateKey, null, $config);
    
    // Public Key detaylarını al
    $publicKeyDetay = openssl_pkey_get_details($res);
    // Eğer $res başarılıysa $publicKeyDetay da dolu gelir, ama kontrol edelim
    if (!$publicKeyDetay) {
         die("Public Key detayları alınamadı.");
    }

    // 2. Veriyi İmzala
    $binarySignature = "";
    openssl_sign($veri, $binarySignature, $privateKey, OPENSSL_ALGO_SHA256);

    // 3. Base64'e çevir ve 4 satıra böl
    $base64Signature = base64_encode($binarySignature);
    
    // Base64 veriyi 4 eşit parçaya bölmek için hesaplama
    $uzunluk = strlen($base64Signature);
    $satirUzunlugu = ceil($uzunluk / 4);
    
    // Eğer veri çok kısaysa (hata durumunda) str_split hata verebilir, kontrol:
    if ($satirUzunlugu < 1) $satirUzunlugu = 1;

    $parcalar = str_split($base64Signature, $satirUzunlugu);

    $cikti = "-----BEGIN USER SIGNATURE-----\n";
    // Parçaları birleştir
    foreach ($parcalar as $satir) {
        $cikti .= $satir . "\n";
    }
    $cikti .= "-----END USER SIGNATURE-----";

    return [
        'imza_blogu' => $cikti,
        'ham_byte_boyutu' => strlen($binarySignature)
    ];
}

// --- TEST KISMI ---

$kullaniciVerisi = "Bu belge onaylanmistir.";
$sonuc = eImzaOlustur($kullaniciVerisi);

echo "<pre>" . $sonuc['imza_blogu'] . "</pre>";
echo "<hr>Ham Boyut: " . $sonuc['ham_byte_boyutu'];

?>