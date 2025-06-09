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
                            <button type="button" class="btn btn-success" onclick="window.location.href='<?= base_url('home/islenecek'); ?>'">
                                İşlenecek
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
                    <h4 class="text-blue h4">REAKTÖR</h4>
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
                                        <input type="checkbox" class="select-row" value="<?= $item['id']; ?>" data-agirlik="<?= $item['islem_goren_miktar']; ?>" data-has="<?= $item['islem_goren_miktar'] * (( !empty($item['olculen_milyem']) && $item['olculen_milyem'] != 0 ) ? $item['olculen_milyem'] : $item['tahmini_milyem']) / 1000 ?>">
                                    </td>

                                    <td><?= esc($item['id']); ?></td>
                                    <td class="table-plus"><?= esc($item['musteri_adi']); ?></td>
                                    <td><?= number_format(esc($item['giris_gram']), 2); ?> gr</td>
                                    <td><?= esc($item['tahmini_milyem']); ?></td>
                                    <td>
                                        <?php
                                        $gram = !empty($item['islem_goren_miktar']) ? $item['islem_goren_miktar'] : $item['giris_gram'];

                                        $milyem = (!empty($item['olculen_milyem']) && $item['olculen_milyem'] != 0)
                                            ? $item['olculen_milyem']
                                            : $item['tahmini_milyem'];

                                        echo number_format($gram * ($milyem / 1000), 2);
                                        ?> gr
                                    </td>
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
                                <td style="font-weight:bold;"><?= number_format($totalGram, 2); ?> gr</td>
                                <td colspan="3"><button type="button" id="erit-button" class="btn btn-success">
                                        Reaktöre At
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
                <div class="row">
                    <!-- SOL SÜTUN -->
                    <div class="col-md-6">
                        <div class="form-group text-center">
                            <label>Seçilen Toplam Ağırlık (gr):</label>
                            <div id="toplam_agirlik" class="form-control-plaintext font-weight-bold text-primary"></div>
                        </div>

                        <div class="form-group text-center">
                            <label>Nitrik Asit(HNO3):</label>
                            <div id="asit1_miktar" class="form-control-plaintext font-weight-bold text-danger"></div>
                        </div>

                        <div class="form-group text-center">
                            <label>HidroKlorik Asit(Hcl):</label>
                            <div id="asit2_miktar" class="form-control-plaintext font-weight-bold text-danger"></div>
                        </div>
                    </div>

                    <!-- SAĞ SÜTUN -->
                    <div class="col-md-6">
                        <div class="form-group text-center">
                            <label>Girdiğiniz Toplam Ağırlık:</label>
                            <div id="toplam_input_agirlik" class="form-control-plaintext font-weight-bold text-info">0 gr</div>
                        </div>

                        <div class="form-group text-center">
                            <label>Fire Miktarı:</label>
                            <div id="fire_miktar" class="form-control-plaintext font-weight-bold text-danger">0 gr</div>
                        </div>
                    </div>
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


        // Seçilen takozların toplam ağırlığını hesapla
        let toplamAgirlik = 0;

        document.querySelectorAll('.select-row:checked').forEach(cb => {
            const agirlik = parseFloat(cb.getAttribute('data-agirlik')) || 0;
            toplamAgirlik += agirlik;
        });

        const asit1 = toplamAgirlik * 1.5;
        const asit2 = asit1 * 3;

        // Label gibi görünen alanlara yaz
        document.getElementById('toplam_agirlik').textContent = toplamAgirlik.toFixed(2) + ' gr';
        document.getElementById('asit1_miktar').textContent = asit1.toFixed(2) + ' gr';
        document.getElementById('asit2_miktar').textContent = asit2.toFixed(2) + ' gr';


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


    let secilenToplamAgirlik = 0; // Global değişken

    // Güncellenmiş erit-button içindeki toplam ağırlığı kaydet
    document.getElementById('erit-button').addEventListener('click', function() {
        secilenToplamAgirlik = 0;
        document.querySelectorAll('.select-row:checked').forEach(cb => {
            const agirlik = parseFloat(cb.getAttribute('data-has')) || 0;
            secilenToplamAgirlik += agirlik;
        });

        // Güncel label'lara yazma burada zaten var ama fire hesaplaması için tekrar ettik
        document.getElementById('toplam_input_agirlik').textContent = '0 gr';
        document.getElementById('fire_miktar').textContent = secilenToplamAgirlik.toFixed(2) + ' gr';
    });

    // Ağırlık inputları oluşturulduğunda event ekle
    document.getElementById('uret_adet').addEventListener('input', function() {
        setTimeout(() => {
            document.querySelectorAll('input[name="agirlik[]"]').forEach(input => {
                input.addEventListener('input', hesaplaFire);
            });
        }, 100);
    });

    function hesaplaFire() {
        const agirliklar = Array.from(document.querySelectorAll('input[name="agirlik[]"]'))
            .map(input => parseFloat(input.value) || 0);

        const toplamInputAgirlik = agirliklar.reduce((a, b) => a + b, 0);
        const fire = secilenToplamAgirlik - toplamInputAgirlik;

        document.getElementById('toplam_input_agirlik').textContent = toplamInputAgirlik.toFixed(2) + ' gr';
        document.getElementById('fire_miktar').textContent = fire.toFixed(2) + ' gr';
    }
</script>




























<?= view('include/footer') ?>