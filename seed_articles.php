<?php
require_once __DIR__ . '/bootstrap.php';
$pdo = $baglanti;

$articles = [
    [
        'dergi_tanim' => 9,
        'makale_baslik' => 'Edebiyat ve Modernizm Üzerine Bir İnceleme',
        'makale_yazar' => 'Dr. Ahmet Yılmaz',
        'makale_ozet' => 'Bu çalışma, 20. yüzyıl edebiyatında modernizm akımının etkilerini ve Türk edebiyatındaki yansımalarını derinlemesine incelemektedir.',
        'anahtar_kelime' => 'Edebiyat, Modernizm, Türk Edebiyatı, İnceleme',
        'makale_turu' => 2,
        'onay' => 1
    ],
    [
        'dergi_tanim' => 9,
        'makale_baslik' => 'Dijital Çağda Okuma Kültürü',
        'makale_yazar' => 'Doç. Dr. Ayşe Kaya',
        'makale_ozet' => 'Teknolojinin gelişmesiyle birlikte okuma alışkanlıklarındaki değişimler ve dijital platformların edebiyat üzerindeki etkisi tartışılmaktadır.',
        'anahtar_kelime' => 'Dijital Kültür, Okuma Alışkanlıkları, Teknoloji, Toplum',
        'makale_turu' => 2,
        'onay' => 1
    ]
];

foreach ($articles as $art) {
    $sql = "INSERT INTO online_makale (dergi_tanim, makale_baslik, makale_yazar, makale_ozet, anahtar_kelime, makale_turu, is_approved) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $art['dergi_tanim'],
        $art['makale_baslik'],
        $art['makale_yazar'],
        $art['makale_ozet'],
        $art['anahtar_kelime'],
        $art['makale_turu'],
        $art['onay'] // This is the value from the array, column is is_approved
    ]);
}

echo "Sample articles added for issue 9.\n";
