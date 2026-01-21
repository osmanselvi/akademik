<div class="container py-5">
    <div class="row" data-aos="fade-up">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0">
                        <i class="bi bi-people text-primary me-2"></i> Kullanıcı Yönetimi
                    </h4>
                    <span class="badge bg-light text-secondary rounded-pill px-3 py-2">
                        Toplam <?= count($users) ?> Kullanıcı
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Ad Soyad</th>
                                    <th>E-posta</th>
                                    <th>Yetki Grubu</th>
                                    <th class="text-end pe-4">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): ?>
                                <tr>
                                    <td class="ps-4 text-secondary">#<?= $u->id ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-soft-primary text-primary me-3 d-flex align-items-center justify-content-center">
                                                <?= strtoupper(substr($u->ad_soyad, 0, 1)) ?>
                                            </div>
                                            <span class="fw-semibold"><?= e($u->ad_soyad) ?></span>
                                        </div>
                                    </td>
                                    <td><?= e($u->email) ?></td>
                                    <td>
                                        <select class="form-select form-select-sm rounded-pill" onchange="updateGroup(<?= $u->id ?>, this.value)">
                                            <?php foreach ($groups as $g): ?>
                                                <option value="<?= $g->id ?>" <?= $u->grup_id == $g->id ? 'selected' : '' ?>>
                                                    <?= e($g->grupadi) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light-danger" onclick="deleteUser(<?= $u->id ?>)" <?= $u->id == $_SESSION['user_id'] ? 'disabled' : '' ?>>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div class="text-secondary small">
                    <?= $paginator->getInfo() ?>
                </div>
                <div>
                    <?= $paginator->getLinks() ?>
                </div>
            </div>
            
        </div>
    </div>
</div>

<style>
.bg-soft-primary { background-color: rgba(52, 152, 219, 0.1); }
.btn-light-danger { 
    background-color: rgba(231, 76, 60, 0.1); 
    color: #e74c3c;
    border: none;
}
.btn-light-danger:hover {
    background-color: #e74c3c;
    color: white;
}
.avatar-sm {
    width: 35px;
    height: 35px;
    font-size: 0.85rem;
    font-weight: bold;
}
</style>

<script>
function deleteUser(id) {
    if (confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')) {
        fetch('/admin/users/delete/' + id, {
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

function updateGroup(userId, groupId) {
    const formData = new FormData();
    formData.append('grup_id', groupId);

    fetch('/admin/users/update-group/' + userId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?= csrf_token() ?>'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert(data.message || 'Güncelleme sırasında bir hata oluştu.');
            location.reload();
        }
    });
}
</script>
