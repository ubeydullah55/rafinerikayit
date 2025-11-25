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
                            <h4>iŞLENECEK</h4>
                        </div>
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
                    <h4 class="text-blue h4">ERİTME</h4>
                </div>
                <div class="pb-20">
                    <table
                        class="table hover multiple-select-row data-table-export nowrap">

                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>Fiş No</th>
                                <th class="table-plus datatable-nosort">Müşteri</th>
                                <th class="table-plus datatable-nosort">Takoz Ağırlığı</th>
                                <th class="table-plus datatable-nosort">Tahmini Milyem</th>
                                <th class="table-plus datatable-nosort">Tahmini Has</th>
                                <th class="table-plus datatable-nosort">İşlem Gören Miktar</th>
                                <th class="table-plus datatable-nosort">Alınan Çeşni Ağırlığı</th>
                                <th class="table-plus datatable-nosort">Ölçülen Milyem</th>
                                <th class="table-plus datatable-nosort">Müşteri Notu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Session'dan role değerini alalım
                            $role = session()->get('role');
                            ?>

                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="select-row" value="<?= $item['id']; ?>" data-agirlik="<?= $item['islem_goren_miktar']; ?>" data-has="<?= $item['islem_goren_miktar'] * ($item['olculen_milyem'] / 1000) ?>" milyem="<?= $item['olculen_milyem']; ?>">
                                    </td>

                                    <td><?= esc($item['id']); ?></td>
                                    <td class="table-plus"><?= esc($item['musteri']); ?></td>
                                    <td><?= number_format(esc($item['giris_gram']), 2); ?> gr</td>
                                    <td><?= esc($item['tahmini_milyem']); ?></td>
                                    <td>
                                        <?php
                                        // Önce gram değerini belirle
                                        $gram = !empty($item['islem_goren_miktar']) ? $item['islem_goren_miktar'] : $item['giris_gram'];

                                        // Ölçülen milyem varsa onu kullan, yoksa tahmini milyem
                                        $milyem = !empty($item['olculen_milyem']) ? $item['olculen_milyem'] : $item['tahmini_milyem'];

                                        // Hesaplama: Saf altın gramı
                                        echo number_format($gram * ($milyem / 1000), 2);
                                        ?> gr
                                    </td>

                                    <td><?= esc($item['islem_goren_miktar']); ?></td>
                                    <td><?= esc($item['cesni_gram']); ?></td>
                                    <td><?= esc($item['olculen_milyem']); ?></td>
                                    <td><?= esc($item['musteri_notu']) ?: '-'; ?></td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="1" style="font-weight:bold;">Toplam Gram:</td>
                                <td style="font-weight:bold;"><?= number_format($totalGram, 2); ?> gr</td>
                                <td colspan="3"><button type="button" id="erit-button" class="btn btn-success">
                                        ERİT
                                    </button></td>
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




<!-- Sadece ağırlık alanları olacak -->
<div class="modal fade" id="uretTakozModal" tabindex="-1" role="dialog" aria-labelledby="uretTakozModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="uretTakozForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Takoz Üret</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="uret_takoz_ids" name="ids[]">

                    <div class="form-group">
                        <label for="uret_adet">Üretilecek Takoz Adedi</label>
                        <input type="number" min="1" class="form-control" id="uret_adet" required>
                    </div>

                    <div id="uret_takoz_inputlari"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Kaydet</button>
                </div>
                <div class="row">
                    <!-- SOL SÜTUN -->
                    <div class="col-md-6">
                        <div class="form-group text-center">
                            <label>Seçilen Toplam Ağırlık (gr):</label>
                            <div id="toplam_agirlik" class="form-control-plaintext font-weight-bold text-primary"></div>
                        </div>


                    </div>

                    <!-- SAĞ SÜTUN -->
                    <div class="col-md-6">
                        <div class="form-group text-center">
                            <label>Girdiğiniz Toplam Ağırlık:</label>
                            <div id="toplam_input_agirlik" class="form-control-plaintext font-weight-bold text-info">0 gr</div>
                        </div>

                        <div class="form-group text-center">
                            <label>Fire Miktarı:</label>
                            <div id="fire_miktar" class="form-control-plaintext font-weight-bold text-danger">0 gr</div>
                        </div>
                    </div>
                </div>


            </div>
        </form>
    </div>
</div>



<script>
    // Modal açıldığında: ID'leri al ve modalı göster
    document.getElementById('erit-button').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.select-row:checked')).map(cb => cb.value);

        if (selectedIds.length === 0) {
            alert('Lütfen en az bir takoz seçin.');
            return;
        }

        document.getElementById('uret_takoz_ids').value = selectedIds.join(',');
        document.getElementById('uret_adet').value = '';
        document.getElementById('uret_takoz_inputlari').innerHTML = '';


        // Seçilen takozların toplam ağırlığını hesapla
        let toplamAgirlik = 0;
        let paketGumus = 0;
        let bilezikGumus = 0;
        document.querySelectorAll('.select-row:checked').forEach(cb => {
            const agirlik = parseFloat(cb.getAttribute('data-agirlik')) || 0;
            const milyem = parseFloat(cb.getAttribute('milyem')) || 0;
            paketGumus += ((agirlik * milyem) / 995) - agirlik;
            bilezikGumus += ((agirlik * milyem) / 916) - agirlik;
            toplamAgirlik += agirlik;
        });

        const asit1 = toplamAgirlik * 1.5;
        const asit2 = asit1 * 3;

        // Label gibi görünen alanlara yaz
        document.getElementById('toplam_agirlik').textContent = toplamAgirlik.toFixed(2) + ' gr';



        $('#uretTakozModal').modal('show');
    });

    // Adet girilince sadece ağırlık inputları oluştur
    document.getElementById('uret_adet').addEventListener('input', function() {
        const adet = parseInt(this.value);
        const container = document.getElementById('uret_takoz_inputlari');
        container.innerHTML = '';

        if (adet > 0) {
            for (let i = 1; i <= adet; i++) {
                container.innerHTML += `
        <div class="form-group d-flex align-items-center gap-2">
  <div style="flex: 1;">
    <label>${i}. Takoz Ağırlığı (gr)</label>
    <input type="number" name="agirlik[]" step="0.01" class="form-control" required>
  </div>
  <div style="flex: 1;">
    <label>Milyem</label>
    <input type="number" name="milyem[]" step="0.01" class="form-control" required>
  </div>
</div>
      `;
            }
        }
    });

    // Form submit: sadece ID'ler ve ağırlıklar JSON olarak gönderilir
    document.getElementById('uretTakozForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const ids = document.getElementById('uret_takoz_ids').value.split(',');
        const agirliklar = Array.from(form.querySelectorAll('input[name="agirlik[]"]')).map(i => i.value);
        const milyemler = Array.from(form.querySelectorAll('input[name="milyem[]"]')).map(i => i.value);

    const takozlar = agirliklar.map((a, index) => ({
    agirlik: a,
    milyem: milyemler[index] || null
}));

        fetch("<?= base_url('home/islenmisTakozUret') ?>", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                ids: ids,
                takozlar: takozlar
            })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                alert("Takozlar başarıyla üretildi!");
                $('#uretTakozModal').modal('hide');
                location.reload();
            } else {
                alert("Bir hata oluştu: " + data.error);
            }
        }).catch(err => {
            console.error(err);
            alert("Sunucu hatası.");
        });
    });


    // --- (1) Modal açılırken seçilen toplam HAS miktarını ayarlayan bölüm (aynı şekilde bırakabilirsiniz)
    let secilenToplamAgirlik = 0; // gram cinsinden HAS toplamı

    document.getElementById('erit-button').addEventListener('click', function() {
        secilenToplamAgirlik = 0;
        document.querySelectorAll('.select-row:checked').forEach(cb => {
            // buradaki attribute ismine göre düzenleyin: örn data-has veya data-agirlik
            const has = parseFloat(cb.getAttribute('data-has')) || 0;
            secilenToplamAgirlik += has;
        });

        document.getElementById('toplam_input_agirlik').textContent = '0 gr';
        // gösterilen "fire_miktar" başlangıçta seçilen toplam has olarak ayarlanıyor
        document.getElementById('fire_miktar').textContent = secilenToplamAgirlik.toFixed(2) + ' gr';
    });

    // --- (2) Inputlara event ekleme (adet değiştiğinde / input oluşturulduğunda)
    document.getElementById('uret_adet').addEventListener('input', function() {
        setTimeout(() => {
            document.querySelectorAll('input[name="agirlik[]"]').forEach(input => {
                input.removeEventListener('input', hesaplaFire);
                input.addEventListener('input', hesaplaFire);
            });
            // aynı zamanda milyem inputlarına da event ekleyelim
            document.querySelectorAll('input[name="milyem[]"]').forEach(input => {
                input.removeEventListener('input', hesaplaFire);
                input.addEventListener('input', hesaplaFire);
            });
        }, 100);
    });

    // --- (3) Yeni hesaplaFire fonksiyonu
    function hesaplaFire() {
        // Tüm girilen ağırlıklar (takozların yeni ağırlıkları)
        const agirlikInputs = Array.from(document.querySelectorAll('input[name="agirlik[]"]'));
        const milyemInputs = Array.from(document.querySelectorAll('input[name="milyem[]"]'));

        // Toplam girilen gram (sadece görüntü için)
        const toplamInputAgirlik = agirlikInputs
            .map(i => parseFloat(i.value) || 0)
            .reduce((a, b) => a + b, 0);

        // Her takoz için 'saf altın eşdeğeri' = girilenTakozGram * (milyem / 1000)
        let toplamHasUretilen = 0;

        for (let idx = 0; idx < agirlikInputs.length; idx++) {
            const g = parseFloat(agirlikInputs[idx].value) || 0;
            const m = (milyemInputs[idx] ? parseFloat(milyemInputs[idx].value) : 0) || 0;
            if (g > 0 && m > 0) {
                toplamHasUretilen += g * (m / 1000);
            } else if (g > 0 && !milyemInputs[idx]) {
                // eğer milyem inputu yoksa (eski kodda olabilir) varsayılan 916 kullanmamak daha güvenli,
                // ama isterseniz burada default milyem belirleyebilirsiniz (ör: 916).
            }
        }

        // Fire = seçilen toplam HAS (secilenToplamAgirlik) - toplamHasUretilen
        const fire = secilenToplamAgirlik - toplamHasUretilen;

        // UI'yı güncelle
        document.getElementById('toplam_input_agirlik').textContent = toplamInputAgirlik.toFixed(2) + ' gr';
        // yeni bir alan ekleyip 'girdiğiniz takozların HAS eşdeğeri' gösterelim
        let hasEqElem = document.getElementById('girdi_has_esdegeri');
        if (!hasEqElem) {
            // Sağ sütuna veya uygun bir yere ekleyin; ben mevcut modal-footer içindeki sağ sütuna ekliyorum
            const colRight = document.querySelector('#uretTakozModal .modal-content .row .col-md-6:last-child');
            if (colRight) {
                const wrapper = document.createElement('div');
                wrapper.className = 'form-group text-center';
                wrapper.innerHTML = `<label>Girdiğiniz Takozların Saf-Altın Eşdeğeri (has):</label>
                                 <div id="girdi_has_esdegeri" class="form-control-plaintext font-weight-bold text-secondary">0 gr</div>`;
                colRight.insertBefore(wrapper, colRight.children[0]); // en üste ekle
                hasEqElem = document.getElementById('girdi_has_esdegeri');
            }
        }

        if (hasEqElem) hasEqElem.textContent = toplamHasUretilen.toFixed(2) + ' gr';

        // Fire alanını negatif gösterme ihtimaline dikkat et; negatif ise 0 veya negatif gösterebilirsin.
        document.getElementById('fire_miktar').textContent = fire.toFixed(2) + ' gr';
    }
</script>

<!-- Gümüş & Bakır hesaplama eklentisi -->
<script>
    document.addEventListener('input', function(e) {
        if (e.target && (e.target.name === 'agirlik[]' || e.target.name === 'milyem[]')) {
            hesaplaAyarOranlari();
        }
    });

    function hesaplaAyarOranlari() {
        const agirliklar = Array.from(document.querySelectorAll('input[name="agirlik[]"]')).map(i => parseFloat(i.value) || 0);
        const hedefMilyemler = Array.from(document.querySelectorAll('input[name="milyem[]"]')).map(i => parseFloat(i.value) || 0);

        let toplamGumus = 0;
        let toplamBakir = 0;

        let outputContainer = document.getElementById('ayar_hesap_tablosu');
        if (!outputContainer) {
            const modalBody = document.querySelector('#uretTakozModal .modal-body');
            outputContainer = document.createElement('div');
            outputContainer.id = 'ayar_hesap_tablosu';
            outputContainer.className = 'mt-4 p-2 border rounded shadow-sm bg-light';
            modalBody.appendChild(outputContainer);
        }

        let html = `
        <div class="text-center mb-3">
            <h6 class="fw-bold text-secondary">💎 Takoz Bazlı Gümüş & Bakır Hesabı</h6>
        </div>
        <div class="table-responsive">
        <table class="table table-sm table-hover align-middle text-center border">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Gram</th>
                    <th>Hedef Milyem</th>
                    <th>Saf Altın (gr)</th>
                    <th class="text-primary">Gümüş (gr)</th>
                    <th class="text-danger">Bakır (gr)</th>
                    <th>Yeni Takoz Ağırlığı (gr)</th>
                </tr>
            </thead>
            <tbody class="fade-in">
    `;

        agirliklar.forEach((gram, i) => {
            const hedefMilyem = hedefMilyemler[i] || 0;
            if (gram > 0 && hedefMilyem > 0 && hedefMilyem <= 1000) {
                // Ters hesap: hedef milyem için eklenmesi gereken alaşım
                const alasim = (gram * (1000 / hedefMilyem)) - gram;
                const gumus = alasim * 0.15; // Alaşımdaki gümüş oranı
                const bakir = alasim * 0.85; // Alaşımdaki bakır oranı
                const safAltin = gram; // elimizdeki saf altın gramı
                const yeniTakoz = safAltin + gumus + bakir;

                toplamGumus += gumus;
                toplamBakir += bakir;

                html += `
                <tr class="table-row-anim">
                    <td>${i + 1}</td>
                    <td>${gram.toFixed(2)}</td>
                    <td>${hedefMilyem.toFixed(1)}</td>
                    <td>${safAltin.toFixed(2)}</td>
                    <td class="text-primary fw-semibold">${gumus.toFixed(2)}</td>
                    <td class="text-danger fw-semibold">${bakir.toFixed(2)}</td>
                    <td class="text-success fw-bold">${yeniTakoz.toFixed(2)}</td>
                </tr>
            `;
            }
        });

        html += `
            </tbody>
            <tfoot class="table-light">
                <tr style="font-weight:bold;">
                    <td colspan="4" class="text-end">TOPLAM:</td>
                    <td class="text-primary">${toplamGumus.toFixed(2)}</td>
                    <td class="text-danger">${toplamBakir.toFixed(2)}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        </div>
    `;

        outputContainer.innerHTML = html;
    }

    // Animasyon
    const style = document.createElement('style');
    style.innerHTML = `
@keyframes fadeInRow {
  from { opacity: 0; transform: translateY(-3px); }
  to { opacity: 1; transform: translateY(0); }
}
.table-row-anim {
  animation: fadeInRow 0.3s ease-in-out;
}
`;
    document.head.appendChild(style);
</script>




























<?= view('include/footer') ?>