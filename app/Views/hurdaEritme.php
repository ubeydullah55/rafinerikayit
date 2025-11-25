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
							<h4>HURDA ERİTME</h4>					
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
									<td class="table-plus"><?= esc($item['musteri_adi']); ?></td>
									<td><?= number_format(esc($item['giris_gram']), 2); ?> gr</td>
									<td><?= esc($item['tahmini_milyem']); ?></td>
									<td><?= number_format($item['giris_gram'] * ($item['tahmini_milyem'] / 1000), 2); ?> gr</td>
									  <td style="text-align:center">
                                        <?php if (!empty($item['musteri_notu'])): ?>
                                            <span
                                                style="cursor:pointer; color:#d9534f; font-size:18px;"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="<?= esc($item['musteri_notu']) ?>">&#9888;</span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
									<td>
										<div class="dropdown">
											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
												<i class="dw dw-more"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
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

			<!-- Export Datatable End -->
		</div>
		<div class="footer-wrap pd-20 mb-20 card-box">
			Creadet By
			<a href="https://github.com/ubeydullah55" target="_blank">Ubeydullah Doğan</a>
		</div>
	</div>
</div>
<!-- welcome modal start -->

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



<script>
	function yazdirTakoz(id, musteri, girisGram, tahminiMilyem, musteriNotu,islemYapan) {
		var printWindow = window.open('', '', 'width=300,height=600');

		var icerik = `
        <html>
        <head>
            <title>Fiş Yazdır</title>
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
				<p><strong>Altın Takoz Fişi</strong></p>
                <p><strong>ID:</strong> ${id}</p>
                <p><strong>Müşteri:</strong> ${musteri}</p>
                <p><strong>Gramaj:</strong> ${girisGram} gr</p>
                <p><strong>Tahmini Milyem:</strong> ${tahminiMilyem}</p>
                <p><strong>Not:</strong> ${musteriNotu}</p>
				<p><strong>İşlem Yapan Personel:</strong> ${islemYapan}</p>
                <p><strong>Tarih:</strong> ${new Date().toLocaleString()}</p>
            </div>
            <script>
                window.addEventListener('afterprint', function() {
                    window.close();
                });
                window.onload = function() {
                    window.print();
                }
            <\/script>
        </body>
        </html>
    `;

		printWindow.document.open();
		printWindow.document.write(icerik);
		printWindow.document.close();
	}
</script>




<?= view('include/footer') ?>