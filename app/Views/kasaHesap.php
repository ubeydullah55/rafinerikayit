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
                                <th class="table-plus datatable-nosort">Action</th>
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
                                    <td class="table-plus"><?= esc($item['musteri_adi']); ?></td>
                                    <td><?= number_format(esc($item['giris_gram']), 2); ?> gr</td>
                                    <td><?= esc($item['tahmini_milyem']); ?></td>
                                    <td><?= number_format($item['giris_gram'] * ($item['tahmini_milyem'] / 1000), 2); ?> gr</td>
                                    <td><?= esc($item['islem_goren_miktar']); ?></td>
                                    <td><?= esc($item['cesni_gram']); ?></td>
                                    <td><?= esc($item['olculen_milyem']); ?></td>
                                    <td><?= esc($item['musteri_notu']) ?: '-'; ?></td>
                                    <td><?= number_format($item['giris_gram'] * ($item['olculen_milyem'] / 1000), 2); ?> gr</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a href="#" class="dropdown-item" onclick="inceleTakoz(<?= $item['id'] ?>)">
                                                    <i class="dw dw-eye"></i> İncele
                                                </a>
                                                <a class="dropdown-item" href="#"
                                                    onclick="hesaplaModalAc('<?= $item['giris_gram']; ?>', '<?= $item['olculen_milyem']; ?>', '<?= $item['oran'] ?? '' ?>')">
                                                    <i class="dw dw-edit2"></i> Hesapla
                                                </a>




                                            </div>
                                        </div>
                                    </td>
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

                <div class="form-group">
                    <label>Oran %</label>
                    <input type="number" step="0.0001" class="form-control" id="oranInput" oninput="yenidenHesapla()">
                </div>

                <div class="form-group">
                    <label>Gram Altın Fiyatı (₺)</label>
                    <input type="number" step="0.01" class="form-control" id="fiyatInput" oninput="yenidenHesapla()">
                </div>

                <p><strong>Has Bedel (Oranlı Tutar):</strong> <span id="hasBedel"></span> gram</p>
                <p><strong>TL Karşılığı (Güncel Altın Fiyatı):</strong> <span id="tlKarsilik"></span> ₺</p>
            </div>



            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>






<script>
    function inceleTakoz(id) {
        // Modalı aç
        $('#cesniInceleModal').modal('show');
        document.getElementById('cesniInceleContent').innerHTML = '<div class="text-center">Yükleniyor...</div>';

        fetch('<?= base_url('takoz/incele') ?>', {
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
let globalHasAltin = 0;

function hesaplaModalAc(agirlik, milyem, oran = '') {
    let agirlikFloat = parseFloat(agirlik);
    let milyemFloat = parseFloat(milyem);
    let hasAltin = (agirlikFloat * milyemFloat) / 1000;
    globalHasAltin = hasAltin;

    // Yazıları yerleştir
    document.getElementById("agirlikDegeri").innerText = agirlik;
    document.getElementById("milyemDegeri").innerText = milyem;
    document.getElementById("hasDegeri").innerText = hasAltin.toFixed(2);

    // Oranı yerleştir (boş olabilir)
    document.getElementById("oranInput").value = oran || '';

    // Fiyatı boşalt ve tekrar çek
    document.getElementById("fiyatInput").value = '';
    document.getElementById("hasBedel").innerText = '';
    document.getElementById("tlKarsilik").innerText = '';

    fetch('https://finans.truncgil.com/v4/today.json')
        .then(res => res.json())
        .then(data => {
            if (data["GRA"] && data["GRA"]["Buying"]) {
                let fiyat = parseFloat(data["GRA"]["Buying"]);
                document.getElementById("fiyatInput").value = fiyat.toFixed(2);
                yenidenHesapla();
            }
        });

    $('#hesaplaModal').modal('show');
}

function yenidenHesapla() {
    let oran = parseFloat(document.getElementById("oranInput").value.replace(",", "."));
    let fiyat = parseFloat(document.getElementById("fiyatInput").value.replace(",", "."));

    let hasBedel = isNaN(oran) ? 0 : globalHasAltin * oran;
    let tlKarsilik = isNaN(fiyat) ? 0 : hasBedel * fiyat; 

    document.getElementById("hasBedel").innerText = hasBedel.toFixed(2);
    document.getElementById("tlKarsilik").innerText = tlKarsilik.toFixed(2);
}

</script>

























<?= view('include/footer') ?>