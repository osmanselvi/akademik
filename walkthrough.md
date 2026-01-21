# EBP Dergi Sistemi - Kullanım Kılavuzu

## 📖 İçindekiler
1. [Sistem Genel Bakışı](#sistem-genel-bakışı)
2. [Kullanıcı Rolleri](#kullanıcı-rolleri)
3. [Kullanıcı Akışları](#kullanıcı-akışları)
4. [Admin İşlemleri](#admin-işlemleri)
5. [Sorun Giderme](#sorun-giderme)

---

## 🌐 Sistem Genel Bakışı

**Edebiyat Bilimleri Dergisi (EBP)** web platformu, akademik makale gönderimi, hakem değerlendirmesi ve dergi yayınlama süreçlerini dijital ortamda yönetmek için geliştirilmiştir.

### Ana Modüller
- 👤 **Kullanıcı Yönetimi**: Kayıt, giriş, profil
- 📝 **Makale Sistemi**: Gönderim, değerlendirme, yayınlama
- 📚 **Dergi Yayınlama**: Sayı oluşturma, içerik düzenleme
- 👥 **Kurul Yönetimi**: Editör ve hakem atama
- ⚙️ **Yönetim Paneli**: Sistem ayarları ve raporlar

---

## 👥 Kullanıcı Rolleri

### 1. Misafir Ziyaretçi
**Yetkiler**:
- ✅ Dergi sayılarını görüntüleme
- ✅ Makale başlıklarını ve özetlerini okuma
- ✅ PDF'leri indirme
- ✅ Künye bilgilerini görüntüleme
- ❌ Makale gönderimi yapamaz

**Erişim**: Direkt URL üzerinden, giriş gerektirmez

---

### 2. Kayıtlı Kullanıcı (Yazar)
**Yetkiler**:
- ✅ Tüm misafir yetkileri
- ✅ Makale gönderimi
- ✅ Gönderim takibi
- ✅ Profil düzenleme

**Kayıt Süreci**:
```
1. Ana sayfa → Kayıt Ol butonu
2. Form doldurma (ad, email, şifre, unvan, kurum)
3. Email doğrulama
4. Aktivasyon linki tıklama
5. Giriş yapma
```

⚠️ **Önemli**: Yahoo.com uzantılı email kullanmayın!

---

### 3. Hakem
**Yetkiler**:
- ✅ Tüm kayıtlı kullanıcı yetkileri
- ✅ Atanan makaleleri değerlendirme
- ✅ Hakem raporları gönderme
- ✅ Değerlendirme formlarına erişim

**Atama**: Admin tarafından yapılır

---

### 4. Editör
**Yetkiler**:
- ✅ Gönderilen makaleleri inceleme
- ✅ Hakem atama
- ✅ Makale durum güncelleme
- ✅ Dergi sayı düzenleme

**Atama**: Sistem yöneticisi tarafından kurul görevleri tablosundan

---

### 5. Sistem Yöneticisi
**Yetkiler**:
- ✅ Tüm sistem erişimi
- ✅ Kullanıcı yönetimi
- ✅ Dergi ayarları
- ✅ Kurul atamaları
- ✅ Sistem konfigürasyonu

---

## 🔄 Kullanıcı Akışları

### 1️⃣ Yeni Kullanıcı Kaydı

#### Adım 1: Kayıt Formu
```
URL: /wwwroot/kayitol.php
```

**Gerekli Bilgiler**:
- Ad Soyad
- Email adresi (Yahoo.com hariç)
- Şifre
- Akademik unvan
- Kurum bilgisi
- Telefon numarası

**Validation Kuralları**:
- Email benzersiz olmalı
- Şifre minimum 6 karakter (önerilmiyor, güncellenmeli)
- Tüm alanlar zorunlu

#### Adım 2: Email Doğrulama
- Sistem otomatik aktivasyon emaili gönderir
- Email içindeki linke tıklayın
- Hesap aktif hale gelir

#### Adım 3: İlk Giriş
```
URL: /wwwroot/login.php
```
- Email ve şifre ile giriş
- Dashboard'a yönlendirilme

---

### 2️⃣ Makale Gönderimi

#### Ön Hazırlık
Makale göndermeden önce hazırlayın:
- ✅ Tam metin PDF (Dergi şablonuna uygun)
- ✅ Türkçe ve İngilizce başlık
- ✅ Türkçe ve İngilizce özet
- ✅ Anahtar kelimeler (5-7 kelime)
- ✅ Kaynakça listesi

#### Adım 1: Giriş ve Erişim
```
Ana Sayfa → Giriş Yap → Makale Gönder
```

#### Adım 2: Makale Formu Doldurma
**Temel Bilgiler**:
- Makale başlığı (Türkçe)
- Makale başlığı (İngilizce)
- Yazar ad-soyad
- Yazar ORCID (varsa)
- Yazar kurumu

**İçerik**:
- Makale özeti (Türkçe)
- Abstract (İngilizce)
- Anahtar kelimeler (Türkçe)
- Keywords (İngilizce)
- Kaynakça

**Dosya**:
- PDF yükleme (maksimum 10MB - önerilir)
- Dosya adlandırma: `yazar_baslik_tarih.pdf`

#### Adım 3: Kontrol ve Gönderim
- Tüm bilgileri gözden geçirin
- "Gönder" butonuna tıklayın
- Onay mesajı bekleyin
- Email bildirim alacaksınız

#### Adım 4: Takip
```
Dashboard → Makalelerim → Durum
```

**Durum Kodları**:
- 🟡 **Beklemede**: Editör incelemesi bekleniyor
- 🔵 **Değerlendirmede**: Hakem ataması yapıldı
- 🟢 **Kabul Edildi**: Yayına hazır
- 🔴 **Reddedildi**: Revizyon gerekli
- ⚪ **Revizyon İstendi**: Düzeltme yapılması bekleniyor

---

### 3️⃣ Dergi Görüntüleme

#### Tüm Sayılar
```
URL: /wwwroot/dergigoster.php
```

**Sol Panel**: Dergi sayıları listesi
- Yeşil: Yayınlanmış
- Gri: Taslak/hazırlık

**Ana Panel**: Seçili sayının makaleleri

#### Tek Sayı Görüntüleme
```
URL: /wwwroot/dergigoster.php?dergi_id={ID}
```

**Gösterilen Bilgiler**:
- Dergi kapağı
- Yayın tarihi
- Jenerik PDF linki
- Makale listesi

#### Makale Detayı
```
URL: /wwwroot/makale_goster.php?sayi={SAYI_ID}&makale={MAKALE_ID}
```

**İçerik**:
- Makale başlığı (TR/EN)
- Yazar bilgisi
- Özet (TR/EN)
- Anahtar kelimeler
- PDF indirme linki

---

### 4️⃣ Şifre Sıfırlama

#### Adım 1: Şifre Unuttum
```
URL: /wwwroot/sifremiunuttum.php
```
- Email adresinizi girin
- "Gönder" butonuna tıklayın

#### Adım 2: Email Kontrolü
- Gelen kutunuzu kontrol edin
- Sıfırlama linkine tıklayın

#### Adım 3: Yeni Şifre
```
URL: /wwwroot/yenisifre.php?token={TOKEN}
```
- Yeni şifrenizi girin
- Tekrar girin (doğrulama)
- Kaydet

---

## 🛠️ Admin İşlemleri

### Dergi Sayı Oluşturma

#### Adım 1: Dergi Tanımlama
```
Admin Panel → Dergi Yönetimi → Yeni Sayı
URL: /wwwroot/dergi_tanim_edit.php?islem=1
```

**Gerekli Bilgiler**:
- Dergi tanımı (örn: "Cilt 5 Sayı 2 - Kış 2026")
- İngilizce başlık
- Yayın tarihi
- Kapak görseli (JPG/PNG)
- Jenerik PDF

#### Adım 2: Makale Ekleme
```
Admin Panel → Online Makale → Yeni
URL: /wwwroot/online_makale_edit.php?islem=1
```

**Her Makale İçin**:
1. Dergi sayısını seç
2. Makale bilgilerini gir
3. PDF yükle
4. Makale türünü belirle:
   - **Tür 1**: Sadece başlık ve PDF
   - **Tür 2**: Tam detaylar (özet, anahtar kelime, kaynakça)

#### Adım 3: Künye Hazırlama
```
Admin Panel → Dergi Künye → Düzenle
URL: /wwwroot/dergi_kunye_edit.php
```

**Künye Bölümleri**:
- Yayın Kurulu
- Editörler
- Hakem Kurulu
- Dergi Bilgileri

#### Adım 4: Yayınlama
```
Dergi Tanım → Düzenle → Onay: 1
```
- Onay değeri `1` yapılınca "Güncel Sayı" olur
- Önceki sayılar otomatik "Geçmiş Sayı" durumuna geçer

---

### 🏛️ İçerik Yönetimi (Yeni Dinamik Sistemler)

Yeni sistemle birlikte dergi içeriğinin büyük bir kısmı veritabanından yönetilebilir hale gelmiştir. Admin veya Editör olarak şu panellere erişebilirsiniz:

#### 1. Dergi Künyesi Yönetimi
```
Dashboard → Dergi Künyesi
URL: /admin/kunye
```
- Dergi jenerik bilgilerini, iletişim detaylarını ve diğer kurumsal bilgileri kategoriler (Yazışma Adresi, ISSN vb.) altında yönetebilirsiniz.
- Değişiklikler anında web sitesinin alt (footer) kısmına ve künye sayfasına yansır.

#### 2. Dizinler (Indexing) Yönetimi
```
Dashboard → Dizinler
URL: /admin/dizin
```
- Derginin tarandığı uluslararası dizinleri logolarıyla birlikte ekleyebilirsiniz.
- Logolar otomatik olarak `/public/images` klasörüne yüklenir ve `/dizinler` sayfasında görüntülenir.

#### 3. Makale Esasları (Yazım Kuralları)
```
Dashboard → Makale Esasları
URL: /admin/makale-esas
```
- Makale yazım kurallarını dinamik olarak güncelleyebilirsiniz.
- **Özel Fonksiyonlar**:
  - `ID=11`: `views/dergi/yzkural.php` dosyasını otomatik olarak içeriğe dahil eder.
  - `ID=12`: İçeriği madde madde (liste formatında) düzenler.

#### 4. Telif Sözleşmesi Yönetimi
```
Dashboard → Telif Sözleşmesi
URL: /admin/makale-sozlesme
```
- Telif devir sözleşmesi maddelerini başlık bazlı (TARAFLAR, YÜKÜMLÜLÜKLER vb.) yönetebilirsiniz.
- Aynı başlığa sahip maddeler kullanıcı sayfasında otomatik olarak gruplandırılır.

#### 5. Dergi Sayı Yönetimi
```
Dashboard → Dergi Sayıları
URL: /admin/dergi-tanim
```
- Yeni dergi sayıları (Volume/Issue) oluşturabilir, kapak görselleri ve jenerik PDF dosyalarını yükleyebilirsiniz.

#### 6. Makale Yayınlama (Submissions -> Online)
```
Dashboard → Hakem Süreci
URL: /admin/submissions
```
- Hakem sürecinden geçmiş (`Yayına Hazır`) makaleleri seçerek "Yayınla" butonu ile istediğiniz dergi sayısına atayabilirsiniz.
- Yayınlama aşamasında makale özeti, kaynakça ve atıf künyesi gibi detayları ekleyebilirsiniz.

---

### Kullanıcı Yönetimi

#### Yeni Kullanıcı Ekleme
```
Admin Panel → Yönetim → Yeni Kullanıcı
URL: /wwwroot/yonetim_edit.php?islem=1
```

**Bilgiler**:
- Ad soyad
- Email
- Şifre (hash'lenmiş olarak saklanır)
- Grup ID (yetki seviyesi)

#### Yetki Grupları
```
Admin Panel → Kullanıcı Grupları
URL: /wwwroot/usergroup_edit.php
```

**Varsayılan Gruplar**:
- 1: Admin (tam yetki)
- 2: Editör
- 3: Hakem
- 9999: Standart kullanıcı

#### Kurul Ataması
```
Admin Panel → Kurul Görev
URL: /wwwroot/kurul_gorev_edit.php
```

**Atama Süreci**:
1. Kurul tipini seç (Yayın, Hakem, Danışma)
2. Kullanıcıyı seç
3. Görev açıklaması gir
4. Kaydet

---

### Hakem Atama ve Değerlendirme

#### Adım 1: Gönderilen Makaleyi Belirleme
```
Admin Panel → Gönderilen Makaleler
URL: /wwwroot/gonderilen_makale_edit.php
```

#### Adım 2: Hakem Seçimi
- Makale konusuna uygun hakem seçin
- Email ile bildirim gönderin

#### Adım 3: Değerlendirme Takibi
```
Admin Panel → Hakem Kriter
URL: /wwwroot/hakem_kriter_edit.php
```

**Değerlendirme Kriterleri**:
- Özgünlük
- Bilimsel katkı
- Metodoloji
- Yazım kalitesi
- Kaynakça yeterliliği

#### Adım 4: Sonuç Bildirimi
- Yazara email ile sonuç bildir
- Durumu güncelle
- Revizyon gerekiyorsa talimatları ilet

---

### Raporlama

#### İstatistikler
```
Admin Panel → Dashboard
```

**Mevcut Metrikler**:
- Toplam makale sayısı
- Bekleyen değerlendirmeler
- Kabul/red oranları
- Kullanıcı sayıları

#### Export İşlemleri
- Makale listesi → Excel
- Kullanıcı listesi → CSV
- İstatistik raporu → PDF

---

## 🔧 Ayarlar ve Konfigürasyon

### Site Ayarları
```
Admin Panel → Ayarlar
URL: /wwwroot/ayarlar_edit.php
```

**Düzenlenebilir Ayarlar**:
- Site başlığı
- Meta açıklama
- İletişim bilgileri
- Logo
- Sosyal medya linkleri

### Menü Yönetimi

#### Ana Menü
```
Admin Panel → Ana Menü
URL: /wwwroot/anamenu_edit.php
```

**Örnek Menü Yapısı**:
```
Ana Sayfa
├── Hakkımızda
│   ├── Dergi Hakkında
│   └── Yayın İlkeleri
├── Arşiv
└── İletişim
```

#### Alt Menü
```
Admin Panel → Alt Menü
URL: /wwwroot/altmenu1_edit.php
```

**Parametreler**:
- Menü adı
- URL
- Üst menü (parent)
- Sıra numarası
- Aktif/Pasif

---

## 🐛 Sorun Giderme

### Yaygın Sorunlar ve Çözümleri

#### 1. Giriş Yapamıyorum
**Belirtiler**: "Hatalı kullanıcı adı veya şifre" mesajı

**Çözümler**:
- ✅ Şifrenizi doğru girdiğinizden emin olun (Caps Lock kapalı mı?)
- ✅ Email adresinizi kontrol edin
- ✅ Hesabınız aktif mi? (Email doğrulama yaptınız mı?)
- ✅ Şifre sıfırlama özelliğini kullanın

---

#### 2. Email Gelmiyor
**Belirtiler**: Aktivasyon/şifre sıfırlama emaili gelmiyor

**Çözümler**:
- ✅ Spam klasörünü kontrol edin
- ✅ Yahoo.com kullanmıyorsunuz değil mi?
- ✅ Email adresiniz doğru mu?
- ✅ 5-10 dakika bekleyin
- ✅ Admin ile iletişime geçin

---

#### 3. Dosya Yüklenmiyor
**Belirtiler**: PDF yükleme başarısız

**Çözümler**:
- ✅ Dosya boyutu 10MB'dan küçük mü?
- ✅ Dosya formatı PDF mi?
- ✅ Dosya adında Türkçe karakter var mı? (kaldırın)
- ✅ Tarayıcı cache'ini temizleyin
- ✅ Farklı tarayıcı deneyin

---

#### 4. Türkçe Karakter Sorunu
**Belirtiler**: Ğ Ü Ş İ Ö Ç yerine garip karakterler

**Çözümler**:
- ✅ Sayfa encoding UTF-8 olmalı
- ✅ Veritabanı charset UTF-8 olmalı
- ✅ Sayfayı yenileyin (Ctrl+F5)
- ✅ Sistemi admin'e bildirin

---

#### 5. Makale Durumu Güncellenmiyor
**Belirtiler**: Değerlendirme sonrası durum değişmiyor

**Çözümler**:
- ✅ Cache temizleyin
- ✅ Çıkış yapıp tekrar giriş yapın
- ✅ Admin değişikliği kaydetmiş mi?
- ✅ Sistem bakımda mı?

---

## 📞 Destek ve İletişim

### Teknik Destek
- **Email**: destek@ebp-dergi.com
- **Telefon**: +90 XXX XXX XX XX
- **Çalışma Saatleri**: Pazartesi-Cuma 09:00-18:00

### Hata Bildirimi
Hata bildirirken lütfen şunları belirtin:
1. Hata mesajının tam metni
2. Hangi sayfada oluştu (URL)
3. Ne yapmaya çalışıyordunuz
4. Ekran görüntüsü (mümkünse)
5. Tarayıcı ve işletim sistemi bilgisi

### Özellik İsteği
Yeni özellik önerileri için:
- Email: oneriler@ebp-dergi.com
- Form: /wwwroot/oneri_formu.php

---

## 📚 Ek Kaynaklar

### Dokümantasyon
- [README.md](./readme.md) - Sistem mimarisi
- [tasks.md](./tasks.md) - Görev listesi
- [oneriler.md](./oneriler.md) - İyileştirme önerileri
- [roadmap.md](./roadmap.md) - Geliştirme planı
- [implementation_plan.md](./implementation_plan.md) - Teknik detaylar

### Video Eğitimler
- Kullanıcı kaydı ve makale gönderimi
- Admin panel kullanımı
- Dergi sayı oluşturma
- Hakem değerlendirme süreci

### SSS (Sık Sorulan Sorular)
1. **Makale gönderimi ücretsiz mi?**
   - Evet, tüm süreç ücretsizdir.

2. **Değerlendirme süresi ne kadar?**
   - Ortalama 4-6 hafta.

3. **Aynı anda birden fazla makale gönderebilir miyim?**
   - Evet, sınırlama yoktur.

4. **Hakem raporlarını görebilir miyim?**
   - Evet, değerlendirme sonrası erişim verilir.

5. **Reddedilen makaleyi tekrar gönderebilir miyim?**
   - Evet, revize ederek yeniden gönderebilirsiniz.

---

**Son Güncelleme**: 2026-01-20  
**Versiyon**: 1.0  
**Hazırlayan**: EBP Development Team

---

**Notlar**: Bu dokümantasyon, sistemin mevcut durum analizine dayalıdır. Bazı özellikler henüz implement edilmemiş olabilir.
