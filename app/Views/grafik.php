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
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="title">
							<h4>Gerçek Kişi Listesi</h4>
						</div>
						<nav aria-label="breadcrumb" role="navigation">
							<ol class="breadcrumb">
								<li class="breadcrumb-item">
									<a href="index.html">Anasayfa</a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">
									Liste
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
					<h4 class="text-blue h4" >Kayıt Açma Grafiği</h4>
				</div>
				<div class="pb-20">
                <div class="row">
						<div class="col-md-6 mb-30">
							<div class="pd-20 card-box height-100-p">
								<h4 class="h4 text-blue">Genel Kayıt Açma Oranı</h4>
								<div id="chart8"></div>
                                <p style="text-align: center;">Toplam Kayıt Sayısı: <?= $totalCount; ?></p>
							</div>
						</div>
						<div class="col-md-6 mb-30">
                     
						<div class="card-box height-100-p pd-20">
                        <h2 class="h4 mb-20" id="monthTitle"></h2>
							<div id="chart5"></div>
                            <p style="text-align: center;">Bu Ay Yapılan Toplam Kayıt Sayısı: <?= array_sum($thisMonthCounts); ?></p>
						</div>				
						</div>
					</div>
				</div>
                
			</div>
            <div class="bg-white pd-20 card-box mb-30">
						<h4 class="h4 text-blue">S.A.R Aylık Toplam Kayıt</h4>
						<div id="chart1"></div>
					</div>
			<!-- Export Datatable End -->
		</div>
		<div class="footer-wrap pd-20 mb-20 card-box">
			Creadet By
			<a href="https://github.com/ubeydullah55" target="_blank">Ubeydullah Doğan</a>
		</div>
	</div>
</div>


<script src="<?= base_url("assets/") ?>src/plugins/apexcharts/apexcharts.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // PHP'den gelen verileri JavaScript'e aktarıyoruz
        var names = <?php echo json_encode($names); ?>;
        var namesMonth = <?php echo json_encode($namesMonth); ?>;
        var series = <?php echo json_encode($series); ?>;
        var seriesMonth = <?php echo json_encode($seriesMonth); ?>;
        var months = <?php echo json_encode($months); ?>;
        var monthlySeries = <?php echo json_encode($monthlySeries); ?>;
        var maxMonthly = Math.max(...monthlySeries);
        var yAxisMax = Math.ceil((maxMonthly + 10) / 10) * 10;
        
    //#region aylık 
          // Ay bazında line grafik (Aylık toplam kayıtları gösteriyoruz)
        var options5 = {
            chart: {
                height: 350,
                type: 'bar',
                parentHeightOffset: 0,
                fontFamily: 'Poppins, sans-serif',
                toolbar: { show: false },
            },
            colors: ['#1b00ff', '#f56767', '#34bfa3'],
            grid: {
                borderColor: '#c7d2dd',
                strokeDashArray: 5,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '30%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: { enabled: true },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            series: [{
                name: 'Kullanıcılar', 
                data: seriesMonth 
            }],
            xaxis: {
                categories: namesMonth, // X ekseninde kullanıcı isimleri
                labels: {
                    style: {
                        colors: ['#353535'],
                        fontSize: '16px',
                    },
                },
                axisBorder: { color: '#8fa6bc' }
            },
            yaxis: {
                title: { text: 'Kayıt Sayısı' },
                labels: { style: { colors: '#353535', fontSize: '16px' } },
                axisBorder: { color: '#f00' }
            },
            legend: {
                horizontalAlign: 'right',
                position: 'top',
                fontSize: '16px',
                offsetY: 0,
                labels: { colors: '#353535' },
                markers: { width: 10, height: 10, radius: 15 },
                itemMargin: { vertical: 0 }
            },
            fill: { opacity: 1 },
            tooltip: {
                style: { fontSize: '15px', fontFamily: 'Poppins, sans-serif' },
                y: {
                    formatter: function (val) { return val + ' kayıt'; }
                }
            }
        };

        var chart5 = new ApexCharts(document.querySelector("#chart5"), options5);
        chart5.render();
    //#endregion
      
        
    //#region en-alt-toplam-firma-cizgi
        var options1 = {
            series: [{
                name: 'Aylık Kayıt',
                data: monthlySeries
            }],
            chart: {
                height: 350,
                type: 'line',
                toolbar: { show: false },
            },
            grid: { show: false, padding: { left: 0, right: 0 } },
            stroke: { width: 7, curve: 'smooth' },
            xaxis: {
                categories: months, // Aylar
            },
            title: {
                text: 'Aylık Toplam Kayıt',
                align: 'left',
                style: { fontSize: "16px", color: '#666' }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: ['#1b00ff'],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100, 100, 100]
                },
            },
            markers: {
                size: 4,
                colors: ["#FFA41B"],
                strokeColors: "#fff",
                strokeWidth: 2,
                hover: { size: 7 }
            },
            yaxis: {
                min: 0,
                max: yAxisMax,
                title: { text: 'Kayıt Sayısı' },
            }
        };

        var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
        chart1.render();
        //#endregion


        // Donut grafik (Önceki gibi)
        var options8 = {
            series: series,  // PHP'den gelen veriyi kullanıyoruz
            chart: {
                type: 'donut',
            },
            labels: names,  // Burada isimleri etiket olarak kullanıyoruz
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: { width: 200 },
                    legend: { position: 'bottom' }
                }
            }]
        };

        var chart8 = new ApexCharts(document.querySelector("#chart8"), options8);
        chart8.render();
    });
</script>
<script>
    const aylar = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
    const simdi = new Date();
    const ayAdi = aylar[simdi.getMonth()]; // getMonth() 0-11 arası döner
    const yil = simdi.getFullYear();

    document.getElementById('monthTitle').textContent = `${ayAdi} ${yil} Ayı Kayıt Açma Sayısı`;
</script>
<?= view('include/footer') ?>