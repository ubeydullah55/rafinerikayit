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
                    <h4 class="text-blue h4">Reaktör Takoz Sonuçları</h4>
                </div>

                <div class="pb-20">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Takoz Kodu</th>
                                <th>Beklenen Has</th>
                                <th>Farklı Madde</th>
                                <th>Çıkan Has</th>
                                <th>Toplam Fire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reaktorTakoz as $fireID => $row): ?>
                                <?php $rf = $row['reaktor_fire']; ?>
                                <tr>
                                    <td style="display:flex; align-items:center; gap:5px;">
                                        <button class="btn btn-sm btn-outline-primary" style="padding:2px 6px; font-size:12px;"
                                            onclick="treeToggle('reaktor<?= $fireID ?>')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <?= $rf['fire_id'] ?>
                                    </td>
                                    <td><?= $rf['takoz_kodu'] ?></td>
                                    <td><?= $rf['beklenen_has'] ?></td>
                                    <td><?= $rf['farkli_madde'] ?></td>
                                    <td><?= $rf['cikan_has'] ?></td>
                                    <td><b><?= $rf['beklenen_has'] - $rf['cikan_has'] ?></b></td>
                                </tr>

                                <!-- Reaktor Detayları -->
                                <tr id="reaktor<?= $fireID ?>" style="display:none; background:#f8f9fa;">
                                    <td colspan="6">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Grup No</th>
                                                    <th>Eksik Halan Has<sub>(999.9)</sub></th>
                                                    <th>Reaktörden Çıkan <sub>(Fiziki)</sub></th>
                                                    <th>Farklı Madde<sub>(Yay vb.)</sub></th>
                                                    <th>Açıklama</th>
                                                    <th>Tarih</th>
                                                    <th>Kullanıcı</th>
                                                    <th>+</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($row['reaktorlar'] as $r): ?>
                                                    <tr>
                                                        <td><?= $r['id'] ?></td>
                                                        <td><?= $r['fis_no'] ?></td>
                                                        <td><?= $r['miktar'] ?></td>
                                                        <td><?= $r['karisik_fire'] ?></td>
                                                        <td><?= $r['farkli_madde'] ?></td>
                                                        <td><?= $r['aciklama'] ?></td>
                                                        <td><?= $r['created_date'] ?></td>
                                                        <td><?= $r['created_user'] ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-secondary" style="padding:1px 5px; font-size:11px;"
                                                                onclick="treeToggle('takoz<?= $r['id'] ?>')">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <!-- Takozlar -->
                                                    <tr id="takoz<?= $r['id'] ?>" style="display:none; background:#fdfdfe;">
                                                        <td colspan="9">
                                                            <?php if (count($r['takozlar']) == 0): ?>
                                                                <p class="text-danger m-1">Bu reaktör için takoz bulunamadı.</p>
                                                            <?php else: ?>
                                                                <table class="table table-sm table-bordered mb-0">
                                                                    <thead class="table-light">
                                                                        <tr>
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
                                                                        <?php foreach ($r['takozlar'] as $t): ?>
                                                                            <tr>
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
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>




                    <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded shadow-sm">
                        <div>

                        </div>
                        <div>
                        </div>
                    </div>
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