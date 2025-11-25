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
							<h4>MAL KABUL</h4>
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
												<a href="#" onclick="ilerletTakoz(<?= $item['id']; ?>, '<?= $item['musteri']; ?>')" class="dropdown-item">
													<i class="dw dw-enter-1"></i> İlerlet
												</a>
												<a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Düzenle</a>
												<a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Sil</a>

												<a href="#" onclick="yazdirTakoz(
    <?= $item['id']; ?>,
    '<?= esc($item['musteri_adi']); ?>',
    <?= number_format((float)$item['giris_gram'], 2, '.', ''); ?>,
    '<?= esc($item['tahmini_milyem']); ?>',
    '<?= esc($item['musteri_notu']) ?: '-'; ?>',
	 '<?= $item['created_user']; ?>',
)" class="dropdown-item">
													<i class="dw dw-print"></i> Fiş Yazdır
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
								<td style="font-weight:bold;"><?= number_format($totalGram, 2); ?> gr</td>
								<td colspan="3"></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>

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
												<a class="dropdown-item" href="#"><i class="dw dw-eye"></i> İncele</a>
												<a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Düzenle</a>
												<a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Sil</a>
												<a href="#" onclick="ilerletHurda(<?= $item['id']; ?>, '<?= $item['musteri']; ?>')" class="dropdown-item">
													<i class="dw dw-enter-1"></i> İlerlet
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


<!--
<script>
	function printRow(el, img1Src, img2Src, dogumYeri, dogumTarihi, anneAdi, babaAdi, uyruk, kimlikBelgesiTuru, kimlikBelgesiNumarasi, eposta) {
		const row = el.closest('tr');
		const cells = row.querySelectorAll('td');
		const adSoyad = cells[1].innerText;
		const tc = cells[2].innerText;
		const unvan = cells[3].innerText;
		const telefon = cells[4].innerText;
		const sehir = cells[5].innerText;
		const ilce = cells[6].innerText;
		const not = cells[7].innerText;

		// Küçük yardımcı fonksiyon
		function isValidImage(src) {
			if (!src) return false;
			const cleanedSrc = src.trim().toLowerCase();
			return cleanedSrc !== 'null' && cleanedSrc !== 'undefined' && !cleanedSrc.includes('default.png');
		}

		// imagesHtml’i oluştur
		let imagesHtml = '';
		if (isValidImage(img1Src) || isValidImage(img2Src)) {
			imagesHtml = '<div class="images" style="display:flex;justify-content:space-between;margin-bottom:20px;">';
			if (isValidImage(img1Src)) {
				imagesHtml += `
					<div style="position:relative;width:45%;">
						<img src="${img1Src}" style="width:100%;" onerror="this.style.display='none'">
						<div style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%) rotate(-15deg);color:#808080;font-size:24px;font-weight:bold;opacity:0.3;pointer-events:none;white-space:nowrap;">
							YALNIZCA ALTIN SATIŞI İÇİNDİR
						</div>
					</div>
				`;
			}
			if (isValidImage(img2Src)) {
				imagesHtml += `
					<div style="position:relative;width:45%;">
						<img src="${img2Src}" style="width:100%;" onerror="this.style.display='none'">
					<div style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%) rotate(-15deg);color:#808080;font-size:24px;font-weight:bold;opacity:0.3;pointer-events:none;white-space:nowrap;">
							YALNIZCA ALTIN SATIŞI İÇİNDİR
						</div>
					</div>
				`;
			}
			imagesHtml += '</div>';
		}

		const printWindow = window.open('', '', 'width=800,height=600');
		printWindow.document.write(`
        <html>
        <head>
          <title>Yazdır</title>
          <style>
            body {font-family:Arial,sans-serif;padding:20px;}
            table {border-collapse:collapse;width:100%;margin-top:20px;}
            td,th {border:1px solid #000;padding:8px;}
            .signature-box {
              width:250px;height:50px;border:1px solid #000;
              margin-top:40px;float:right;text-align:center;
              padding-top:70px;font-weight:bold;
            }
          </style>
        </head>
        <body>
          <h2 style="text-align:center;">GERÇEK KİŞİ TANI FORMU</h2>
          ${imagesHtml}
         <table>
                <tr><th>Ad Soyad</th><td>${adSoyad}</td></tr>
                <tr><th>T.C. Kimlik</th><td>${tc}</td></tr>
                <tr><th>Doğum Yeri</th><td>${dogumYeri}</td></tr>
                <tr><th>Doğum Tarihi</th><td>${dogumTarihi}</td></tr>
                <tr><th>Anne Baba Adı</th><td>${anneAdi} / ${babaAdi}</td></tr>
                <tr><th>Uyruk</th><td>${uyruk}</td></tr>
                <tr><th>Kimlik Belgesinin Türü ve Numarası</th><td>${kimlikBelgesiTuru} / ${kimlikBelgesiNumarasi}</td></tr>
                <tr><th>Adres</th><td>${sehir} / ${ilce}</td></tr>
                <tr><th>Mesleği</th><td>${unvan}</td></tr>
                <tr><th>Cep Telefonu</th><td>${telefon}</td></tr>
                <tr><th>E-posta</th><td>${eposta}</td></tr>
            </table>
          <div class="signature-box"></div>
          <script>
            window.onload = function() {
              window.print();
              window.onafterprint = function() { window.close(); };
            }
          <\/script>
		  <div style="position: fixed; bottom: 20px; left: 0; right: 0; text-align: center; font-size: 14px; color: #555;">
  Samsun SAR Altın Kıymetli Madenler Limited Şirketi olarak size ilişkin birtakım kişisel verileri 6698 sayılı Kişisel Verilerin Korunması Kanunu uyarınca işlemekteyiz.Kişisel verileriniz farklı hukuki
  sebeplere dayanarak şirketin faalitlerini sürdürebilmesi için KVKK tarafından öngörülen temel ilkelere uygun olarak,KVKK'nın 5. ve 6. maddelerinde belitrilen kişisel veri işleme şartları
  ve amaçlı kapsamında toplanabilmekte, işlenebilmekte ve aktarılabilmektedir.Verilerinizin işlenme amaçları ve veri sahibi olarak haklarınız hakkında detaylı bilgi almak için [www.saraltin.com] adresli internet
  sitemizde yer alan Kişisel Verilerin Koruması Aydınlatma Metni'ne ulaşabilirsiniz.
</div>

        </body>
        </html>
    `);
		printWindow.document.close();
	}
</script>

 -->

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
	function ilerletHurda(id, musteri) {
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
				fetch('<?= base_url('hurda/ilerletAjax/'); ?>' + id, {
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
	function yazdirTakoz(id, musteri, girisGram, tahminiMilyem, musteriNotu, islemYapan) {
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