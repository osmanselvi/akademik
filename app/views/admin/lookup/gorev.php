<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Görev Yönetimi', 'url' => '/admin/lookup/gorev']
];
?>

<div class="container py-5">
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-8">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-briefcase text-primary me-2"></i> Kurul Görevleri
            </h2>
            <p class="text-secondary mb-0">Kurullardaki görevleri (Baş Editör, Editör vb.) tanımlayın.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="/admin/kurul" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Kurul Üyelerine Dön
            </a>
        </div>
    </div>

    <!-- Add New Form -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Yeni Görev Ekle</h5>
            <form action="/admin/lookup/gorev/store" method="POST" class="row g-3">
                <?= csrf_field() ?>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary">KURUL</label>
                    <select name="kurul" class="form-select bg-light border-0 py-2 rounded-3" required>
                        <option value="">Kurul Seçiniz</option>
                        <?php foreach($kurullar as $k): ?>
                            <option value="<?= $k->id ?>"><?= e($k->kurul) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">GÖREV ADI</label>
                    <input type="text" name="kurul_gorev" class="form-control bg-light border-0 py-2 rounded-3" 
                           placeholder="Örn: Baş Editör, Alan Editörü" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label small fw-bold text-secondary">AKTİF</label>
                    <div class="form-check form-switch pt-2">
                        <input class="form-check-input" type="checkbox" name="is_approved" checked>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-3">
                        <i class="bi bi-plus-circle me-1"></i> Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Kurul</th>
                            <th>Görev</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($gorevler)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Henüz görev tanımlanmamış.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($gorevler as $g): ?>
                            <tr id="row-<?= $g->id ?>">
                                <td class="ps-4 fw-bold text-secondary">#<?= $g->id ?></td>
                                <td>
                                    <span class="view-mode">
                                        <span class="badge bg-soft-info text-dark">
                                            <?php 
                                            $kurulName = 'Bilinmiyor';
                                            foreach($kurullar as $k) {
                                                if ($k->id == $g->kurul) {
                                                    $kurulName = $k->kurul;
                                                    break;
                                                }
                                            }
                                            echo e($kurulName);
                                            ?>
                                        </span>
                                    </span>
                                    <select class="form-select form-select-sm edit-mode d-none" data-field="kurul">
                                        <?php foreach($kurullar as $k): ?>
                                            <option value="<?= $k->id ?>" <?= $g->kurul == $k->id ? 'selected' : '' ?>><?= e($k->kurul) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <span class="view-mode fw-semibold"><?= e($g->kurul_gorev) ?></span>
                                    <input type="text" class="form-control form-control-sm edit-mode d-none" value="<?= e($g->kurul_gorev) ?>" data-field="kurul_gorev">
                                </td>
                                <td>
                                    <span class="view-mode">
                                        <?php if ($g->is_approved): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Pasif</span>
                                        <?php endif; ?>
                                    </span>
                                    <div class="form-check form-switch edit-mode d-none">
                                        <input class="form-check-input" type="checkbox" data-field="is_approved" <?= $g->is_approved ? 'checked' : '' ?>>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="view-mode">
                                        <button class="btn btn-sm btn-light shadow-sm me-1" onclick="editRow(<?= $g->id ?>)">
                                            <i class="bi bi-pencil"></i> Düzenle
                                        </button>
                                        <button class="btn btn-sm btn-light-danger shadow-sm" onclick="deleteItem(<?= $g->id ?>)">
                                            <i class="bi bi-trash"></i> Sil
                                        </button>
                                    </div>
                                    <div class="edit-mode d-none">
                                        <button class="btn btn-sm btn-success shadow-sm me-1" onclick="saveRow(<?= $g->id ?>)">
                                            <i class="bi bi-check"></i> Kaydet
                                        </button>
                                        <button class="btn btn-sm btn-secondary shadow-sm" onclick="cancelEdit(<?= $g->id ?>)">
                                            <i class="bi bi-x"></i> İptal
                                        </button>
                                    </div>
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

<style>
.bg-soft-info { background-color: rgba(52, 219, 213, 0.1); }
.btn-light-danger { 
    background-color: rgba(231, 76, 60, 0.1); 
    color: #e74c3c;
    border: none;
}
.btn-light-danger:hover {
    background-color: #e74c3c;
    color: white;
}
</style>

<script>
function editRow(id) {
    const row = document.getElementById('row-' + id);
    row.querySelectorAll('.view-mode').forEach(el => el.classList.add('d-none'));
    row.querySelectorAll('.edit-mode').forEach(el => el.classList.remove('d-none'));
}

function cancelEdit(id) {
    location.reload();
}

function saveRow(id) {
    const row = document.getElementById('row-' + id);
    const kurul = row.querySelector('[data-field="kurul"]').value;
    const kurulGorev = row.querySelector('[data-field="kurul_gorev"]').value;
    const isApproved = row.querySelector('[data-field="is_approved"]').checked ? 1 : 0;

    const formData = new FormData();
    formData.append('kurul', kurul);
    formData.append('kurul_gorev', kurulGorev);
    if (isApproved) formData.append('is_approved', '1');

    fetch('/admin/lookup/gorev/update/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?= csrf_token() ?>'
        },
        body: formData
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        }
    });
}

function deleteItem(id) {
    if (confirm('Bu görevi silmek istediğinize emin misiniz?')) {
        fetch('/admin/lookup/gorev/delete/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?= csrf_token() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Bir hata oluştu.');
            }
        });
    }
}
</script>
