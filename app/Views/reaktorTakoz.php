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
                    <h4 class="text-blue h4">Reaktör & Takoz Grupları</h4>
                </div>

                <div class="pb-20">
                    <table class="table hover nowrap">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="selectAllTakoz"></th>


                                <th>Fiş No</th>
                                <th>Beklenen Has</th>
                                <th>Çıkan karışık miktar</th>
                                <th>Farklı madde</th>
                                <th>Tarih</th>
                                <th>Açıklama</th>
                                <th>İncele</th> <!-- Artı ikonu için boş başlık -->
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($reaktorTakoz as $fisNo => $row): ?>
                                <?php $r = $row['reaktor']; ?>

                                <tr>
                                    <td>
                                        <input type="checkbox" class="selectTakoz" value="<?= $r['id'] ?>">
                                    </td>
                                    <td><b><?= $r['fis_no'] ?></b></td>
                                    <td><?= $r['miktar'] ?></td>
                                    <td><?= $r['karisik_fire'] ?></td>
                                    <td><?= $r['farkli_madde'] ?></td>
                                    <td><?= $r['created_date'] ?></td>
                                    <td><?= $r['aciklama'] ?></td>
                                    <!-- Artı ikonlu buton -->
                                    <td style="display:flex; align-items:center; gap:5px; justify-content:left;">
                                        <span class="badge badge-info"><?= count($row['takozlar']) ?></span>
                                        <button class="btn btn-sm btn-light"
                                            style="padding:2px 6px; font-size:12px; border:1px solid #ccc;"
                                            onclick="treeToggle('tree<?= $fisNo ?>')"
                                            type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Açılır kapanır takoz listesi -->
                                <tr id="tree<?= $fisNo ?>" style="display:none; background:#f8f8f8;">
                                    <td colspan="6">
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
                                                            <td><?= $t['seri_no']  ?></td>
                                                            <td><?= $t['musteri_adi']  ?></td>
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
                         
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm px-4" id="openTakozButton">
                                <i class="dw dw-hammer mr-1"></i> Takoz Yap
                            </button>
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


<!-- Takoz Yap Modal -->
<div class="modal fade" id="openTakozYapModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="takozForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Takoz Yap</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Takoz Ağırlığı</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="cesniTakozGram" name="cesni_takoz_gram" required>
                    </div>
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





<script>
    document.addEventListener("change", function(e) {
        if (e.target.classList.contains("selectAllTakoz")) {

            let table = e.target.closest("table");
            let checkboxes = table.querySelectorAll(".selectTakoz");

            checkboxes.forEach(cb => cb.checked = e.target.checked);
        }
    });

    function getSelectedTakozlar() {
        return Array.from(document.querySelectorAll(".selectTakoz:checked"))
            .map(cb => cb.value);
    }
</script>

<script>
    document.getElementById('openTakozButton').addEventListener('click', function() {
    const selected = Array.from(document.querySelectorAll('.selectTakoz:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('Lütfen en az bir takoz seçin.');
        return;
    }

    let toplamHas = 0;
    let toplamGercekGram = 0;
        let toplamFarkli = 0; // 🔹 farkli toplamı

    const rowsHtml = selected.map(id => {
        const row = document.querySelector(`.selectTakoz[value="${id}"]`).closest('tr');
        const fis = row.cells[1].innerText;
        const beklenenHas = parseFloat(row.cells[2].innerText) || 0;
        const karisik = parseFloat(row.cells[3].innerText) || 0;
        const farkli = parseFloat(row.cells[4].innerText) || 0;

        toplamHas += beklenenHas;
        toplamGercekGram += karisik;
        toplamFarkli += farkli; // 🔹 buraya ekledik

        return `
            <tr>
                <td>${fis}</td>
                <td>${beklenenHas.toFixed(3)}</td>
                <td>${karisik.toFixed(3)}</td>
                <td>${farkli.toFixed(3)}</td>
            </tr>
        `;
    }).join('');

    document.getElementById('takoz_agirlik_alani').innerHTML = `
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fiş No</th>
                    <th>Beklenen Has</th>
                    <th>Çıkan Karışık</th>
                    <th>Farklı Madde</th>
                </tr>
            </thead>
            <tbody>${rowsHtml}</tbody>
        </table>
        <input type="hidden" id="takoz_toplam_has" value="${toplamHas}">
        <input type="hidden" id="takoz_toplam_karisik" value="${toplamGercekGram}">
        <input type="hidden" id="takoz_secilenler" value='${JSON.stringify(selected)}'>
        <input type="hidden" id="takoz_toplam_farkli" value="${toplamFarkli}"> <!-- 🔹 Bunu ekledik -->
    `;

    $('#openTakozYapModal').modal('show');
});



document.getElementById('takozForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const takozGram = parseFloat(document.getElementById('cesniTakozGram').value) || 0;
    const secilenler = JSON.parse(document.getElementById('takoz_secilenler').value);
    const toplamHas = parseFloat(document.getElementById('takoz_toplam_has').value);
    const toplamFarkli = parseFloat(document.getElementById('takoz_toplam_farkli').value); // 🔹 farkli alındı

    if (takozGram <= 0) {
        alert("Lütfen geçerli bir takoz ağırlığı girin!");
        return;
    }

    const formData = new FormData();
    formData.append('takoz_gram', takozGram);
    formData.append('toplam_has', toplamHas);
    formData.append('toplam_farkli', toplamFarkli); // 🔹 farkli eklendi
    formData.append('secilenler', JSON.stringify(secilenler));

    fetch("<?= site_url('reaktorTakozView/reaktorTakozOlustur') ?>", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert("Takoz başarıyla oluşturuldu!");
        location.reload();
    })
    .catch(err => {
        console.error(err);
        alert("Bir hata oluştu!");
    });
});

</script>




<?= view('include/footer') ?>