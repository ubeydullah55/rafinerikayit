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
                <div class="pd-20" style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h4 class="text-blue h4">AYAR BAKILANLAR CESNİ</h4>

                        <div style="margin-top: 5px;">
                            <span style="display:inline-block;width:15px;height:15px;background-color:#d9d9d9;margin-right:5px;border:1px solid #ccc;"></span>
                            Tıklanmıyorsa: Altın veya gümüş ayarı girilmemiş
                        </div>
                        <div style="margin-top: 5px;">
                            <span style="display:inline-block;width:15px;height:15px;background-color:#ffe6f2;margin-right:5px;border:1px solid #ccc;"></span>
                            Henüz daha merkez stoğa çekilmemişse
                        </div>
                        <div style="margin-top: 5px;">
                            <span style="display:inline-block;width:15px;height:15px;background-color:#e6f3ff;margin-right:5px;border:1px solid #ccc;"></span>
                            Merkeze ulaşmışsa
                        </div>
                    </div>

                    <!-- Sağ tarafa buton -->
                    <div>
                        <form action="<?= site_url('ayarBakma') ?>" method="get">
                            <button type="submit" class="btn btn-primary">Takoz Listesi</button>
                        </form>
                    </div>
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

                                <th class="table-plus datatable-nosort">Fiş No</th>
                                <th class="table-plus datatable-nosort">Müşteri</th>
                                <th class="table-plus datatable-nosort">Alınan Toplam Çeşni Ağırlığı</th>
                                <th class="table-plus datatable-nosort">İşlem Görmeyen</th>
                                <th class="table-plus datatable-nosort">İşlem Gören</th>
                                <th class="table-plus datatable-nosort">Kalan Has Çeşni</th>
                                <th class="table-plus datatable-nosort">Poşet Toplam Çeşni</th>
                                <th class="table-plus datatable-nosort">Ölçülen Milyem</th>
                                <th class="table-plus datatable-nosort">Gümüş Milyem</th>

                                <th class="table-plus datatable-nosort">Action</th>
                            </tr>

                        </thead>
                        <tbody>

                            <?php foreach ($items as $item): ?>
                                <?php
                                $pasif = ($item['olculen_milyem'] <= 0 || $item['gumus_milyem'] === null);

                                if (isset($item['cesni_status_code'])) {

                                    if ($item['cesni_status_code'] == 2) {
                                        // Pembe
                                        $rowBg = 'background-color: #ffe6f2;';
                                    } elseif ($item['cesni_status_code'] == 3) {
                                        // Yeşilimsi
                                        $rowBg = 'background-color: #e6f3ff;';
                                    } else {
                                        // Diğer durumlarda renk yok
                                        $rowBg = '';
                                    }
                                } else {
                                    $rowBg = '';
                                }
                                ?>
                                <tr
                                    data-tarih="<?= esc(date('Y-m-d', strtotime($item['created_date']))) ?>"
                                    class="<?= $pasif ? 'text-muted bg-light' : '' ?>"
                                    style="<?= $pasif ? 'opacity:0.6; pointer-events:none;' : '' ?> <?= $rowBg ?>">
                                    <td><?= esc($item['seri_no']); ?></td>
                                    <td class="table-plus"><?= esc($item['musteri_adi']); ?></td>
                                    <td><?= esc($item['agirlik']); ?></td>

                                    <td>
                                        <?= esc($item['agirlik'] - ($item['kullanilan'] ?? 0)); ?>
                                    </td>
                                    <td>
                                        <?= ($item['kullanilan'] ?? 0); ?>
                                    </td>
                                    <td><?= esc($item['cesni_has']); ?></td>
                                    <td>
                                        <?= esc($item['cesni_has'] + ($item['agirlik'] - ($item['kullanilan'] ?? 0))); ?>
                                    </td>
                                    <td><?= esc($item['olculen_milyem']); ?></td>
                                    <td><?= esc($item['gumus_milyem']); ?></td>

                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">


                                                <?php if (!in_array($item['cesni_status_code'], [1, 3])): ?>
                                                    <a class="dropdown-item" href="#"
                                                        onclick="onaylaCesni(<?= $item['cesni_id']; ?>)">
                                                        <i class="dw dw-checked"></i> Onayla
                                                    </a>

                                                    <a class="dropdown-item" href="#"
                                                        onclick="onaylaCesniGeri(<?= $item['cesni_id']; ?>)">
                                                        <i class="dw dw-return"></i> Çeşni Rafineriye Kalsın
                                                    </a>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>




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





<!-- Hesapla Modal -->
<div class="modal fade" id="hesaplaModal" tabindex="-1" role="dialog" aria-labelledby="hesaplaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hesaplaModalLabel">Ölçüm Hası</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Toplam Ağırlık:</strong> <span id="agirlikDegeri"></span> gr</p>
                <p><strong>Milyem:</strong> <span id="milyemDegeri"></span></p>
                <p><strong>Has Altın (gr):</strong> <span id="hasDegeri"></span></p>
                <p><strong>Gümüş (gr):</strong> <span id="gumusGram"></span></p>

                <div class="form-group">
                    <label>Oran %</label>
                    <input type="number" step="0.0001" class="form-control" id="oranInput" oninput="yenidenHesapla()">
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Gram Altın Fiyatı (₺)</label>
                        <input type="number" step="0.01" class="form-control" id="fiyatInput" oninput="yenidenHesapla()">
                    </div>

                    <div class="col-md-6">
                        <label>Gümüş Fiyatı (₺)</label>
                        <input type="number" step="0.01" class="form-control" id="gumusFiyatInput" oninput="yenidenHesapla()">
                    </div>
                </div>



                <p><strong>Has Geri Verilecek:</strong> <span id="hasGeriVerilecek"></span> gram</p>
                <p><strong>Has Bedel (Oranlı Tutar):</strong> <span id="hasBedel"></span> gram</p>
                <p><strong>TL Karşılığı (Alınacak):</strong> <span id="tlKarsilik"></span> ₺</p>
                <div class="form-group mt-3">
                    <label for="gumusSecim"><strong>Gümüş İşlem Şekli</strong></label>
                    <select id="gumusSecim" class="form-control" onchange="yenidenHesapla()">
                        <option value="0" selected>Gümüş ayrı verilecek</option>
                        <option value="1">Gümüş altına eklenecek</option>
                        <option value="2">Gümüş TL olarak verilecek</option>
                    </select>
                </div>

            </div>



            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>






















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



<script>
    function onaylaCesni(cesni_id) {
        $.ajax({
            url: "<?= base_url('ayarBakma/onaylaCesni') ?>",
            method: "POST",
            data: {
                cesni_id: cesni_id
            },
            success: function(response) {
                // İstersen refresh de atarsın
                location.reload();
            },
            error: function() {
                alert("Bir hata oluştu");
            }
        });
    }
</script>

<script>
    function onaylaCesniGeri(cesni_id) {
        $.ajax({
            url: "<?= base_url('ayarBakma/geriCesni') ?>",
            method: "POST",
            data: {
                cesni_id: cesni_id
            },
            success: function(response) {
                // İstersen refresh de atarsın
                location.reload();
            },
            error: function() {
                alert("Bir hata oluştu");
            }
        });
    }
</script>



<?= view('include/footer') ?>