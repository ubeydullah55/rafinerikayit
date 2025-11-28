<?= view('include/header') ?>
<?= view('include/leftmenu') ?>

<div class="main-container">

    <div class="card p-3 shadow-sm mb-4">
        <label for="stokSecim" class="form-label"><strong>İşlem Türü Seçin:</strong></label>
        <select id="stokSecim" class="form-control w-50">
            <option value="">-- Seçiniz --</option>
            <option value="mal_kabul">Mal Kabul</option>
            <option value="ifraz">Rafineri</option>
            <option value="ayar_evi">Çeşni</option>
            <option value="reaktor">Reaktörden Artan</option>
        </select>
    </div>

    <!-- ✅ Mal Kabul Tablosu -->
    <div id="mal_kabul" class="stok-tablosu" style="display:none;">
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">MAL KABUL</h4>
            </div>
            <div class="pb-20">
                <table class="table hover multiple-select-row data-table-export nowrap">
                    <thead>
                        <tr>
                            <th>Fiş No</th>
                            <th>Tür</th>
                            <th>Müşteri</th>
                            <th>Giriş Gram</th>
                            <th>Tahmini Milyem</th>
                            <th>Oluşturan</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($malKabulHurda) && empty($malKabulTakoz)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Kayıt bulunamadı</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($malKabulHurda as $item): ?>
                            <tr>
                                <td><?= esc($item['id']); ?></td>
                                <td><span class="badge bg-secondary">Hurda</span></td>
                                <td><?= esc($item['musteri_adi']); ?></td>
                                <td><?= number_format($item['giris_gram'], 2); ?> gr</td>
                                <td><?= esc($item['tahmini_milyem']); ?></td>
                                <td><?= esc($item['created_user']); ?></td>
                                <td><?= esc($item['created_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($malKabulTakoz as $item): ?>
                            <tr>
                                <td><?= esc($item['id']); ?></td>
                                <td><span class="badge bg-info">Takoz</span></td>
                                <td><?= esc($item['musteri_adi']); ?></td>
                                <td><?= number_format($item['giris_gram'], 2); ?> gr</td>
                                <td><?= esc($item['tahmini_milyem']); ?></td>
                                <td><?= esc($item['created_user']); ?></td>
                                <td><?= esc($item['created_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded shadow-sm">
                    <div>
                        <h6 class="mb-0 text-secondary">
                            <i class="dw dw-gem text-warning"></i>
                            <strong>Toplam Miktar:</strong>
                            <br>
                            <span class="text-success"><?= number_format($totalMalKabul, 2) ?> gr</span>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ İfraz Tablosu -->
    <div id="ifraz" class="stok-tablosu" style="display:none;">
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">RAFİNERİ STOKLARI</h4>
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

                            <th class="table-plus datatable-nosort">İşlem Gören Miktar</th>
                            <th class="table-plus datatable-nosort">Alınan Çeşni Ağırlığı</th>
                            <th class="table-plus datatable-nosort">Ölçülen Milyem</th>
                            <th class="table-plus datatable-nosort">Müşteri Notu</th>
                            <th class="table-plus datatable-nosort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Session'dan role değerini alalım
                        $role = session()->get('role');
                        ?>

                        <?php foreach ($ifrazTakoz as $item): ?>
                            <tr
                                <?php
                                $style = '';

                                // cesni_gram > 0 ise yeşilimsi
                                if (!empty($item['cesni_gram']) && $item['cesni_gram'] > 0) {
                                    $style .= 'background-color: #e6ffed;';
                                }

                                // tur=35 ise pembe (baskın olsun diye sonradan yazdık)
                                if (!empty($item['tur']) && $item['tur'] == 35) {
                                    $style .= 'background-color: #ffe6f2;';
                                }

                                echo !empty($style) ? 'style="' . $style . '"' : '';
                                ?>>
                                <td><?= esc($item['seri_no']); ?></td>
                                <td class="table-plus"><?= esc($item['musteri_adi']); ?></td>
                                <td><?= number_format(esc($item['giris_gram']), 2); ?> gr</td>
                                <td><?= esc($item['tahmini_milyem']); ?></td>
                                <td><?= number_format(esc($item['islem_goren_miktar']), 2); ?></td>
                                <td><?= esc($item['cesni_gram']); ?></td>
                                <td><?= esc($item['olculen_milyem']); ?></td>
                                <td><?= esc($item['musteri_notu']) ?: '-'; ?></td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a href="#" class="dropdown-item" onclick="inceleTakoz(<?= $item['id'] ?>)">
                                                <i class="dw dw-eye"></i> İncele
                                            </a>
                                            <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Düzenle</a>
                                            <?php if ($item['cesni_gram'] ==  0): ?>
                                                <a href="#" class="dropdown-item" onclick="openCesniModal(<?= $item['id']; ?>)">
                                                    <i class="dw dw-brightness1"></i> Çeşni Al
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($item['cesni_gram']) && $item['cesni_gram'] != 0 && $item['olculen_milyem'] != 0): ?>
                                                <a href="#" onclick="ilerletTakoz(<?= $item['id']; ?>, '<?= $item['musteri']; ?>')" class="dropdown-item">
                                                    <i class="dw dw-enter-1"></i> İlerlet
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php foreach ($ifrazHurda as $item): ?>
                            <tr style="background-color: #f1f519ff;">
                                <td><?= esc($item['id']); ?></td>
                                <td class="table-plus"><?= esc($item['musteri_adi']); ?></td>
                                <td><?= number_format(esc($item['giris_gram']), 2); ?> gr</td>
                                <td><?= esc($item['tahmini_milyem']); ?></td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td><?= esc($item['musteri_notu']) ?: '-'; ?></td>
                                <td>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>
    </div>


    <div id="ayar_evi" class="stok-tablosu" style="display:none;">
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">ÇEŞNİ STOKLARI</h4>
            </div>
            <div class="pb-20">
                <table class="table hover multiple-select-row data-table-export nowrap" id="cesniTable">
                    <thead>
                        <tr>
                            <th>Fiş No</th>
                            <th>Müşteri</th>
                            <th>Alınan Toplam Çeşni Ağırlığı</th>
                            <th>İşlem Görmeyen</th>
                            <th>İşlem Gören</th>
                            <th>Kalan Has Çeşni</th>
                            <th>Poşet Toplam Çeşni</th>
                            <th>Ölçülen Milyem</th>
                            <th>Gümüş Milyem</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cesnibilgi as $item): ?>
                            <?php
                            $style = '';
                            if ($item['olculen_milyem'] > 0) {
                                $style = !is_null($item['gumus_milyem']) ? 'background-color:#e6ffed;' : 'background-color:#fff9e6;';
                            }
                            ?>
                            <tr style="<?= $style ?>">
                                <td><?= esc($item['seri_no']); ?></td>
                                <td><?= esc($item['musteri_adi']); ?></td>
                                <td><?= esc($item['agirlik']); ?></td>
                                <td><?= esc($item['agirlik'] - ($item['kullanilan'] ?? 0)); ?></td>
                                <td><?= ($item['kullanilan'] ?? 0); ?></td>
                                <td><?= esc($item['cesni_has']); ?></td>
                                <td><?= esc($item['cesni_has'] + ($item['agirlik'] - ($item['kullanilan'] ?? 0))); ?></td>
                                <td><?= esc($item['olculen_milyem']); ?></td>
                                <td><?= esc($item['gumus_milyem']); ?></td>
                                <td>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded shadow-sm">
                    <div>
                        <h6 class="mb-0 text-secondary">
                            <i class="dw dw-gem text-warning"></i>
                            <strong>Beklenen Toplam Has Miktarları:</strong>
                            <br>
                            <strong>Ayar Bakılmış</strong>
                            <span class="text-success"><?= number_format($totalGercekHas, 3) ?> gr</span>
                            <br>
                            <strong>Ayar Bakılmamış</strong>
                            <span class="text-warning"><?= number_format($cesniAlinmamisHas, 3) ?> gr</span>
                            <br>
                            <strong>Toplam Has:</strong>
                            <span class="text-info"><?= number_format($cesniAlinmamisHas + $totalGercekHas, 3) ?> gr</span>
                        </h6>
                    </div>
                </div>


            </div>

        </div>
    </div>
    <!--#endregion -->

    <!-- ✅ Reaktör Tablosu -->
    <div id="reaktor" class="stok-tablosu" style="display:none;">
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">Reaktör & Takoz Grupları</h4>
            </div>

            <div class="pb-20">
                <table class="table hover nowrap">
                    <thead>
                        <tr>
                            <th>Fiş No</th>
                            <th>Beklenen Has</th>
                            <th>Çıkan karışık miktar</th>
                            <th>Farklı madde</th>
                            <th>Tarih</th>
                            <th>Açıklama</th>
                            <th>İncele</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($reaktorTakoz as $fisNo => $row): ?>
                            <?php $r = $row['reaktor']; ?>

                            <tr>
                                <td><b><?= $r['fis_no'] ?></b></td>
                                <td><?= $r['miktar'] ?></td>
                                <td><?= $r['karisik_fire'] ?></td>
                                <td><?= $r['farkli_madde'] ?></td>
                                <td><?= $r['created_date'] ?></td>
                                <td><?= $r['aciklama'] ?></td>

                                <td style="display:flex; align-items:center; gap:5px; justify-content:left;">
                                    <span class="badge badge-info"><?= count($row['takozlar']) ?></span>
                                    <button class="btn btn-sm btn-light"
                                        style="padding:2px 6px; font-size:12px; border:1px solid #ccc;"
                                        onclick="treeToggle('tree<?= $fisNo ?>')" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr id="tree<?= $fisNo ?>" style="display:none; background:#f8f8f8;">
                                <td colspan="8">
                                    <?php if (count($row['takozlar']) == 0): ?>
                                        <p class="text-danger m-2">Bu fiş için takoz bulunamadı.</p>
                                    <?php else: ?>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Fiş No</th>
                                                    <th>Müşteri</th>
                                                    <th>Giriş Gram</th>
                                                    <th>Tahmini Milyem</th>
                                                    <th>İşlem Gören</th>
                                                    <th>Çeşni Has</th>
                                                    <th>Ölçülen Milyem</th>
                                                    <th>Tür</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($row['takozlar'] as $t): ?>
                                                    <tr>
                                                        <td><?= $t['seri_no'] ?></td>
                                                        <td><?= $t['musteri_adi'] ?></td>
                                                        <td><?= $t['giris_gram'] ?></td>
                                                        <td><?= $t['tahmini_milyem'] ?></td>
                                                        <td><?= $t['islem_goren_miktar'] ?></td>
                                                        <td><?= $t['cesni_has'] ?></td>
                                                        <td><?= $t['olculen_milyem'] ?></td>
                                                        <td><?= $t['tur'] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded shadow-sm">
                    <div>
                        <div class="p-2" style="font-size:14px; line-height:1.4;">
                            <strong>Toplam Beklenen Has:</strong> <?= number_format($reaktortoplamHasMiktar, 2) ?> gr<br>
                            <strong>Toplam Farklı Madde:</strong> <?= number_format($toplamFarkliMadde, 2) ?> gr<br>
                            <strong>Toplam Fiziki Miktar:</strong> <?= number_format($toplamKarisikFire, 2) ?> gr
                            <div>
                                <strong>Toplam Fiziki Miktar:</strong><br>

                                <?php
                                $fizikiSonuc = $toplamKarisikFire - $toplamFarkliMadde;
                                ?>

                                <!-- İşlemi göster -->
                                <?= number_format($toplamKarisikFire, 2) ?> gr
                                − <?= number_format($toplamFarkliMadde, 2) ?> gr
                                = <strong><?= number_format($fizikiSonuc, 2) ?> gr</strong>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- ✅ Ayar Evi Tablosu -->

</div>

<script>
    document.getElementById('stokSecim').addEventListener('change', function() {
        document.querySelectorAll('.stok-tablosu').forEach(el => el.style.display = 'none');
        const secim = this.value;
        if (secim) document.getElementById(secim).style.display = 'block';
    });
</script>

<script>
    function treeToggle(id) {
        let el = document.getElementById(id);
        let btn = event.currentTarget;

        if (el.style.display === "none") {
            el.style.display = "table-row";
            btn.innerHTML = '<i class="fa fa-minus"></i>';
        } else {
            el.style.display = "none";
            btn.innerHTML = '<i class="fa fa-plus"></i>';
        }
    }
</script>

<?= view('include/footer') ?>