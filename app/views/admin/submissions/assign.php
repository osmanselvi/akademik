<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3><i class="bi bi-person-check"></i> Hakem Atama</h3>
            <a href="/admin/submissions" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Geri Dön
            </a>
        </div>
        <p class="text-muted">
            Makale: <strong><?= e($item->makale_adi) ?></strong>
        </p>
    </div>

    <!-- Assignment Form -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                Yeni Hakem Ata
            </div>
            <div class="card-body">
                <form action="/admin/submissions/assign/<?= $item->id ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Hakem Seçiniz</label>
                        <select name="hakem_id" class="form-select" required>
                            <option value="">Seçiniz...</option>
                            <?php foreach ($reviewers as $reviewer): ?>
                                <option value="<?= $reviewer->id ?>"><?= e($reviewer->ad_soyad) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Son Değerlendirme Tarihi</label>
                        <input type="date" name="deadline" class="form-control" 
                               value="<?= date('Y-m-d', strtotime('+15 days')) ?>" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Hakem Ata
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Current Assignments -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <i class="bi bi-list-task"></i> Mevcut Atamalar
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Hakem</th>
                                <th>Atama Tarihi</th>
                                <th>Son Tarih</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($assignments)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Henüz hakem atanmamış.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($assignments as $assign): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-initials bg-secondary text-white rounded-circle me-2" 
                                                 style="width:30px; height:30px; display:flex; align-items:center; justify-content:center; font-size:12px;">
                                                <?= strtoupper(substr($assign->hakem_adi, 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?= e($assign->hakem_adi) ?></div>
                                                <small class="text-muted"><?= e($assign->email) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= formatDate($assign->created_at) ?></td>
                                    <td><?= formatDate($assign->deadline) ?></td>
                                    <td>
                                        <?php if ($assign->status == 0): ?>
                                            <span class="badge bg-warning text-dark">Bekliyor</span>
                                        <?php elseif ($assign->status == 1): ?>
                                            <span class="badge bg-info">Kabul Etti</span>
                                        <?php elseif ($assign->status == 2): ?>
                                            <span class="badge bg-danger">Reddetti</span>
                                        <?php elseif ($assign->status == 3): ?>
                                            <span class="badge bg-success">Tamamlandı</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" onclick="alert('Silme özelliği henüz aktif değil.')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
