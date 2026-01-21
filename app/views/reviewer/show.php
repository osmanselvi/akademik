<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Hakem Paneli', 'url' => '/reviewer'],
    ['text' => 'Değerlendirme', 'url' => '#']
];
?>

<div class="container py-5">
    <div class="row">
        <!-- Article Info -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Makale Bilgileri</h5>
                    <div class="mb-3">
                        <label class="text-muted small">Başlık</label>
                        <div class="fw-bold"><?= e($article->makale_adi) ?></div>
                    </div>
                    
                    <?php if (!empty($article->makale_ozet)): ?>
                    <div class="mb-3">
                        <label class="text-muted small">Özet</label>
                        <div class="small bg-light p-2 rounded text-secondary" style="max-height: 200px; overflow-y: auto;">
                            <?= e($article->makale_ozet) ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="d-grid mt-4">
                        <a href="<?= get_image_url($article->dosya) ?>" target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-download me-2"></i> Makale Dosyasını İndir
                        </a>
                        <button onclick="window.open('<?= get_image_url($article->dosya) ?>', '_blank')" class="btn btn-primary mt-2">
                             <i class="bi bi-eye me-2"></i> Yan Sekmede Aç
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluation Form -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold mb-0">Değerlendirme Formu</h4>
                </div>
                <div class="card-body p-4">
                    <form action="/reviewer/store/<?= $assignment->id ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <!-- Criteria Scoring -->
                        <h5 class="border-bottom pb-2 mb-4">Kriter Değerlendirmesi</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kriter</th>
                                        <th style="width: 150px;">Puan (1-10)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($criteria as $kriter): ?>
                                    <tr>
                                        <td><?= e($kriter->kriter) ?></td>
                                        <td>
                                            <input type="number" name="puan[<?= $kriter->id ?>]" class="form-control" min="1" max="10" required>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Notes -->
                        <h5 class="border-bottom pb-2 mb-4 mt-5">Görüş ve Öneriler</h5>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Yazara Notlar</label>
                            <div class="form-text mb-1">Bu alan yazar tarafından görülecektir.</div>
                            <textarea name="notlar_yazar" class="form-control" rows="6" placeholder="Makalenin güçlü ve zayıf yönleri, geliştirilmesi gereken kısımlar..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Editöre Notlar (Opsiyonel)</label>
                            <div class="form-text mb-1">Bu alan sadece editör tarafından görülecek, yazar görmeyecektir.</div>
                            <textarea name="notlar_editor" class="form-control" rows="4" placeholder="Editöre iletmek istediğiniz özel notlar..."></textarea>
                        </div>

                        <!-- Annotated File Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Dosya Yükle (Opsiyonel)</label>
                            <div class="form-text mb-1">Makale üzerinde notlar aldıysanız dosyayı buradan yükleyebilirsiniz.</div>
                            <input type="file" name="dosya" class="form-control" accept=".pdf,.doc,.docx">
                        </div>

                        <!-- Decision -->
                        <h5 class="border-bottom pb-2 mb-4 mt-5">Sonuç Kararı</h5>
                        
                        <div class="mb-4">
                            <div class="form-check p-3 border rounded mb-2 hover-bg-light cursor-pointer">
                                <input class="form-check-input" type="radio" name="karar" id="k1" value="1" required>
                                <label class="form-check-label w-100 stretched-link" for="k1">
                                    <strong class="text-success"><i class="bi bi-check-circle-fill"></i> Kabul Edilsin</strong>
                                    <div class="small text-muted ps-4">Makale bu haliyle yayımlanabilir.</div>
                                </label>
                            </div>
                            
                            <div class="form-check p-3 border rounded mb-2 hover-bg-light cursor-pointer">
                                <input class="form-check-input" type="radio" name="karar" id="k2" value="2">
                                <label class="form-check-label w-100 stretched-link" for="k2">
                                    <strong class="text-warning"><i class="bi bi-pencil-fill"></i> Düzeltme İstensin</strong>
                                    <div class="small text-muted ps-4">Belirtilen düzeltmeler yapıldıktan sonra tekrar değerlendirilsin.</div>
                                </label>
                            </div>

                            <div class="form-check p-3 border rounded mb-2 hover-bg-light cursor-pointer">
                                <input class="form-check-input" type="radio" name="karar" id="k3" value="3">
                                <label class="form-check-label w-100 stretched-link" for="k3">
                                    <strong class="text-danger"><i class="bi bi-x-circle-fill"></i> Red Edilsin</strong>
                                    <div class="small text-muted ps-4">Makale yayınlanmaya uygun değildir.</div>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                             <div class="alert alert-info">
                                <i class="bi bi-info-circle me-1"></i> Gönder butonuna bastığınızda değerlendirmeniz kaydedilecek ve editöre bildirilecektir. Bu işlem geri alınamaz.
                             </div>
                             <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send-fill me-2"></i> Değerlendirmeyi Tamamla ve Gönder
                             </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
