<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Unvan Yönetimi', 'url' => '/admin/lookup/unvan']
];
?>

<div class="container py-5">
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-8">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-mortarboard text-primary me-2"></i> Akademik Unvan Yönetimi
            </h2>
            <p class="text-secondary mb-0">Kurul üyelerinde kullanılacak unvanları tanımlayın.</p>
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
            <h5 class="fw-bold mb-3">Yeni Unvan Ekle</h5>
            <form action="/admin/lookup/unvan/store" method="POST" class="row g-3">
                <?= csrf_field() ?>
                <div class="col-md-8">
                    <input type="text" name="unvan" class="form-control bg-light border-0 py-2 rounded-3" 
                           placeholder="Örn: Prof. Dr., Doç. Dr., Dr. Öğr. Üyesi" required>
                </div>
                <div class="col-md-2">
                    <div class="form-check form-switch pt-2">
                        <input class="form-check-input" type="checkbox" name="is_approved" id="isApproved" checked>
                        <label class="form-check-label" for="isApproved">Aktif</label>
                    </div>
                </div>
                <div class="col-md-2">
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
                            <th>Unvan</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($unvanlar)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Henüz unvan tanımlanmamış.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($unvanlar as $u): ?>
                            <tr id="row-<?= $u->id ?>">
                                <td class="ps-4 fw-bold text-secondary">#<?= $u->id ?></td>
                                <td>
                                    <span class="view-mode fw-semibold"><?= e($u->unvan) ?></span>
                                    <input type="text" class="form-control form-control-sm edit-mode d-none" value="<?= e($u->unvan) ?>" data-field="unvan">
                                </td>
                                <td>
                                    <span class="view-mode">
                                        <?php if ($u->is_approved): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Pasif</span>
                                        <?php endif; ?>
                                    </span>
                                    <div class="form-check form-switch edit-mode d-none">
                                        <input class="form-check-input" type="checkbox" data-field="is_approved" <?= $u->is_approved ? 'checked' : '' ?>>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="view-mode">
                                        <button class="btn btn-sm btn-light shadow-sm me-1" onclick="editRow(<?= $u->id ?>)">
                                            <i class="bi bi-pencil"></i> Düzenle
                                        </button>
                                        <button class="btn btn-sm btn-light-danger shadow-sm" onclick="deleteItem(<?= $u->id ?>)">
                                            <i class="bi bi-trash"></i> Sil
                                        </button>
                                    </div>
                                    <div class="edit-mode d-none">
                                        <button class="btn btn-sm btn-success shadow-sm me-1" onclick="saveRow(<?= $u->id ?>)">
                                            <i class="bi bi-check"></i> Kaydet
                                        </button>
                                        <button class="btn btn-sm btn-secondary shadow-sm" onclick="cancelEdit(<?= $u->id ?>)">
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
    location.reload(); // Simple way to reset
}

function saveRow(id) {
    const row = document.getElementById('row-' + id);
    const unvan = row.querySelector('[data-field="unvan"]').value;
    const isApproved = row.querySelector('[data-field="is_approved"]').checked ? 1 : 0;

    const formData = new FormData();
    formData.append('unvan', unvan);
    if (isApproved) formData.append('is_approved', '1');

    fetch('/admin/lookup/unvan/update/' + id, {
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
    if (confirm('Bu unvanı silmek istediğinize emin misiniz?')) {
        fetch('/admin/lookup/unvan/delete/' + id, {
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
