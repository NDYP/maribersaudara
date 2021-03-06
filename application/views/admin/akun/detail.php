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
                <li><a href="<?= base_url('admin/admin/index'); ?>">Karyawan</a></li>
                <li class="active"><?= $title; ?></li>
            </ol>
        </section>
        <!-- Main content -->
        <!-- Main content -->
        <section class="content">

            <div class="box box-default">
                <div class="box-header with-border">
                    <a href="<?= base_url('admin/akun/edit') ?>" class="btn btn-xs bg-blue"><span
                            class="fa fa-edit"></span>
                        Edit</a>
                    <a href="<?= base_url('admin/akun/berkas') ?>" class="btn btn-xs bg-blue"><span
                            class="fa fa-plus"></span>
                        Upload</a>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <center>

                                <?php if ($akun['foto']) : ?>
                                <a href="<?= base_url('admin/akun/download/' . $akun['id_pengguna']); ?>">
                                    <img class="img center-block img-responsive profile-user-img"
                                        src="<?= base_url('assets/foto/pengguna/' . $akun['foto']); ?>" alt=""
                                        style="width: auto;max-height: 500px;max-width: 500px; vertical-align: left;">
                                </a>
                                <?php else : ?>

                                <img class="img center-block img-responsive profile-user-img"
                                    src="<?= base_url('assets/foto/profil.png') ?>" alt=""
                                    style="width: auto;max-height: 500px;max-width: 500px; vertical-align: left;">

                                <?php endif; ?>
                            </center>
                            <table id="" class="table table-striped dataTable" role="grid"
                                aria-describedby="example1_info">
                                <tbody>
                                    <tr>
                                        <th>NIK</th>
                                        <th> : </th>
                                        <td><?= $akun['nik'] ?></td>
                                        <td></td>
                                        <th>Jenis Kelamin</th>
                                        <th> : </th>
                                        <td><?= $akun['jenis_kelamin'] ?></td>
                                        <td></td>
                                        <th>Email</th>
                                        <th> : </th>
                                        <td><?= $akun['email'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nama</th>
                                        <th> : </th>
                                        <td><?= $akun['nama_lengkap'] ?></td>
                                        <td></td>
                                        <th>Alamat</th>
                                        <th> : </th>
                                        <td><?= $akun['alamat'] ?></td>
                                        <td></td>
                                        <th>No.hp/WA</th>
                                        <th> : </th>
                                        <td><?= $akun['no_hp'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Lahir</th>
                                        <th> : </th>
                                        <td><?= $akun['tempat_lahir'];
                                            echo ', ' ?><?= date('d-m-Y', strtotime($akun['tanggal_lahir'])); ?>
                                        </td>
                                        <td></td>
                                        <th>No.hp/WA</th>
                                        <th> : </th>
                                        <td></td>
                                        <td></td>
                                        <th></th>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <h3 class="text-center"><u>Berkas</u></h3>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
                                aria-describedby="example1_info">
                                <thead>
                                    <tr role="row">
                                        <th style="width: 2%" class="sorting_asc" tabindex="0" aria-controls="example1"
                                            rowspan="1" colspan="1" aria-sort="ascending"
                                            aria-label="Rendering engine: activate to sort column descending"
                                            style="width: 177.281px;">No</th>
                                        <th style="width: 224.844px;">Judul</th>
                                        <th style="width: 206.484px;">Berkas</th>
                                        <th style="width: 152.688px;">Tanggal</th>
                                        <th style="width: 15%" style="width: 111.703px;">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($berkas as $x) : ?>
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"><?= $no++; ?></td>
                                        <td><?= $x['judul'] ?></td>
                                        <td><a
                                                href="<?= base_url('admin/berkas/download/' . $x['id_pemilik']); ?>"><?= $x['berkas'] ?></a>
                                        </td>
                                        <td> <?= date('d-m-Y', strtotime($x['daftar'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn bg-green btn-social btn-flat btn-xs"
                                                    data-toggle="dropdown"><i class="fa fa-arrow-circle-down"></i>
                                                    Pilih</button>
                                                <ul class="dropdown-menu" role="menu">

                                                    <li>
                                                        <a href="<?= base_url('admin/admin/hapusberkas/' . $x['id_berkas']); ?>"
                                                            class="btn btn-social btn-flat btn-block btn-sm tombol-hapus"><i
                                                                class="fa fa-trash-o"></i> Hapus</a>
                                                    </li>
                                                </ul>
                                            </div>

                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

        </section>
        <!-- /.content -->
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->