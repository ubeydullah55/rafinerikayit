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
                            <h4>Rafineri Takoz Listesi</h4>
                              <button type="submit" class="btn btn-success" onclick="window.location.href='<?= base_url('homepage'); ?>'">Mal Kabul</button>
                            <button type="button" class="btn btn-success" onclick="window.location.href='<?= base_url('home/ayarevi'); ?>'">
                                Ayar Evi
                            </button>
                            <button type="button" class="btn btn-success" onclick="window.location.href='<?= base_url('home/eritme'); ?>'">
                                İfraz
                            </button>
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
                    <h4 class="text-blue h4">Eritme</h4>
                </div>
                <div class="pb-20">
                    <table
                        class="table hover multiple-select-row data-table-export nowrap">

                        <thead>
                            <tr>
                                  <th>
                        <input type="checkbox" id="select-all">
                    </th>
                                <th>Fiş No</th>
                                <th class="table-plus datatable-nosort">Müşteri</th>
                                <th class="table-plus datatable-nosort">Takoz Ağırlığı</th>
                                <th class="table-plus datatable-nosort">Tahmini Milyem</th>
                                <th class="table-plus datatable-nosort">Tahmini Has</th>
                                <th class="table-plus datatable-nosort">İşlem Gören Miktar</th>
                                <th class="table-plus datatable-nosort">Alınan Çeşni Ağırlığı</th>
                                <th class="table-plus datatable-nosort">Ölçülen Milyem</th>
                                <th class="table-plus datatable-nosort">Müşteri Notu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Session'dan role değerini alalım
                            $role = session()->get('role');
                            ?>

                            <?php foreach ($items as $item): ?>
                               <tr>
                                  <td>
                            <input type="checkbox" class="select-row" value="<?= $item['id']; ?>">
                        </td>
                                    <td><?= esc($item['id']); ?></td>
                                    <td class="table-plus"><?= esc($item['musteri']); ?></td>
                                    <td><?= number_format(esc($item['giris_gram']), 2); ?> gr</td>
                                    <td><?= esc($item['tahmini_milyem']); ?></td>
                                    <td><?= number_format($item['giris_gram'] * ($item['tahmini_milyem'] / 1000), 2); ?> gr</td>
                                    <td><?= esc($item['islem_goren_miktar']); ?></td>
                                    <td><?= esc($item['cesni_gram']); ?></td>
                                    <td><?= esc($item['olculen_milyem']); ?></td>
                                    <td><?= esc($item['musteri_notu']) ?: '-'; ?></td>
                              
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="1" style="font-weight:bold;">Toplam Gram:</td>
                                <td style="font-weight:bold;"><?= number_format($totalGram, 3); ?> gr</td>
                                <td colspan="3"><button type="button" id="erit-button" class="btn btn-success">
    Erit
</button></td>
                            </tr>
                            
                        </tfoot>
                    </table>
                </div>

            </div>


            <!-- Export Datatable End -->
        </div>




        <div class="footer-wrap pd-20 mb-20 card-box">
            Creadet By
            <a href="https://github.com/ubeydullah55" target="_blank">Ubeydullah Doğan</a>
        </div>
    </div>
</div>
<!-- Çeşni Modalı -->
<div class="modal fade" id="cesniModal" tabindex="-1" role="dialog" aria-labelledby="cesniModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="cesniForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="cesniModalLabel">Çeşni Miktarı Gir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="cesniId" name="id">
                    <div class="form-group">
                        <label for="cesniGram">Çeşni Gramı</label>
                        <input type="number" step="0.001" min="0" class="form-control" id="cesniGram" name="cesni_gram" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ekle</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="kalancesniModal" tabindex="-1" role="dialog" aria-labelledby="kalancesniModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="kalancesniForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="kalancesniModalLabel">Çeşni Miktarı Gir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="cesniId" name="id">
                    <div class="form-group">
                        <label for="cesniGram">Kalan Çeşni Gramı</label>
                        <input type="number" step="0.001" min="0" class="form-control" id="kalancesniGram" name="kalan_cesni_gram" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ekle</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                </div>
            </form>
        </div>
    </div>
</div>






<script>
    function openCesniModal(id) {
        document.getElementById('cesniId').value = id;
        document.getElementById('cesniGram').value = '';
        $('#cesniModal').modal('show');
    }

    document.getElementById('cesniForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('cesniId').value;
        const gram = parseFloat(document.getElementById('cesniGram').value);

        if (!gram || gram <= 0) {
            alert("Geçerli bir gram değeri girin.");
            return;
        }

        fetch('<?= base_url('takoz/cesniEkle'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: id,
                    cesni_gram: gram
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#cesniModal').modal('hide');
                    Swal.fire("Başarılı", "Çeşni alındı.", "success").then(() => {
                        location.reload(); // sayfa yenile
                    });
                } else {
                    Swal.fire("Hata", "İşlem başarısız.", "error");
                }
            })
            .catch(error => {
                console.error('Hata:', error);
                Swal.fire("Hata", "Sunucu hatası.", "error");
            });
    });
</script>




<script>
    function openKalanCesniModal(id) {
        document.getElementById('cesniId').value = id;
        document.getElementById('cesniGram').value = '';
        $('#kalancesniModal').modal('show');
    }

    document.getElementById('kalancesniForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('cesniId').value;
        const gram = parseFloat(document.getElementById('kalancesniGram').value);

        if (!gram || gram <= 0) {
            alert("Geçerli bir gram değeri girin.");
            return;
        }

        fetch('<?= base_url('takoz/kalancesniEkle'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: id,
                    cesni_gram: gram
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#cesniModal').modal('hide');
                    Swal.fire("Başarılı", "Çeşni alındı.", "success").then(() => {
                        location.reload(); // sayfa yenile
                    });
                } else {
                    Swal.fire("Hata", "İşlem başarısız.", "error");
                }
            })
            .catch(error => {
                console.error('Hata:', error);
                Swal.fire("Hata", "Sunucu hatası.", "error");
            });
    });
</script>



<script>
    function ilerletTakoz(id, musteri) {
        Swal.fire({
            title: 'Emin misin?',
            text: musteri + ' için ilerletme işlemi yapmak istediğine emin misin?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, ilerlet!',
            cancelButtonText: 'Vazgeç'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= base_url('takoz/ilerletAjax/'); ?>' + id, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'İşlem Başarılı!',
                                'Müşteri ilerletildi.',
                                'success'
                            ).then(() => {
                                location.reload(); // sayfayı yenile
                            });
                        } else {
                            Swal.fire('Hata', 'İşlem gerçekleştirilemedi.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Hata:', error);
                        Swal.fire('Hata', 'Sunucuya erişilemedi.', 'error');
                    });
            }
        });
    }
</script>





<script>
    function deleteCustomer(id, adSoyad) {
        Swal.fire({
            title: 'Emin misin?',
            text: adSoyad + ' isimli kişiyi silmek istediğine emin misin?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'Vazgeç'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('customerdelete/'); ?>' + id;
            }
        });
    }
</script>


<script>
    function redirectToEditPage(id) {
        // Kayıt etme ekranına yönlendiriyoruz ve ID parametresini URL'ye ekliyoruz
        window.location.href = '<?= base_url("customereditview/"); ?>' + id;
    }
</script>
<script>
    function redirectToViewPage(id) {
        // Kayıt etme ekranına yönlendiriyoruz ve ID parametresini URL'ye ekliyoruz
        window.location.href = '<?= base_url("customerview/"); ?>' + id;
    }
</script>

<script>
    document.getElementById('erit-button').addEventListener('click', function () {
    const selectedIds = Array.from(document.querySelectorAll('.select-row:checked')).map(cb => cb.value);

    if (selectedIds.length === 0) {
        alert('Lütfen en az bir takoz seçin.');
        return;
    }

    const adet = prompt("Kaç adet takoz üretildi?");
    if (!adet || isNaN(adet)) return;

    const takozlar = [];

    for (let i = 1; i <= adet; i++) {
        const agirlik = prompt(`${i}. Takozun ağırlığı (gram):`);
        const milyem = prompt(`${i}. Takozun milyemi:`);

        if (!agirlik || !milyem) return;

        takozlar.push({ agirlik, milyem });
    }

    // AJAX ile sunucuya gönder
    fetch("<?= base_url('home/uretTakoz') ?>", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            ids: selectedIds,
            takozlar: takozlar
        })
    }).then(res => res.json()).then(data => {
        if (data.success) {
            alert("Takozlar başarıyla üretildi!");
            location.reload();
        } else {
            alert("Bir hata oluştu: " + data.error);
        }
    });
});

</script>


<?= view('include/footer') ?>