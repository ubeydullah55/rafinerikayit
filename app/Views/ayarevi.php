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
                            <h4>AYAR EVİ</h4>
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
                    </div>

                    <div class="col-md-6 col-sm-12 d-flex align-items-end justify-content-end">
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <strong>Reaktörde Kalan:</strong> <?= number_format($reaktorToplamFire['miktar'] ?? 0, 2) ?> gr
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
                    <h4 class="text-blue h4">TAKOZLAR</h4>
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
                                <th class="table-plus datatable-nosort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Session'dan role değerini alalım
                            $role = session()->get('role');
                            ?>

                            <?php foreach ($items as $item): ?>
                                <tr <?= ($item['cesni_gram'] > 0) ? 'style="background-color: #e6ffed;"' : ''; ?>>
                                    <td><?= esc($item['id']); ?></td>
                                    <td class="table-plus"><?= esc($item['musteri']); ?></td>
                                    <td><?= number_format(esc($item['giris_gram']), 2); ?> gr</td>
                                    <td><?= esc($item['tahmini_milyem']); ?></td>
                                    <td>
                                        <?php
                                        $gram = !empty($item['islem_goren_miktar']) ? $item['islem_goren_miktar'] : $item['giris_gram'];
                                        echo number_format($gram * ($item['tahmini_milyem'] / 1000), 2);
                                        ?> gr
                                    </td>
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
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> İncele</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Düzenle</a>
                                                <?php if ($item['cesni_gram'] ==  0): ?>
                                                    <a href="#" class="dropdown-item" onclick="openCesniModal(<?= $item['id']; ?>)">
                                                        <i class="dw dw-brightness1"></i> Çeşni Al
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (!empty($item['cesni_gram']) && $item['cesni_gram'] != 0): ?>
                                                    <a href="#" onclick="ilerletTakoz(<?= $item['id']; ?>, '<?= $item['musteri']; ?>')" class="dropdown-item">
                                                        <i class="dw dw-enter-1"></i> İlerlet
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
                                <td style="font-weight:bold;"><?= number_format($totalGram, 2); ?> gr</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>


            <!--#region hurdalar  -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">HURDALAR</h4>
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
                                <th class="table-plus datatable-nosort">Müşteri Notu</th>
                                <th class="table-plus datatable-nosort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Session'dan role değerini alalım
                            $role = session()->get('role');
                            ?>

                            <?php foreach ($hurdalar as $item): ?>
                                <tr>
                                    <td><?= esc($item['id']); ?></td>
                                    <td class="table-plus"><?= esc($item['musteri']); ?></td>
                                    <td><?= number_format(esc($item['giris_gram']), 2); ?> gr</td>
                                    <td><?= esc($item['tahmini_milyem']); ?></td>
                                    <td><?= number_format($item['giris_gram'] * ($item['tahmini_milyem'] / 1000), 2); ?> gr</td>



                                    <td><?= esc($item['musteri_notu']) ?: '-'; ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> İncele</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Düzenle</a>

                                                <a href="#" class="dropdown-item" onclick="openTakozYapModal(<?= $item['id']; ?>)">
                                                    <i class="dw dw-brightness1"></i> Takoz Yap
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
                                <td style="font-weight:bold;"><?= number_format($hurdatotalGram, 2); ?> gr</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

            <!--#endregion -->




            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">HAS TAKOZLAR</h4>
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

                            <?php foreach ($hastakozlar as $item): ?>
                                <tr <?= ($item['cesni_gram'] > 0) ? 'style="background-color: #e6ffed;"' : ''; ?>>
                                    <td><?= esc($item['id']); ?></td>
                                    <td class="table-plus"><?= esc($item['musteri']); ?></td>
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
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="1" style="font-weight:bold;">Toplam Gram:</td>
                                <td style="font-weight:bold;"><?= number_format($totalHasTakozGram, 2); ?> gr</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>







            <!--#region çeşniler  -->

            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Çeşniler</h4>
                </div>
                <div class="pb-20">
                    <table
                        class="table hover multiple-select-row data-table-export nowrap">

                        <thead>
                            <tr>
                                <th>Fiş No</th>
                                <th class="table-plus datatable-nosort">Müşteri</th>
                                <th class="table-plus datatable-nosort">Alınan Toplam Çeşni Ağırlığı</th>
                                <th class="table-plus datatable-nosort">İşlem Görmeyen</th>
                                <th class="table-plus datatable-nosort">İşlem Gören</th>
                                <th class="table-plus datatable-nosort">Kalan Has Çeşni</th>
                                <th class="table-plus datatable-nosort">Poşet Toplam Çeşni</th>
                                <th class="table-plus datatable-nosort">Ölçülen Milyem</th>

                                <th class="table-plus datatable-nosort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Session'dan role değerini alalım
                            $role = session()->get('role');
                            ?>

                            <?php foreach ($cesnibilgi as $item): ?>
                                <tr <?= ($item['cesni_has'] > 0) ? 'style="background-color: #e6ffed;"' : ''; ?>>
                                    <td><?= esc($item['fis_no']); ?></td>
                                    <td class="table-plus"><?= esc($item['musteri']); ?></td>
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

                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a href="#" class="dropdown-item" onclick="inceleCesni(<?= $item['id'] ?>)">
                                                    <i class="dw dw-eye"></i> İncele
                                                </a>


                                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Düzenle</a>
                                                <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Sil</a>
                                                <?php if ($item['cesni_has'] ==  0): ?>
                                                    <a href="#" class="dropdown-item" onclick="openKalanCesniModal(<?= $item['fis_no']; ?>, <?= $item['id']; ?>)">
                                                        <i class="dw dw-brightness1"></i> Kalan Gram Gir
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($item['cesni_has'] !=  0): ?>
                                                    <a href="#" onclick="ilerletCesni(<?= $item['id']; ?>, '<?= $item['musteri']; ?>')" class="dropdown-item">
                                                        <i class="dw dw-enter-1"></i> İlerlet
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($item['cesni_has'] != 0): ?>
                                                    <a href="#"
                                                        class="dropdown-item yazdir-btn"
                                                        data-id="<?= $item['id']; ?>"
                                                        data-fis="<?= esc($item['fis_no']); ?>"
                                                        data-islem-gormeyen="<?= esc($item['agirlik'] - ($item['kullanilan'] ?? 0)); ?>"
                                                        data-has="<?= esc($item['cesni_has']); ?>"
                                                        data-total="<?= esc($item['cesni_has'] + ($item['agirlik'] - ($item['kullanilan'] ?? 0))); ?>"
                                                        data-musteri="<?= esc($item['musteri']); ?>">
                                                        <i class="dw dw-print"></i> Yazdır
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
                                <td style="font-weight:bold;"><?= number_format($totalCesni, 2); ?> gr</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

            <!--#endregion -->

        </div>




        <div class="footer-wrap pd-20 mb-20 card-box">
            Creadet By
            <a href="https://github.com/ubeydullah55" target="_blank">Ubeydullah Doğan</a>
        </div>
    </div>
</div>



<!-- Takoz Yap Modal -->
<div class="modal fade" id="openTakozYapModal" tabindex="-1" role="dialog" aria-labelledby="takozYapLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="takozForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Takoz Yap</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Gizli ID -->
                    <input type="hidden" id="takoz_hurda_id" name="hurda_id">

                    <!-- Adet Girişi -->
                    <div class="form-group">
                        <label for="adet">Takoz Adeti</label>
                        <input type="number" class="form-control" id="takoz_adet" name="adet" min="1" required>
                    </div>

                    <!-- Dinamik Alan: Her takozun ağırlığı -->
                    <div id="takoz_agirlik_alani"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                </div>
            </div>
        </form>
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
                    <input type="hidden" id="tableId" name="tableid">
                    <div class="form-group">
                        <label for="kullanilancesniGram">Kullanılan Çeşni Gramı</label>
                        <input type="number" step="0.0001" min="0" class="form-control" id="kullanilancesniGram" name="kullanilan_cesni_gram" required>
                    </div>
                    <div class="form-group">
                        <label for="cesniGram">Kalan Çeşni Gramı</label>
                        <input type="number" step="0.0001" min="0" class="form-control" id="kalancesniGram" name="kalan_cesni_gram" required>
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


<div class="modal fade" id="takozInceleModal" tabindex="-1" role="dialog" aria-labelledby="takozInceleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Takoz Detayları</h5>
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
    function openKalanCesniModal(id, tableId) {
        document.getElementById('cesniId').value = id;
        document.getElementById('tableId').value = tableId;
        document.getElementById('cesniGram').value = '';
        document.getElementById('kullanilancesniGram').value = '';
        $('#kalancesniModal').modal('show');
    }

    document.getElementById('kalancesniForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('cesniId').value;
        const tableId = document.getElementById('tableId').value;
        const kullanilangram = parseFloat(document.getElementById('kullanilancesniGram').value);
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
                    tableid: tableId,
                    kullanilan_gram: kullanilangram,
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


<!-- ÇEŞNİ İLERLETME -->
<script>
    function ilerletCesni(id, musteri) {
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
                fetch('<?= base_url('cesni/ilerletCesniAjax/'); ?>' + id, {
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
    function openTakozYapModal(hurdaId) {
        // Hurda ID'yi input'a ata
        document.getElementById('takoz_hurda_id').value = hurdaId;
        document.getElementById('takoz_adet').value = "";
        document.getElementById('takoz_agirlik_alani').innerHTML = "";

        $('#openTakozYapModal').modal('show');
    }

    // Adet girildiğinde dinamik olarak input alanları oluştur
    document.getElementById('takoz_adet').addEventListener('input', function() {
        const adet = parseInt(this.value);
        const container = document.getElementById('takoz_agirlik_alani');
        container.innerHTML = "";

        if (adet > 0) {
            for (let i = 1; i <= adet; i++) {
                container.innerHTML += `
          <div class="form-group">
            <label for="takoz_${i}">Takoz ${i} Ağırlığı (gr)</label>
            <input type="number" step="0.01" class="form-control" name="takoz_agirlik[]" required>
          </div>
        `;
            }
        }
    });

    // Form gönderme işlemi
    document.getElementById('takozForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('<?= base_url('takozHurda/hurdaTakozYap'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Takozlar başarıyla kaydedildi!");
                    $('#openTakozYapModal').modal('hide');
                    location.reload(); // Sayfayı yenile
                } else {
                    alert("Hata oluştu: " + data.message);
                }
            })
            .catch(err => {
                alert("Bir hata oluştu.");
                console.error(err);
            });
    });
</script>


<script>
    function inceleCesni(id) {
        // Modalı aç
        $('#cesniInceleModal').modal('show');
        document.getElementById('cesniInceleContent').innerHTML = '<div class="text-center">Yükleniyor...</div>';

        fetch('<?= base_url('cesni/incele') ?>', {
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
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".yazdir-btn").forEach(function(btn) {
            btn.addEventListener("click", function(e) {
                e.preventDefault();

                const id = btn.dataset.id;
                const fis = btn.dataset.fis;
                const islemGormeyen = btn.dataset.islemGormeyen;
                const has = btn.dataset.has;
                const total = btn.dataset.total;
                const musteri = btn.dataset.musteri;

                const printWindow = window.open('', '', 'width=300,height=600');

                const icerik = `
                <html>
                <head>
                    <title>Çeşni Yazdır</title>
                    <style>
                        @media print {
                            @page {
                                size: 58mm auto;
                                margin: 0;
                            }
                            body {
                                margin: 0;
                                font-family: monospace;
                                font-size: 12px;
                                width: 58mm;
                            }
                        }

                        body {
                            font-family: monospace;
                            padding: 10px;
                        }

                        .fis {
                            text-align: center;
                            border-top: 1px dashed #000;
                            border-bottom: 1px dashed #000;
                            padding: 10px 0;
                        }

                        .fis p {
                            margin: 4px 0;
                        }
                    </style>
                </head>
                <body>
                    <div class="fis">
                        <p><strong>SAMSUN ALTIN RAFİNERİ</strong></p>
                        <p><strong>Çeşni Poşeti Fişi</strong></p>
                        <p><strong>Fiş No:</strong> ${fis}</p>
                        <p><strong>Müşteri:</strong> ${musteri}</p>
                        <p><strong>İşlem Görmeyen:</strong> ${islemGormeyen} gr</p>
                        <p><strong>Has:</strong> ${has} gr</p>
                        <p><strong>Toplam:</strong> ${total} gr</p>
                        <p><strong>Tarih:</strong> ${new Date().toLocaleString()}</p>
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                        };
                        window.addEventListener('afterprint', function() {
                            window.close();
                        });
                    <\/script>
                </body>
                </html>
            `;

                printWindow.document.open();
                printWindow.document.write(icerik);
                printWindow.document.close();
            });
        });
    });
</script>



<?= view('include/footer') ?>