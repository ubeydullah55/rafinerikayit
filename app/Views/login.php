<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8" />
	<title>SAR-GERCEK KİSİ</title>

	<!-- Site favicon -->
	<link
		rel="apple-touch-icon"
		sizes="180x180"
		href="<?= base_url("assets/vendors/images/apple-touch-icon.png") ?>" />
	<link
		rel="icon"
		type="image/png"
		sizes="32x32"
		href="<?= base_url("assets/vendors/images/favicon-32x32.png") ?>" />
	<link
		rel="icon"
		type="image/png"
		sizes="16x16"
		href="<?= base_url("assets/vendors/images/favicon-16x16.png") ?>" />

	<!-- Mobile Specific Metas -->
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1, maximum-scale=1" />

	<!-- Google Font -->
	<link
		href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
		rel="stylesheet" />
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="<?= base_url("assets/") ?>vendors/styles/core.css" />
	<link
		rel="stylesheet"
		type="text/css"
		href="<?= base_url("assets/") ?>vendors/styles/icon-font.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= base_url("assets/") ?>vendors/styles/style.css" />

	<!-- Global site tag (gtag.js) - Google Analytics -->

</head>

<body class="login-page">
	<div class="login-header box-shadow">
		<div
			class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="<?= base_url() ?>">
					<img src="<?= base_url("assets/") ?>vendors/images/deskapp-logo.svg" alt="" />
				</a>
			</div>

		</div>
	</div>
	<div
		class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="<?= base_url("assets") ?>/vendors/images/login-page-img.png" alt="" />
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">SAMSUN ALTIN RAFİNERİ</h2>
						</div>
						<?php if (session()->getFlashdata('error')): ?>
							<div class="alert alert-danger">
								<?= session()->getFlashdata('error') ?>
							</div>
						<?php endif; ?>
						<form action="<?= base_url('login') ?>" method="post">
							<div class="select-role">
								<div class="btn-group btn-group-toggle" data-toggle="buttons">
									<label class="btn active">
										<input type="radio" name="role" id="admin" value="1" />
										<div class="icon">
											<img src="<?= base_url("assets/") ?>vendors/images/briefcase.svg" class="svg" alt="" />
										</div>
										<span>S.A.R</span>
										Personel Girişi
									</label>
								</div>
							</div>
							<div class="input-group custom">
								<input
									type="text"
									name="username"
									class="form-control form-control-lg"
									placeholder="Username" />
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>
							</div>
							<div class="input-group custom">
								<input
									type="password"
									name="password"
									class="form-control form-control-lg"
									placeholder="**********" />
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<button type="submit" class="btn btn-primary btn-lg btn-block">Giriş Yap</button>
									</div>
								</div>
							</div>
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- welcome modal start -->


	<!-- welcome modal end -->
	<!-- js -->
	<script src="<?= base_url("assets/") ?>vendors/scripts/core.js"></script>
	<script src="<?= base_url("assets/") ?>vendors/scripts/script.min.js"></script>
	<script src="<?= base_url("assets/") ?>vendors/scripts/process.js"></script>
	<script src="<?= base_url("assets/") ?>vendors/scripts/layout-settings.js"></script>
	<!-- Google Tag Manager (noscript) -->
	
</body>

</html>