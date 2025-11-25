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


                </div>

            </div>






            <!--#region çeşniler  -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Çeşni Fire Sonuçları</h4>
                </div>

                <div class="pb-20">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Takoz Kodu</th>
                                <th>Takoz Ağırlık</th>
                                <th>Ölçülen Milyem</th>
                                <th>Beklenen Has</th>
                                <th>Çıkan Has</th>
                                <th>Fire</th>
                                <th>Tarih</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($liste as $index => $row): ?>
                                <?php $fire = $row['fire']; ?>

                                <!-- ÜST BAŞLIK -->
                                <tr>
                                    <td style="display:flex;align-items:center;gap:5px;">
                                        <button class="btn btn-sm btn-outline-primary"
                                            onclick="treeToggle('fire<?= $index ?>')"
                                            style="padding:2px 5px;font-size:12px;">
                                            <i class="fa fa-plus"></i>
                                        </button>

                                        <?= $fire['id'] ?>
                                    </td>    
                                                            
                                    <td><?= $fire['takoz_kodu'] ?></td>
                                    <td><?= $fire['giris_gram'] ?></td> 
                                    <td><?= $fire['olculen_milyem'] ?></td> 
                                    <td><?= $fire['beklenen_has'] ?></td>
                                    <td><?= $fire['cikan_has'] ?></td>
                                    <td><b><?= $fire['beklenen_has'] - $fire['cikan_has'] ?></b></td>
                                    <td>
                                        <?= date('d.m.Y H:i', strtotime($fire['created_date'])) ?>
                                    </td>
                                </tr>

                                <!-- ALT DETAY TABLOSU -->
                                <tr id="fire<?= $index ?>" style="display:none; background:#fafafa;">
                                    <td colspan="5">

                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>Müşteri</th>
                                                    <th>ID</th>
                                                    <th>Fiş No</th>
                                                    <th>Alınan Çeşni</th>
                                                    <th>İşlem Görmeyen</th>
                                                    <th>İşlem Gören</th>

                                                    <th>Kalan Has</th>
                                                    <th>Ölçülen Milyem</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php if (count($row['cesniler']) == 0): ?>
                                                    <tr>
                                                        <td colspan="8" class="text-danger">Bu fire için kayıt bulunamadı.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($row['cesniler'] as $c): ?>
                                                        <tr>
                                                            <td><?= $c['musteri_adi'] ?></td>
                                                            <td><?= $c['id'] ?></td>
                                                            <td><?= $c['fis_no'] ?></td>
                                                            <td><?= $c['agirlik'] ?></td>
                                                            <td>
                                                                <?= isset($c['agirlik'], $c['kullanilan']) ? ($c['agirlik'] - $c['kullanilan']) : '-' ?>
                                                            </td>
                                                            <td><?= $c['kullanilan'] ?></td>

                                                            <td><?= $c['cesni_has'] ?? '-' ?></td>
                                                            <td><?= $c['olculen_milyem'] ?? '-' ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>

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