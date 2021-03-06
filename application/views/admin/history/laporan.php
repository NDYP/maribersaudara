<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laporan</title>
    <link rel="stylesheet" href="">

    <link rel="stylesheet" href="<?= base_url('assets'); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="<?= base_url('assets'); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/bootstrap-4.3.1/dist/css/bootstrap.min.css">
    <?php foreach ($profil as $x) : ?>
    <link rel="icon" href="<?= base_url('assets/foto/profil/' . $x['logo']) ?>" type="image/x-icon">
    <?php endforeach; ?>
</head>

<body>
    <?php foreach ($profil as $x) : ?>
    <img src="<?= base_url('assets/foto/profil/' . $x['logo']) ?>"
        style="padding:0%;position: absolute; width: 80px; height: auto;">
    <?php endforeach; ?>
    <table style="width: 100%;">
        <tr>
            <td align="center">
                <span style="line-height: 1; font-weight: bold;">
                    <font style="line-height: 0.9;" face="Arial" font size="16">
                        Rental Mari Bersaudara</font>

                </span>
                <p style="line-height: 1; margin:2px;">
                    <font face="Arial" font size=10px>
                        <br>
                        Alamat : <?php foreach ($profil as $x) : ?>
                        <?= $x['alamat']; ?>
                        <br>
                        E-mail : <?= $x['email']; ?> - Kontak : <?= $x['no_hp']; ?>

                        <?php endforeach; ?>

                </p>
            </td>
        </tr>
    </table>
    <br>
    <hr>
    <br>
    <p align="left">
        <font face="Times New Roman" font size="">
            Laporan Akhir Bulan <?= bulan(); ?><br>
    </p>
    <font font-size=10px face="Times New Roman"><b>Transaksi</b>
        <table style="font-size: 14px;" class="table-responsive" style="width: 100%; page-break-after: always;"
            border="1" cellspacing="0">
            <tr>
                <th>No.</th>
                <th>Penyewa</th>
                <th>Mobil</th>

                <th>Pinjam - Kembali</th>

                <th>Rincian</th>
                <th>Potongan jasa rental</th>
            </tr>
            <?php $no = 0;
            foreach ($pemasukan_transaksi as $x) : $no++ ?>
            <tr>
                <td>
                    <center> <?= $no; ?> </center>
                </td>
                <td>NIK : <?= $x['nik'] ?> <br>
                    Nama : <?= $x['nama_lengkap'] ?></td>
                <td>
                    <?= $x['jenis'] ?> <?= $x['tahun'] ?></td>

                <td><?= date('d-m-Y', strtotime($x['tanggal_pinjam'])); ?> <br> sampai <br>
                    <?= date('d-m-Y', strtotime($x['tanggal_kembali'])); ?></td>
                <td>Tarif : <?= "Rp." . number_format($x['tarif'], 2, ',', '.') ?> -
                    <?= $x['diskon'] ?>% (diskon)
                    <br>
                    DP : <?= "Rp." . number_format($x['dp'], 2, ',', '.') ?>
                    <br>
                    Denda : <?= "Rp." . number_format($x['denda'], 2, ',', '.') ?>
                    <br>
                    Total Bayar : <?= "Rp." . number_format($x['bayar'], 2, ',', '.') ?>
                </td>
                <td>
                    <?= "Rp." . number_format($x['sewa'], 2, ',', '.') ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th align="center" colspan="4">Total Pemasukan</th>
                <td colspan="2"> <b>
                        <?php $no = 0;
                        foreach ($pemasukan_total as $x) : $no++ ?>
                        <?= "Rp." . number_format($x['x'] - $x['y'] + $x['dp'], 2, ',', '.') ?>
                    </b></td>
                <?php endforeach; ?>

            </tr>
        </table>
    </font>
    <br>
    <br>




    <table class="table-responsive" style="width: 100%; page-break-after: always;" border="" cellspacing="0">
        <tr>
            <td></td>
            <td>
                <p align="left">
                    <font face="Times New Roman" font size="">
                        Palangkaraya, <?= tanggal(); ?><br>
                </p>
            </td>
        </tr>
        <tr>
            <td width="350">
                <font face="Times New Roman" font size="">Bendahara <br>Rental Mari Bersaudara<br> <br>
                    <br><br><br>
                    <p>Meltha</p>
            </td>
            <td align="left">
                <font face="Times New Roman" font size="">Owner <br>Rental Mari Bersaudara<br> <br> <br><br><br>
                    <p>Meltha</p>
            </td>
        </tr>
    </table>

</body>

</html>