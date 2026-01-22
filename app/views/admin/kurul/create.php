<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Kurul Üyeleri', 'url' => '/admin/kurul'],
    ['text' => 'Yeni Üye', 'url' => '/admin/kurul/create']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0">
                        <i class="bi bi-person-plus text-primary me-2"></i> Yeni Kurul Üyesi Ekle
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="/admin/kurul/store" method="POST" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="row g-4">
                            <!-- Ad Soyad & Unvan -->
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-secondary">UNVAN</label>
                                <select name="unvan" class="form-select bg-light border-0 py-2 rounded-3" required>
                                    <option value="">Seçiniz</option>
                                    <?php foreach($unvanlar as $u): ?>
                                        <option value="<?= $u->id ?>" <?= old('unvan') == $u->id ? 'selected' : '' ?>>
                                            <?= e($u->unvan) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?= error('unvan') ?>
                                <?= error('unvan') ?>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold small text-secondary">SIRA NO</label>
                                <input type="number" name="sira" class="form-control bg-light border-0 py-2 rounded-3" 
                                       value="<?= old('sira', 0) ?>" required>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-bold small text-secondary">AD SOYAD</label>
                                <input type="text" name="ad_soyad" class="form-control bg-light border-0 py-2 rounded-3" 
                                       placeholder="Örn: Ahmet Yılmaz" value="<?= old('ad_soyad') ?>" required>
                                <?= error('ad_soyad') ?>
                            </div>

                            <!-- Kurul & Görev -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">AİT OLDUĞU KURUL</label>
                                <select id="kurul_select" class="form-select bg-light border-0 py-2 rounded-3" required>
                                    <option value="">Kurul Seçiniz</option>
                                    <?php foreach($kurullar as $k): ?>
                                        <option value="<?= $k->id ?>"><?= e($k->kurul) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text small mt-1">Önce kurulu seçmelisiniz.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">KURULDAKİ GÖREVİ</label>
                                <select name="kurul_gorev" id="gorev_select" class="form-select bg-light border-0 py-2 rounded-3" required disabled>
                                    <option value="">Önce Kurul Seçin</option>
                                </select>
                                <?= error('kurul_gorev') ?>
                            </div>

                            <!-- Kurum/Bölüm & Açıklama -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">KURUM / BÖLÜM</label>
                                <input type="text" name="bolum_ad" class="form-control bg-light border-0 py-2 rounded-3" 
                                       placeholder="Örn: İstanbul Üniversitesi, Edebiyat Fakültesi" value="<?= old('bolum_ad') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-secondary">EK AÇIKLAMA</label>
                                <input type="text" name="aciklama" class="form-control bg-light border-0 py-2 rounded-3" 
                                       placeholder="Örn: Bölüm Başkanı" value="<?= old('aciklama') ?>">
                            </div>

                            <!-- İletişim Bilgileri -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">E-POSTA</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-0 py-2 rounded-end-3" 
                                           placeholder="eposta@adres.com" value="<?= old('email') ?>">
                                </div>
                                <?= error('email') ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">ORCID NUMARASI</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person-badge"></i></span>
                                    <input type="text" name="orcid_number" class="form-control bg-light border-0 py-2 rounded-end-3" 
                                           placeholder="0000-0000-0000-0000" value="<?= old('orcid_number') ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-secondary">WEB SAYFASI</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-link-45deg"></i></span>
                                    <input type="url" name="web_page" class="form-control bg-light border-0 py-2 rounded-end-3" 
                                           placeholder="https://..." value="<?= old('web_page') ?>">
                                </div>
                            </div>

                            <hr class="my-4 text-muted opacity-25">

                            <div class="col-12">
                                <div class="form-check form-switch bg-light p-3 rounded-4 d-flex align-items-center">
                                    <input class="form-check-input ms-0 me-3 mt-0" type="checkbox" name="is_approved" id="isApproved" checked>
                                    <div>
                                        <label class="form-check-label fw-bold d-block" for="isApproved">Listede Yayınla</label>
                                        <small class="text-secondary">Üye bilgileri onaylandığında hemen sitede görünür.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-5 py-3 border-top d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm">
                                    <i class="bi bi-check2-circle me-1"></i> Kaydet
                                </button>
                                <a href="/admin/kurul" class="btn btn-light px-4 rounded-pill">
                                    Vazgeç
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('kurul_select').addEventListener('change', function() {
    const kurulId = this.value;
    const gorevSelect = document.getElementById('gorev_select');
    
    // Clear and disable gorev select
    gorevSelect.innerHTML = '<option value="">Yükleniyor...</option>';
    gorevSelect.disabled = true;

    if (!kurulId) {
        gorevSelect.innerHTML = '<option value="">Önce Kurul Seçin</option>';
        return;
    }

    fetch('/api/kurul/duties/' + kurulId)
        .then(response => response.json())
        .then(data => {
            gorevSelect.innerHTML = '<option value="">Görev Seçiniz</option>';
            if (data.length > 0) {
                data.forEach(item => {
                    gorevSelect.innerHTML += `<option value="${item.id}">${item.kurul_gorev}</option>`;
                });
                gorevSelect.disabled = false;
            } else {
                gorevSelect.innerHTML = '<option value="">Bu kurul için görev tanımlanmamış</option>';
            }
        });
});
</script>
