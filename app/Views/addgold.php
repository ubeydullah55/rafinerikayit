<?= view('include/header') ?>

<?= view('include/leftmenu') ?>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <!-- Form grid Start -->
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <h4 class="text-blue h4">Takoz Girişi</h4>

                </div>

            </div>

            <form method="post" action="<?= base_url('takoz/kaydet') ?>" enctype="multipart/form-data">


                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label>Müşteri<span class="text-danger">*</span></label>
                            <input type="text" name="musteri" class="form-control" oninput="this.value = fixTurkishUppercase(this.value);" required />
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <label>Giriş Miktarı<span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="giris_agirlik" class="form-control" required />
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <label>Tahmini Milyem</label>
                            <input type="number" step="0.01" name="tahmini_milyem" class="form-control" required />
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label>Müşteri Notu</label>
                            <input type="text" name="musteri_notu" class="form-control" oninput="this.value = fixTurkishUppercase(this.value);" />
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <label>Tür</label>
                            <select name="altin_turu" class="form-control">
                                <option value="0">Hurda</option>
                                <option value="1">Takoz</option>
                            </select>
                        </div>
                    </div>
                </div>



                <br>
                <div class="btn-list">
                    <button type="submit" class="btn btn-success btn-lg btn-block">
                        Kaydet
                    </button>



            </form>

        </div>
    </div>
    <!-- Form grid End -->
    <div class="footer-wrap pd-20 mb-20 card-box">
        Created by
        <a href="https://github.com/ubeydullah55" target="_blank">ubeydullah doğan</a>
    </div>
</div>

</div>
<!-- welcome modal start -->

<?= view('include/footer') ?>


<script>
    function fixTurkishUppercase(val) {
        return val.replace(/i/g, 'İ').toUpperCase();
    }
</script>