<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?= $title; ?>
                <small><?= $title2; ?></small>

            </h1>
            <ol class="breadcrumb">
                <li><a href="<?= base_url('admin/dashboard/index'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?= base_url('admin/mobil/index'); ?>">Karyawan</a></li>
                <li class="active"><?= $title; ?></li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <a class="btn btn-xs bg-blue" href="<?= base_url('admin/mobil/index') ?>"><span
                            class="fa fa-arrow-left"></span>
                        Kembali</a>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Pemilik</label>
                                    <select name="id_pemilik" class="form-control select2" style="width: 100%;">
                                        <option selected="selected" value="<?= set_value('id_fat'); ?>">Pilih</option>
                                        <?php foreach ($pemilik as $x) : ?>
                                        <option
                                            value=<?= $x['id_pengguna'] ?><?= set_select('id_pemilik', $x['id_pengguna']); ?>>
                                            <?= $x['nama_lengkap'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('id_pemilik', '<small class="text-danger pl-1">', '</small>'); ?>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label">Jenis</label>
                                    <input type="text" class="form-control" placeholder="Jenis" name="jenis"
                                        value="<?= set_value('jenis'); ?>">
                                    <?= form_error('jenis', '<small class="text-danger pl-1">', '</small>'); ?>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label">Tahun Produksi</label>
                                    <input type="text" class="form-control" placeholder="Tahun" name="tahun"
                                        value="<?= set_value('tahun'); ?>">
                                    <?= form_error('tahun', '<small class="text-danger pl-1">', '</small>'); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label">Warna</label>
                                    <input type="text" class="form-control" placeholder="Warna" name="warna"
                                        value="<?= set_value('warna'); ?>">
                                    <?= form_error('warna', '<small class="text-danger pl-1">', '</small>'); ?>

                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label">Jumlah Kursi</label>
                                    <input type="text" class="form-control" placeholder="Jumlah Kursi"
                                        name="jumlah_kursi" value="<?= set_value('jumlah_kursi'); ?>">
                                    <?= form_error('jumlah_kursi', '<small class="text-danger pl-1">', '</small>'); ?>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label">Transmisi</label>
                                    <select name="transmisi" class="form-control select2" style="width: 100%;">
                                        <option selected="selected" value="<?= set_value('transmisi'); ?>">Pilih
                                        </option>
                                        <option value="Manual" <?= set_select('transmisi', 'Manual'); ?>>Manual
                                        </option>
                                        <option value="Matic" <?= set_select('transmisi', 'Matic'); ?>>Matic</option>
                                    </select>
                                    <?= form_error('transmisi', '<small class="text-danger pl-1">', '</small>'); ?>
                                </div>

                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label">Tarif</label>
                                    <input type="text" class="form-control" placeholder="Harga Sewa/hari" name="tarif"
                                        value="<?= set_value('tarif'); ?>">
                                </div>
                                <?= form_error('tarif', '<small class="text-danger pl-1">', '</small>'); ?>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label">Diskon</label>
                                    <input type="text" class="form-control" placeholder="Tanpa tanda baca" name="diskon"
                                        value="<?= set_value('diskon'); ?>">
                                </div>
                                <?= form_error('diskon', '<small class="text-danger pl-1">', '</small>'); ?>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail" class="control-label">Bagian Rental/Hari</label>
                                    <input type="text" class="form-control" placeholder="" name="bagian_rental"
                                        value="<?= set_value('bagian_rental'); ?>">
                                    <?= form_error('bagian_rental', '<small class="text-danger pl-1">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputExperience" class="control-label" name="">Foto</label>
                                    <input accept=".jpg, .jpeg, .png" title="Hanya tipe Foto" type="file"
                                        name="thumbnail"></input>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Info Tambahan</label>
                                    <textarea type="text" class="form-control" name="info" id="password">

                                    </textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        <button class="btn btn-xs bg-blue"><span class="fa fa-save"></span> Simpan</button>
                    </div>
                </form>
                <!-- /.box -->
            </div>
        </section>
        <!-- /.content -->
        <!-- /.content -->
    </div>
</div>

<!-- /.content-wrapper -->