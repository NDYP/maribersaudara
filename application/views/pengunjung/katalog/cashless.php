<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="SB-Mid-client-BYWgAXMORF1cj8x_"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<section class="inner-page-banner bg_img overlay-3"
    data-background="<?= base_url('assets/depan/') ?>assets/images/inner-page-bg.jpg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Transaksi</h2>
                <ol class="page-list">
                    <li><a href="<?= base_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
                    <li><a href="<?= base_url('katalog') ?>">Katalog</a></li>
                    <li>Transaksi</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- inner-apge-banner end -->

<!-- reservation-section start -->
<section class="reservation-section pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <div class="reservation-details-area">
                    <div class="thumb">
                        <img src="<?= base_url('assets/foto/mobil/' . $mobil['thumbnail']); ?>" alt="images">
                    </div>
                    <div class="content">
                        <div class="content-block">
                            <h3 class="car-name"><?= $mobil['jenis'] ?> - Diskon <?= $mobil['diskon'] ?>%</h3>
                            <h3 class="price">Tarif <?= $mobil['tarif'] ?> per hari</h3>
                            <p><?= $mobil['info'] ?></p>
                        </div>

                        <form class="reservation-form" id="payment-form" method="post" action="">
                            <div class="content-block">
                                <h3 class="title">Form Penyewaan</h3>
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <input id="alamat" name="alamat" value="<?= set_value('alamat') ?>" type="text"
                                            placeholder="Alamat Peminjam">
                                        <input id="id_mobil" name="id_mobil" value="<?= $mobil['id_mobil']; ?>"
                                            type="hidden" placeholder="Alamat Peminjam">
                                        <input type="hidden" name="result_type" id="result-type" value="">
                                        <input type="hidden" name="result_data" id="result-data" value="">
                                        <?= form_error('alamat', '<small class="text-danger pl-1">', '</small>'); ?>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <input id="dp" name="dp" value="<?= set_value('dp') ?>" type="text"
                                            placeholder="Jumlah DP (Nominal tanpa titik koma)">

                                    </div>
                                    <div class="form-group col-md-6">
                                        <i class="fa fa-calendar"></i>
                                        <input id="tanggal_pinjam" name="tanggal_pinjam"
                                            value="<?= set_value('tanggal_pinjam') ?>" type='text'
                                            class='form-control has-icon datepicker-here' data-language='en'
                                            placeholder="Tanggal Sewa">
                                        <?= form_error('tanggal_pinjam', '<small class="text-danger pl-1">', '</small>'); ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <i class="fa fa-calendar"></i>
                                        <input id="tanggal_kembali" name="tanggal_kembali"
                                            value="<?= set_value('tanggal_kembali') ?>" type='text'
                                            class='form-control has-icon datepicker-here' data-language='en'
                                            placeholder="Tanggal Pengembalian">
                                        <?= form_error('tanggal_kembali', '<small class="text-danger pl-1">', '</small>'); ?>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <select id="opsi" name="opsi">
                                            <option name="opsi" value="">--Pilih--</option>
                                            <option name="opsi" value="ambil">Ambil</option>
                                            <option name="opsi" value="antar">Antar</option>
                                        </select>
                                        <?= form_error('opsi', '<small class="text-danger pl-1">', '</small>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="content-block">
                                <!-- <h3 class="title">addisonal information</h3> -->
                                <div class="row">
                                    <!-- <div class="col-lg-12 form-group">
                                        <textarea placeholder="Write addisonal information in here"></textarea>
                                    </div> -->
                                    <div class="col-lg-12">
                                        <button type="submit" class="cmn-btn">Konfirmasi</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</section>