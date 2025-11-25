<?= view('include/header') ?>
<?= view('include/leftmenu') ?>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">

            <div class="page-header">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success" style="text-align:center;">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger" style="text-align:center;">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">İŞLENMİŞ FİRE TAKİP</h4>
                </div>

                <div class="pb-20">
                    <div class="pd-20 d-flex justify-content-end align-items-center gap-2">
                        <label for="baslangic_tarih" class="mb-0 mr-2">Başlangıç:</label>
                        <input type="date" id="baslangic_tarih" class="form-control" style="width:200px;">

                        <label for="bitis_tarih" class="mb-0 ml-3 mr-2">Bitiş:</label>
                        <input type="date" id="bitis_tarih" class="form-control" style="width:200px;">

                        <button class="btn btn-primary ml-3" onclick="filtreleTablo()">Filtrele</button>
                        <button class="btn btn-outline-secondary ml-2" onclick="temizleFiltre()">Temizle</button>
                    </div>

                    <table class="table data-table-export">
                        <thead>
                            <tr>
                                <th>Fiş No</th>
                                <th>Müşteri</th>
                                <th>Takoz Ağırlığı</th>
                                <th>Tahmini Milyem</th>
                                <th>Tahmini Has</th>
                                <th>İşlem Gören Miktar</th>
                                <th>Çeşni</th>
                                <th>Ölçülen Milyem</th>
                                <th>Ölçüm Has</th>
                                <th>Au Milyem</th>
                                <th>Au Ağırlık</th>
                                <th>Not</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($gruplar as $grupKodu => $grup): ?>
                                <!-- ÜST GRUP SATIRI -->
                                <tr class="table-info" style="cursor:pointer;"
                                    onclick="toggleGroup('group-<?= $grupKodu ?>')"
                                    data-tarih="<?= esc($grup['ust']['created_date']) ?>">
                                    <td colspan="13">
                                        <strong id="icon-group-<?= $grupKodu ?>">[+]</strong>
                                        <strong> ► Grup Kodu: <?= esc($grupKodu) ?></strong>
                                        <span style="margin-left:15px;">Fire Miktarı: <b><?= esc($grup['ust']['miktar']) ?> gr</b></span>
                                        <span style="float:right;">
                                            Oluşturulma Tarihi: <b><?= date('d-m-Y H:i', strtotime($grup['ust']['created_date'])) ?></b>
                                        </span>
                                    </td>
                                </tr>

                                <!-- ALT KAYITLAR -->
                                <?php foreach ($grup['alt'] as $item): ?>
                                    <?php
                                    $pasif = ($item['olculen_milyem'] <= 0 || $item['gumus_milyem'] === null);
                                    $status6 = ($item['status_code'] == 6);
                                    $rowClass = '';
                                    $rowStyle = '';

                                    if ($status6) {
                                        $rowClass = 'bg-secondary text-white';
                                    } elseif ($pasif) {
                                        $rowClass = 'text-muted bg-light';
                                        $rowStyle = 'opacity:0.6;';
                                    }
                                    ?>
                                    <tr class="<?= $rowClass ?> group-row group-<?= $grupKodu ?>" style="<?= $rowStyle ?>; display:none;">
                                        <td><?= esc($item['id']) ?></td>
                                        <td><?= esc($item['musteri_adi']) ?></td>
                                        <td><?= number_format($item['giris_gram'], 2) ?> gr</td>
                                        <td><?= esc($item['tahmini_milyem']) ?></td>
                                        <td><?= number_format($item['giris_gram'] * ($item['tahmini_milyem'] / 1000), 2) ?> gr</td>
                                        <td><?= esc($item['islem_goren_miktar']) ?></td>
                                        <td><?= esc($item['cesni_gram']) ?></td>
                                        <td><?= esc($item['olculen_milyem']) ?></td>
                                        <td><?= number_format($item['giris_gram'] * ($item['olculen_milyem'] / 1000), 2) ?> gr</td>
                                        <td><?= esc($item['gumus_milyem']) ?></td>
                                        <td><?= number_format($item['giris_gram'] * $item['gumus_milyem'], 2) ?> gr</td>
                                        <td><?= esc($item['musteri_notu']) ?: '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>



                </div>
            </div>

            <div class="footer-wrap pd-20 mb-20 card-box">
                Created By <a href="https://github.com/ubeydullah55" target="_blank">Ubeydullah Doğan</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cesniInceleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">İşlem Geçmiş Detayları</h5>
                <button class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="cesniInceleContent">
                <div class="text-center">Yükleniyor...</div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleGroup(groupClass) {
        const rows = document.querySelectorAll('.' + groupClass);
        const icon = document.getElementById('icon-' + groupClass);
        let isHidden = rows[0].style.display === 'none';

        rows.forEach(row => row.style.display = isHidden ? '' : 'none');

        icon.textContent = isHidden ? '[-]' : '[+]';
    }
</script>

<script>
    function inceleTakoz(id) {
        $('#cesniInceleModal').modal('show');
        document.getElementById('cesniInceleContent').innerHTML = '<div class="text-center">Yükleniyor...</div>';

        fetch('<?= base_url('takoz/inceleKasa') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id
                })
            })
            .then(r => r.text())
            .then(html => document.getElementById('cesniInceleContent').innerHTML = html)
            .catch(err => {
                console.error(err);
                document.getElementById('cesniInceleContent').innerHTML =
                    '<div class="text-danger">Sunucu hatası.</div>';
            });
    }

    document.addEventListener("DOMContentLoaded", () => {
        const today = new Date();
        const yesterday = new Date();
        yesterday.setDate(today.getDate() - 1);

        const format = d => d.toISOString().split('T')[0];

        document.getElementById("baslangic_tarih").value = format(yesterday);
        document.getElementById("bitis_tarih").value = format(today);

        filtreleTablo();
    });

    function filtreleTablo() {
        const bas = document.getElementById("baslangic_tarih").value;
        const bit = document.getElementById("bitis_tarih").value;

        const basT = bas ? new Date(bas + "T00:00:00") : null;
        const bitT = bit ? new Date(bit + "T23:59:59") : null;

        let toplam = 0;
        document.querySelectorAll("table tbody tr").forEach(row => {
            const tarih = row.getAttribute("data-tarih");
            if (!tarih) return;

            const fis = new Date(tarih);
            const goster = (!basT || fis >= basT) && (!bitT || fis <= bitT);

            row.style.display = goster ? "" : "none";

            if (goster) {
                const t = row.querySelectorAll("td")[2];
                if (t) {
                    const v = parseFloat(t.textContent.replace("gr", "").replace(",", "."));
                    if (!isNaN(v)) toplam += v;
                }
            }
        });

        document.getElementById("toplam_gram").textContent =
            toplam.toLocaleString("tr-TR", {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            }) + " gr";
    }

    function temizleFiltre() {
        document.getElementById("baslangic_tarih").value = "";
        document.getElementById("bitis_tarih").value = "";
        filtreleTablo();
    }
</script>

<?= view('include/footer') ?>