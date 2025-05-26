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
                    <h4 class="text-blue h4">KASA KÜLÇE TAKİP</h4>
                </div>
                <div class="pb-20">
                    <table
                        class="table hover multiple-select-row data-table-export nowrap">

                        <thead>
                            <tr>

                                <th>Fiş No</th>
                                <th class="table-plus datatable-nosort">Müşteri</th>
                                <th class="table-plus datatable-nosort">Takoz Ağırlığı</th>
                                <th class="table-plus datatable-nosort">Tahmini Milyem</th>
                                <th class="table-plus datatable-nosort">Tahmini Has</th>
                                <th class="table-plus datatable-nosort">İşlem Gören Miktar</th>
                                <th class="table-plus datatable-nosort">Alınan Çeşni Ağırlığı</th>
                                <th class="table-plus datatable-nosort">Ölçülen Milyem</th>
                                <th class="table-plus datatable-nosort">Müşteri Notu</th>
                                <th class="table-plus datatable-nosort">Ölçüm Has</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Session'dan role değerini alalım
                            $role = session()->get('role');
                            ?>

                            <?php foreach ($items as $item): ?>
                                <tr>

                                    <td><?= esc($item['id']); ?></td>
                                    <td class="table-plus"><?= esc($item['musteri']); ?></td>
                                    <td><?= number_format(esc($item['giris_gram']), 2); ?> gr</td>
                                    <td><?= esc($item['tahmini_milyem']); ?></td>
                                    <td><?= number_format($item['giris_gram'] * ($item['tahmini_milyem'] / 1000), 2); ?> gr</td>
                                    <td><?= esc($item['islem_goren_miktar']); ?></td>
                                    <td><?= esc($item['cesni_gram']); ?></td>
                                    <td><?= esc($item['olculen_milyem']); ?></td>
                                    <td><?= esc($item['musteri_notu']) ?: '-'; ?></td>
                                    <td><?= number_format($item['giris_gram'] * ($item['olculen_milyem'] / 1000), 2); ?> gr</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="1" style="font-weight:bold;">Toplam Gram:</td>
                                <td style="font-weight:bold;"><?= number_format($totalGram, 3); ?> gr</td>
                               
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



<!-- Sadece ağırlık alanları olacak -->
<div class="modal fade" id="uretTakozModal" tabindex="-1" role="dialog" aria-labelledby="uretTakozModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="uretTakozForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Takoz Üret</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="uret_takoz_ids" name="ids[]">

                    <div class="form-group">
                        <label for="uret_adet">Üretilecek Takoz Adedi</label>
                        <input type="number" min="1" class="form-control" id="uret_adet" required>
                    </div>

                    <div id="uret_takoz_inputlari"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Kaydet</button>
                </div>
            </div>
        </form>
    </div>
</div>



<script>
    // Modal açıldığında: ID'leri al ve modalı göster
    document.getElementById('erit-button').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.select-row:checked')).map(cb => cb.value);

        if (selectedIds.length === 0) {
            alert('Lütfen en az bir takoz seçin.');
            return;
        }

        document.getElementById('uret_takoz_ids').value = selectedIds.join(',');
        document.getElementById('uret_adet').value = '';
        document.getElementById('uret_takoz_inputlari').innerHTML = '';
        $('#uretTakozModal').modal('show');
    });

    // Adet girilince sadece ağırlık inputları oluştur
    document.getElementById('uret_adet').addEventListener('input', function() {
        const adet = parseInt(this.value);
        const container = document.getElementById('uret_takoz_inputlari');
        container.innerHTML = '';

        if (adet > 0) {
            for (let i = 1; i <= adet; i++) {
                container.innerHTML += `
        <div class="form-group">
          <label>${i}. Takoz Ağırlığı (gr)</label>
          <input type="number" name="agirlik[]" step="0.01" class="form-control" required>
        </div>
      `;
            }
        }
    });

    // Form submit: sadece ID'ler ve ağırlıklar JSON olarak gönderilir
    document.getElementById('uretTakozForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const ids = document.getElementById('uret_takoz_ids').value.split(',');
        const agirliklar = Array.from(form.querySelectorAll('input[name="agirlik[]"]')).map(i => i.value);

        const takozlar = agirliklar.map(a => ({
            agirlik: a
        }));

        fetch("<?= base_url('home/uretTakoz') ?>", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                ids: ids,
                takozlar: takozlar
            })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                alert("Takozlar başarıyla üretildi!");
                $('#uretTakozModal').modal('hide');
                location.reload();
            } else {
                alert("Bir hata oluştu: " + data.error);
            }
        }).catch(err => {
            console.error(err);
            alert("Sunucu hatası.");
        });
    });
</script>




























<?= view('include/footer') ?>