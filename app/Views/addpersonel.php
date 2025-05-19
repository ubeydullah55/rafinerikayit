<?= view('include/header') ?>

<?= view('include/leftmenu') ?>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success" style="text-align: center;">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger" style="text-align: center;">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Kullanıcı Ekleme-Düzenleme</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="index.html">Anasayfa</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Liste
                                </li>
                            </ol>
                        </nav>
                    </div>

                </div>
            </div>
            <!-- Simple Datatable start -->

            <!-- Simple Datatable End -->
            <!-- multiple select row Datatable start -->

            <!-- multiple select row Datatable End -->
            <!-- Checkbox select Datatable start -->

            <!-- Checkbox select Datatable End -->
            <!-- Export Datatable start -->




            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Yeni Kullanıcı Ekle</h4>
                </div>
                <div class="pb-20">
                    <div class="pd-20 card-box mb-30">
                        <form action="<?= site_url('insertuser') ?>" method="post">
                            <div class="form-group row">
                                <label class="col-sm-1 col-md-1 col-form-label">Kullanıcı Adı:</label>
                                <div class="col-sm-4 col-md-4">
                                    <input
                                        name="name"
                                        class="form-control"
                                        type="text"
                                        placeholder="Kullanıcı Adı"
                                        required />
                                </div>

                                <label class="col-sm-1 col-md-1 col-form-label">Şifre:</label>
                                <div class="col-sm-4 col-md-4">
                                    <input
                                        name="password"
                                        class="form-control"
                                        type="text"
                                        placeholder="Şifre"
                                        required />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-1 col-md-1 col-form-label">Rol-Yetki</label>
                                <div class="col-sm-12 col-md-10">
                                    <select name="role" class="custom-select col-12" required>
                                        <option value="1">Developer</option>
                                        <option value="2">Admin</option>
                                        <option value="3">User</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 col-md-12 text-right">
                                    <button type="submit" class="btn btn-success">Kullanıcı Ekle</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!-- Export Datatable End -->


            <!-- user_list.php -->

            <div class="pd-20 card-box mb-30">
                <div class="clearfix mb-20">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Kullanıcı Listesi</h4>
                        <p>Kullanıcı <code>.düzenle</code></p>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">İd</th>
                            <th scope="col">Ad</th>
                            <th scope="col">Rol</th>
                            <th scope="col">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <th scope="row"><?= $user['user_id']; ?></th>
                                <td><?= $user['name']; ?></td>
                                <td>
                                    <?= $user['role'] == 1 ? 'Developper' : ($user['role'] == 2 ? 'Admin' : ($user['role'] == 3 ? 'User' : 'Bilinmiyor')); ?>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-primary"
                                        onclick="openEditModal(<?= $user['user_id'] ?>, '<?= $user['name'] ?>', <?= $user['role'] ?>)">
                                        Düzenle
                                    </button>
                                    <button class="btn btn-danger" onclick="confirmDelete(<?= $user['user_id'] ?>, '<?= $user['name'] ?>')">
                                        Sil
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


            <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="<?= site_url('updateuser') ?>">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel">Kullanıcıyı Düzenle</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="edit_user_id" name="user_id">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Ad</label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_password" class="form-label">Şifre</label>
                                    <input type="password" class="form-control" id="edit_password" name="password" placeholder="Yeni şifre girin">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_role" class="form-label">Rol</label>
                                    <select class="form-select" id="edit_role" name="role" required>
                                        <option value="1">Developper</option>
                                        <option value="2">Admin</option>
                                        <option value="3">User</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                <button type="submit" class="btn btn-success">Kaydet</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
        <div class="footer-wrap pd-20 mb-20 card-box">
            Creadet By
            <a href="https://github.com/ubeydullah55" target="_blank">Ubeydullah Doğan</a>
        </div>
    </div>
</div>



<script>
    function openEditModal(userId, name, role) {
        document.getElementById('edit_user_id').value = userId;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_role').value = role;

        // Şifreyi boş bırakıyoruz, kullanıcı isterse değiştirecek
        document.getElementById('edit_password').value = '';

        // Bootstrap 5 Modal açtırıyoruz
        var myModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        myModal.show();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(userId, userName) {
        // SweetAlert ile kullanıcıdan onay alıyoruz
        Swal.fire({
            title: `${userName} kullanıcısını silmek istediğinize emin misiniz?`,
            text: 'Bu işlem geri alınamaz!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'Hayır, iptal et'
        }).then((result) => {
            if (result.isConfirmed) {
                // Evet, silmeye onay verildiyse silme işlemi için backend'e yönlendirme yapıyoruz
                window.location.href = '<?= site_url('deleteuser') ?>/' + userId;
            }
        });
    }
</script>





<?= view('include/footer') ?>