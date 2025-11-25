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
                    <h4 class="text-blue h4">REAKTÖR FİRE TAKİP 2</h4>
                </div>
                <div class="pb-20">
                    <div class="pd-20 d-flex justify-content-end align-items-center gap-2">
                        <label for="baslangic_tarih" class="mb-0 mr-2">Başlangıç:</label>
                        <input type="date" id="baslangic_tarih" class="form-control" style="width: 200px;">

                        <label for="bitis_tarih" class="mb-0 ml-3 mr-2">Bitiş:</label>
                        <input type="date" id="bitis_tarih" class="form-control" style="width: 200px;">

                        <button class="btn btn-primary ml-3" onclick="filtreleTablo()">Filtrele</button>
                        <button class="btn btn-outline-secondary ml-2" onclick="temizleFiltre()">Temizle</button>
                    </div>


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
                                <th class="table-plus datatable-nosort">Çeşni</th>
                                <th class="table-plus datatable-nosort">Ölçülen Milyem</th>
                                <th class="table-plus datatable-nosort">Ölçüm Has</th>
                                <th class="table-plus datatable-nosort">Au Milyem</th>
                                <th class="table-plus datatable-nosort">Au Ağırlık</th>
                                <th class="table-plus datatable-nosort">Not</th>
                                <th class="table-plus datatable-nosort">Action</th>
                            </tr>

                        </thead>
                        <tbody>
                         

                            <!-- Grup kodu > 0 olan kayıtlar -->
                            <?php if (!empty($gruplar)): ?>
                                <?php if (!empty($ustGruplar)): ?>
                                    <?php foreach ($ustGruplar as $reaktorKod => $grupListesi): ?>
                                        <?php $rf = $reaktorFireMap[$reaktorKod] ?? null; ?>
                                        <tr class="table-info">
                                            <td colspan="13">
                                                <strong style="font-size:16px; font-weight:600;">►Reaktor Takoz Numarası: <?= esc($reaktorKod) ?></strong>
                                                <?php if ($rf): ?>
                                                    <span style="margin:0 8px;">•</span>
                                                    <span>Beklenen Has: <?= esc($rf['beklenen_has']) ?> gr</span>
                                                    <span style="margin:0 8px;">•</span>
                                                    <span>Çıkan Has Miktar: <?= esc($rf['cikan_has']) ?> gr</span>
                                                    <span style="margin:0 8px;">•</span>
                                                    <span>Farklı Madde Miktarı: <b style="color:orange;"><?= esc($rf['farkli_madde']) ?> gr</b></span>
                                                    <span style="margin:0 8px;">•</span>
                                                    <span>Fire Miktarı: <b style="color:red;"><?= $rf['beklenen_has'] - $rf['cikan_has'] ?>gr</b></span>

                                                    <?php if (!empty($rf['created_date'])): ?>
                                                        <span style="margin:0 8px;">•</span>
                                                        <span style="float:right; font-weight:600; color:#555;">
                                                            <?= date('d.m.Y H:i', strtotime($rf['created_date'])) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>

                                        </tr>

                                        <!-- Üst grup içindeki grup kodları -->
                                        <?php foreach ($grupListesi as $grupKodu => $itemsGroup): ?>
                                            <?php $rb = $reaktorBilgileri[$grupKodu] ?? null; ?>
                                            <tr class="table-secondary">
                                                <td colspan="13">
                                                    <strong>Grup Kodu: <?= esc($grupKodu) ?></strong>
                                                    <?php if ($rb): ?>
                                                        <span style="margin:0 8px;">•</span>
                                                        <span>Reaktörden Çıkan Miktar:<sub>(Fiziki)</sub>: <?= esc($rb['karisik_fire']) ?> gr</span>
                                                        <span style="margin:0 8px;">•</span>
                                                        <span>Çıkması Gereken Has: <?= esc($rb['miktar']) ?> gr</span>
                                                        <span style="margin:0 8px;">•</span>
                                                        <span>Farklı Madde Miktarı:<sub>(yay vb.)</sub>: <?= esc($rb['farkli_madde']) ?> gr</span>
                                                        <span style="margin:0 8px;">•</span>
                                                        <span>Not: <?= esc($rb['aciklama']) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <!-- Grup içindeki itemlar -->
                                            <?php foreach ($itemsGroup as $item): ?>
                                                <?php
                                                $pasif = ($item['olculen_milyem'] <= 0 || $item['gumus_milyem'] === null);

                                                // Status 5 veya 6 ise gri arkaplan uygulanacak
                                                $statusGri = (isset($item['status_code']) && in_array($item['status_code'], [5, 6]));
                                                ?>
                                                <tr
                                                    class="<?= $pasif ? 'text-muted bg-light' : '' ?> <?= $statusGri ? 'table-secondary' : '' ?>"
                                                    style="<?= $pasif ? 'opacity:0.6; pointer-events:none;' : '' ?>"
                                                    data-tarih="<?=
                                                                !empty($rf['created_date'])
                                                                    ? esc(date('Y-m-d', strtotime($rf['created_date'])))
                                                                    : esc(date('Y-m-d', strtotime($item['created_date'])))
                                                                ?>">
                                                    <td><?= esc($item['id']); ?></td>
                                                    <td><?= esc($item['musteri_adi']); ?></td>
                                                    <td style="text-align:center"><?= number_format($item['giris_gram'], 2); ?> gr</td>
                                                    <td style="text-align:center"><?= esc($item['tahmini_milyem']); ?></td>
                                                    <td style="text-align:center"><?= number_format($item['giris_gram'] * ($item['tahmini_milyem'] / 1000), 2); ?> gr</td>
                                                    <td style="text-align:center"><?= esc($item['islem_goren_miktar']); ?></td>
                                                    <td style="text-align:center"><?= esc($item['cesni_gram']); ?></td>
                                                    <td style="text-align:center"><?= esc($item['olculen_milyem']); ?></td>
                                                    <td style="text-align:center"><?= number_format($item['giris_gram'] * ($item['olculen_milyem'] / 1000), 2); ?> gr</td>
                                                    <td style="text-align:center"><?= esc($item['gumus_milyem']); ?></td>
                                                    <td style="text-align:center"><?= number_format($item['giris_gram'] * $item['gumus_milyem'], 2); ?> gr</td>
                                                    <td style="text-align:center"><?= esc($item['musteri_notu']) ?: '-'; ?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                                <i class="dw dw-more"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                                <a href="#" class="dropdown-item" onclick="inceleTakoz(<?= $item['id'] ?>)">
                                                                    <i class="dw dw-eye"></i> İncele
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                        <?php endforeach; ?>

                                    <?php endforeach; ?>
                                <?php endif; ?>

                            <?php endif; ?>
                        </tbody>


                        <tfoot>
                            <tr>
                                <td colspan="1" style="font-weight:bold;">Toplam Gram:</td>
                                <td style="font-weight:bold;" id="toplam_gram">0.000 gr</td>

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






<!-- Çeşni İncele Modal -->
<div class="modal fade" id="cesniInceleModal" tabindex="-1" role="dialog" aria-labelledby="cesniInceleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">iŞLEM GEÇMİŞ DETAYLARI</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Kapat">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="cesniInceleContent">
                <div class="text-center">Yükleniyor...</div>
            </div>
        </div>
    </div>
</div>












<script>
    function inceleTakoz(id) {
        // Modalı aç
        $('#cesniInceleModal').modal('show');
        document.getElementById('cesniInceleContent').innerHTML = '<div class="text-center">Yükleniyor...</div>';

        fetch('<?= base_url('takoz/inceleKasa') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: id
                })
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('cesniInceleContent').innerHTML = html;
            })
            .catch(error => {
                console.error("Hata:", error);
                document.getElementById('cesniInceleContent').innerHTML = '<div class="text-danger">Sunucu hatası oluştu.</div>';
            });
    }
</script>




















<script>
    document.addEventListener("DOMContentLoaded", function() {
        const today = new Date();
        const yesterday = new Date();
        yesterday.setDate(today.getDate() - 1);

        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = ('0' + (date.getMonth() + 1)).slice(-2);
            const day = ('0' + date.getDate()).slice(-2);
            return `${year}-${month}-${day}`;
        };

        document.getElementById("baslangic_tarih").value = formatDate(yesterday);
        document.getElementById("bitis_tarih").value = formatDate(today);

        filtreleTablo(); // Artık DOM tamamen yüklendikten sonra çalışıyor
    });

    function filtreleTablo() {
        const baslangicInput = document.getElementById("baslangic_tarih").value;
        const bitisInput = document.getElementById("bitis_tarih").value;

        const baslangic = baslangicInput ? new Date(baslangicInput + "T00:00:00") : null;
        const bitis = bitisInput ? new Date(bitisInput + "T23:59:59") : null;

        const rows = document.querySelectorAll("table tbody tr");
        let toplamGram = 0;

        rows.forEach(row => {
            const fisTarihiText = row.getAttribute('data-tarih');
            if (!fisTarihiText) return;

            const fisTarihi = new Date(fisTarihiText);

            const goster =
                (!baslangic || fisTarihi >= baslangic) &&
                (!bitis || fisTarihi <= bitis);

            row.style.display = goster ? "" : "none";

            if (goster) {
                const gramTd = row.querySelectorAll('td')[2]; // 3. kolon: Takoz Ağırlığı
                if (gramTd) {
                    const gramText = gramTd.textContent
                        .replace("gr", "")
                        .trim()
                        .replace(/,/g, ""); // <-- virgülleri temizle

                    const gram = parseFloat(gramText);
                    if (!isNaN(gram)) {
                        toplamGram += gram;
                    }
                }
            }
        });

        document.getElementById("toplam_gram").textContent = toplamGram.toLocaleString('tr-TR', {
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



<script>
    window.addEventListener('DOMContentLoaded', () => {
        const today = new Date();
        const yesterday = new Date();
        yesterday.setDate(today.getDate() - 1);

        const formatDate = (date) => {
            return date.toISOString().split('T')[0];
        };

        document.getElementById('baslangic_tarih').value = formatDate(yesterday);
        document.getElementById('bitis_tarih').value = formatDate(today);
    });
</script>







<?= view('include/footer') ?>