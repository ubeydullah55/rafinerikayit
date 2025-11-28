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
                    <h4 class="text-blue h4">Çeşniler</h4>
                </div>
                <div class="pb-20">
                    <table class="table hover multiple-select-row data-table-export nowrap" id="cesniTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th> <!-- Tümünü seç -->
                                <th>Fiş No</th>
                                <th>Müşteri Firma Adı</th>

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
                                if (!empty($item['tur']) && $item['tur'] == 35) {
                                    $style = 'background-color: #ffe6f2;'; // pembe ton
                                } elseif ($item['olculen_milyem'] > 0) {
                                    $style = !is_null($item['gumus_milyem']) ? 'background-color:#e6ffed;' : 'background-color:#fff9e6;';
                                }
                                // Checkbox durumu: ölçülen milyem yoksa veya gümüş milyem null ise disable yap
                                $disabled = ($item['olculen_milyem'] <= 0 || is_null($item['gumus_milyem']) || $item['status_code'] == 2) ? 'disabled' : '';
                                ?>
                                <tr style="<?= $style ?>">
                                    <td>
                                        <input type="checkbox" class="selectCesni" value="<?= $item['id'] ?>" <?= $disabled ?>>
                                    </td>
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
                        <label for="adet">Takoz Ağırlığı</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="cesniTakozGram" name="cesni_takoz_gram" required>
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





<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.selectCesni').forEach(cb => {
            if (!cb.disabled) {
                cb.checked = this.checked;
            }
        });
    });


    document.getElementById('openTakozButton').addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.selectCesni:checked')).map(cb => cb.value);
        if (selected.length === 0) {
            alert('Lütfen en az bir çeşni seçin.');
            return;
        }

        let toplamHas = 0;
        let toplamGercekGram = 0;

        const tbody = selected.map(id => {
            const row = document.querySelector(`.selectCesni[value="${id}"]`).closest('tr');
            const fis = row.cells[1].innerText;
            const musteri = row.cells[2].innerText;

            const agirlik = parseFloat(row.cells[3].innerText) || 0;
            const kullanilmayan = parseFloat(row.cells[4].innerText) || 0;
            const kullanilan = parseFloat(row.cells[5].innerText) || 0;
            const cesniHas = parseFloat(row.cells[6].innerText) || 0;
            const olculenMilyem = parseFloat(row.cells[8].innerText) || 0;

            const kullanilmayanHas = kullanilmayan * (olculenMilyem / 1000);
            const toplamHasSatir = cesniHas + kullanilmayanHas;
            const gercekGramSatir = cesniHas + kullanilmayan;

            toplamHas += toplamHasSatir;
            toplamGercekGram += gercekGramSatir;

            return `
                <tr>
                    <td>${fis}</td>
                    <td>${musteri}</td>
                    <td>${agirlik.toFixed(3)}</td>
                    <td>${kullanilan.toFixed(3)}</td>
                    <td>${cesniHas.toFixed(3)}</td>
                    <td>${kullanilmayan.toFixed(3)}</td>
                    <td>${olculenMilyem.toFixed(2)}</td>
                    <td>${toplamHasSatir.toFixed(3)}</td>
                </tr>`;
        }).join('');

        const ortalamaMilyemNum = toplamGercekGram > 0 ? (toplamHas / toplamGercekGram) * 1000 : 0;
        const ortalamaMilyem = ortalamaMilyemNum.toFixed(2);

        const html = `
            <div class="mt-3 p-2 bg-light rounded">
                <p><strong>Gerçek Kalan Toplam Ağırlık (Takoz):</strong> ${toplamGercekGram.toFixed(2)} gr</p>
                <p><strong>Beklenen Has Altın:</strong> ${toplamHas.toFixed(2)} gr</p>
                <p><strong>Ortalama Beklenen Milyem:</strong> 
                    <span style="color:blue;font-weight:bold;">${ortalamaMilyem}</span>
                </p>
            </div>

            <input type="hidden" id="takoz_ortalama_milyem" value="${ortalamaMilyem}">
            <input type="hidden" id="takoz_beklenen_has" value="${toplamHas.toFixed(2)}"> <!-- 🔹 bu eklendi -->
            <input type="hidden" id="takoz_secilenler" name="secilenler" value='${JSON.stringify(selected)}'>
        `;

        document.querySelector('#openTakozYapModal #takoz_agirlik_alani').innerHTML = html;
        $('#openTakozYapModal').modal('show');
    });


    // 🟢 Kaydet butonuna basıldığında çalışacak kısım
    document.getElementById('takozForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const takozGram = parseFloat(document.getElementById('cesniTakozGram').value) || 0;
        const tahminiMilyem = parseFloat(document.getElementById('takoz_ortalama_milyem').value) || 0;
        const beklenenHas = parseFloat(document.getElementById('takoz_beklenen_has').value) || 0;
        const cesniIDs = JSON.parse(document.getElementById('takoz_secilenler').value);

        if (takozGram <= 0) {
            alert("Lütfen geçerli bir takoz ağırlığı girin!");
            return;
        }

        const formData = new FormData();
        formData.append('giris_agirlik', takozGram);
        formData.append('tahmini_milyem', tahminiMilyem);
        formData.append('beklenen_has', beklenenHas);
        formData.append('cesni_ids', JSON.stringify(cesniIDs)); // 🔹 seçilen ID'ler burada gönderiliyor

        fetch("<?= site_url('cesniTakozView/cesniTakozOlustur') ?>", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(() => {
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