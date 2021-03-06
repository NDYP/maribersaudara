<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('admin/beranda/index'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?= base_url('admin/profil/index'); ?>"><?= $title; ?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <form enctype="multipart/form-data" role="form" action="<?= base_url('admin/profil/visi'); ?>" method="post"
                class="form-horizontal">

                <!-- /.col (left) -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <!-- /.box -->
                    <div class="box ">
                        <div class="box-header with-border">
                            <a href="<?= base_url('admin/profil'); ?>"
                                class="btn bg-green-gradient btn-social btn-flat btn btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                                title="Kembali"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                        <div class="box-body">


                            <div class="col-sm-12">
                                <h3>Misi</h3>
                            </div>
                            <br>
                            <div class="col-sm-12">
                                <input type="hidden" name="id_profil" value="<?= $index['id_profil']; ?>">
                                <div class="box-body pad">
                                    <form>
                                        <textarea class="textarea" placeholder=""
                                            style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                            value="<?= $index['visi']; ?>" name="visi">
                                        <?= $index['visi']; ?>
                                        </textarea>
                                    </form>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="box-footer">

                            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-left"><i
                                    class="fa fa-check"></i> Simpan</button>
                        </div>
                        <!-- /.box -->
                    </div>
            </form>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->