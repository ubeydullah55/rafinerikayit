                    <?= view('include/header') ?>

                    <?= view('include/leftmenu') ?>

                    <div class="main-container">
                        <div class="pd-ltr-20 xs-pd-20-10">

                            <!-- Form grid Start -->
                            <div class="pd-20 card-box mb-30">
                                <div class="clearfix">
                                    <div class="pull-left">
                                        <h4 class="text-blue h4">Gerçek Kişi Ekle</h4>

                                    </div>

                                </div>

                                <form method="post" action="<?= base_url('savecustomer') ?>" enctype="multipart/form-data">


                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-4">
                                            <div class="position-relative text-center">
                                                <a class="d-block">
                                                    <!-- Varsayılan Resim ve Resim Önizleme -->
                                                    <img src="products/default-bilezik.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-md" id="bilezikResimPreview">
                                                </a>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="bilezikResim">Kimlik kartı Ön Yüz Resmi Yükle</label>
                                                <input type="file" class="form-control" id="onyuz_resim" name="onyuz_resim" accept="image/*" onchange="previewImage(event)">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="position-relative text-center">
                                                <a class="d-block">
                                                    <!-- Varsayılan Resim ve Resim Önizleme -->
                                                    <img src="products/default-bilezik.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-md" id="bilezikResimPreview2">
                                                </a>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="bilezikResim">Kimlik kartı Arka Yüz Resmi Yükle</label>
                                                <input type="file" class="form-control" id="arkayuz_resim" name="arkayuz_resim" accept="image/*" onchange="previewImage2(event);readOCR();">
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>Ad<span class="text-danger">*</span></label>
                                                <input type="text" name="ad" class="form-control" oninput="this.value = fixTurkishUppercase(this.value);" required />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>Soyad<span class="text-danger">*</span></label>
                                                <input type="text" name="soyad" class="form-control" oninput="this.value = fixTurkishUppercase(this.value);" required />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>T.C Kimlik Numarası<span class="text-danger">*</span></label>
                                                <input type="text" name="tc" class="form-control" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>Doğum Tarihi</label>
                                                <input type="date" name="dogum_tarihi" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>Doğum Yeri</label>
                                                <input type="text" name="dogum_yeri" class="form-control" oninput="this.value = fixTurkishUppercase(this.value);" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>Anne Adı</label>
                                                <input type="text" name="anne_adi" class="form-control" oninput="this.value = fixTurkishUppercase(this.value);" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>Baba Adı</label>
                                                <input type="text" name="baba_adi" class="form-control" oninput="this.value = fixTurkishUppercase(this.value);" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>Uyruğu</label>
                                                <input type="text" name="uyruk" class="form-control" value="T.C" oninput="this.value = fixTurkishUppercase(this.value);" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>Kimlik Belgesinin Türü</label>
                                                <input type="text" name="belge_turu" class="form-control" value="T.C" oninput="this.value = fixTurkishUppercase(this.value);" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>Kimlik Belgesinin Numarası</label>
                                                <input type="text" name="belge_numarası" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>E-Posta</label>
                                                <input type="text" name="eposta" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>Telefon Numarası</label>
                                                <input type="text" name="tel" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>Meslek Bilgisi</label>
                                                <input type="text" name="meslek" class="form-control" oninput="this.value = fixTurkishUppercase(this.value);" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>Şehir</label>
                                                <input type="text" name="sehir" class="form-control" oninput="this.value = fixTurkishUppercase(this.value);" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>Adres</label>
                                                <input type="text" name="adres" class="form-control" oninput="this.value = fixTurkishUppercase(this.value);" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label>Not<span class="text-danger">*</span></label>
                                                <textarea name="not" oninput="this.value = fixTurkishUppercase(this.value);" class="form-control" rows="4" required></textarea>
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
                        // Resim Yükleme Önizleme Fonksiyonu
                        function previewImage(event) {
                            var output = document.getElementById('bilezikResimPreview');
                            output.src = URL.createObjectURL(event.target.files[0]);
                        }

                        function previewImage2(event) {
                            var output = document.getElementById('bilezikResimPreview2');
                            output.src = URL.createObjectURL(event.target.files[0]);
                        }
                    </script>
                    <script>
                        function fixTurkishUppercase(val) {
                            return val.replace(/i/g, 'İ').toUpperCase();
                        }
                    </script>

                    <script>
                        function parseMRZ(text) {
                            const lines = text.trim().split('\n').map(l => l.trim()).filter(Boolean);
                            if (lines.length < 3) return {
                                error: "MRZ satırları eksik."
                            };

                            const [line1, line2, line3] = lines;

                            const documentType = line1.slice(0, 1);
                            const countryCode = line1.slice(2, 5);

                            // ✅ DÜZELTME: Seri no 9 hane, TC no 11 hane
                            const afterCountry = line1.slice(5);
                            const serialNumber = afterCountry.slice(0, 9).replace(/</g, '');

                            const remaining = afterCountry.slice(9);
                            const tcStart = remaining.indexOf('<') + 1;
                            const tcNumber = remaining.slice(tcStart).replace(/</g, '').slice(0, 11);

                            const birthDateRaw = line2.slice(0, 6);
                            const gender = line2.slice(7, 8);
                            const expiryRaw = line2.slice(8, 14);

                            const surnameAndName = line3.split('<<');
                            const surname = surnameAndName[0].replace(/</g, '');
                            const name = (surnameAndName[1] || '').replace(/</g, ' ');

                            function formatDate(yyMMdd, force2000 = false) {
                                const year = parseInt(yyMMdd.slice(0, 2), 10);
                                const month = yyMMdd.slice(2, 4);
                                const day = yyMMdd.slice(4, 6);

                                const fullYear = force2000 ?
                                    `20${year.toString().padStart(2, '0')}` :
                                    (year > 30 ? `19${year}` : `20${year}`);

                                return `${fullYear}-${month}-${day}`;
                            }

                            return {
                                "Belge Türü": documentType,
                                "Ülke": countryCode,
                                "Seri No": serialNumber,
                                "T.C. No": tcNumber,
                                "Doğum Tarihi": formatDate(birthDateRaw),
                                "Cinsiyet": gender === 'M' ? 'Erkek' : (gender === 'F' ? 'Kadın' : 'Belirsiz'),
                                "Son Geçerlilik Tarihi": formatDate(expiryRaw, true),
                                "Soyad": surname,
                                "Ad": name
                            };
                        }

                        function readOCR() {
                            const fileInput = document.getElementById('arkayuz_resim');

                            if (!fileInput.files.length) {
                                alert("Lütfen bir resim seçin.");
                                return;
                            }

                            const image = fileInput.files[0];
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                const imageDataUrl = e.target.result;

                                const img = new Image();
                                img.onload = function() {
                                    // 🔽 Alt %40'lık kısmı kes
                                    const mrzHeight = Math.floor(img.height * 0.4);
                                    const mrzY = img.height - mrzHeight;

                                    const croppedCanvas = document.createElement('canvas');
                                    croppedCanvas.width = img.width;
                                    croppedCanvas.height = mrzHeight;

                                    const croppedCtx = croppedCanvas.getContext('2d');
                                    croppedCtx.drawImage(img, 0, mrzY, img.width, mrzHeight, 0, 0, img.width, mrzHeight);

                                    // 🔄 Gri tonlama işlemi
                                    const imageData = croppedCtx.getImageData(0, 0, croppedCanvas.width, croppedCanvas.height);
                                    const data = imageData.data;

                                    for (let i = 0; i < data.length; i += 4) {
                                        const avg = (data[i] + data[i + 1] + data[i + 2]) / 3;
                                        data[i] = avg;
                                        data[i + 1] = avg;
                                        data[i + 2] = avg;
                                    }

                                    croppedCtx.putImageData(imageData, 0, 0);
                                    const grayImageDataUrl = croppedCanvas.toDataURL();

                                    Tesseract.recognize(
                                        grayImageDataUrl,
                                        'tur', {
                                            logger: m => console.log(m) // Konsola loglar, kullanıcı görmez
                                        }
                                    ).then(({
                                        data: {
                                            text
                                        }
                                    }) => {
                                        const result = parseMRZ(text);
                                        if (result.error) {
                                            alert("OCR hatası: " + result.error);
                                        } else {
                                            // Otomatik input doldurma
                                            document.querySelector('input[name="ad"]').value = result["Ad"] || '';
                                            document.querySelector('input[name="soyad"]').value = result["Soyad"] || '';
                                            document.querySelector('input[name="tc"]').value = result["T.C. No"] || '';
                                            document.querySelector('input[name="dogum_tarihi"]').value = result["Doğum Tarihi"] || '';
                                            document.querySelector('input[name="belge_numarası"]').value = result["Seri No"] || '';
                                        }
                                    }).catch(err => {
                                        alert("OCR işlemi sırasında hata oluştu: " + err.message);
                                    });
                                };

                                img.src = imageDataUrl;
                            };

                            reader.readAsDataURL(image);
                        }
                    </script>