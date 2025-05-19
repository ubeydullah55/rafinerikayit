
		<!-- welcome modal end -->
		<!-- js -->
		<script src="<?= base_url("assets/") ?>vendors/scripts/core.js"></script>
		<script src="<?= base_url("assets/") ?>vendors/scripts/script.min.js"></script>
		<script src="<?= base_url("assets/") ?>vendors/scripts/process.js"></script>
		<script src="<?= base_url("assets/") ?>vendors/scripts/layout-settings.js"></script>
		<script src="<?= base_url("assets/") ?>src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="<?= base_url("assets/") ?>src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="<?= base_url("assets/") ?>src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="<?= base_url("assets/") ?>src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<!-- buttons for Export datatable -->
		<script src="<?= base_url("assets/") ?>src/plugins/datatables/js/dataTables.buttons.min.js"></script>
		<script src="<?= base_url("assets/") ?>src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
		<script src="<?= base_url("assets/") ?>src/plugins/datatables/js/buttons.print.min.js"></script>
		<script src="<?= base_url("assets/") ?>src/plugins/datatables/js/buttons.html5.min.js"></script>
		<script src="<?= base_url("assets/") ?>src/plugins/datatables/js/buttons.flash.min.js"></script>
		<script src="<?= base_url("assets/") ?>src/plugins/datatables/js/pdfmake.min.js"></script>
		<script src="<?= base_url("assets/") ?>src/plugins/datatables/js/vfs_fonts.js"></script>
		<script src="<?= base_url("assets/") ?>src/plugins/dropzone/src/dropzone.js"></script>
		<script>
			Dropzone.autoDiscover = false;
			$(".dropzone").dropzone({
				addRemoveLinks: true,
				removedfile: function (file) {
					var name = file.name;
					var _ref;
					return (_ref = file.previewElement) != null
						? _ref.parentNode.removeChild(file.previewElement)
						: void 0;
				},
			});
		</script>
		<!-- Datatable Setting js -->
		<script src="<?= base_url("assets/") ?>vendors/scripts/datatable-setting.js"></script>
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
