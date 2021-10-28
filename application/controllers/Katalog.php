<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Katalog extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Akun');
        $this->load->model('M_Mobil');
        $this->load->model('M_Transaksi');
        $this->load->model('M_Customer');
        $this->load->model('M_Profil');
    }
    function index()
    {
        $data['title'] = "Index Mobil";
        $data['index'] = $this->M_Mobil->index();
        $data['kontak'] = $this->M_Profil->index();
        $this->load->view('pengunjung/template/header', $data);
        $this->load->view('pengunjung/katalog/index', $data);
        $this->load->view('pengunjung/template/footer', $data);
    }
    function sewa()
    {
        $this->form_validation->set_rules('alamat', 'alamat', 'required|trim', [
            'required' => 'Alamat Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('tanggal_pinjam', 'tanggal_pinjam', 'required|trim', [
            'required' => 'Tanggal Sewa Tidak Boleh Kosong!',
        ]);
        $this->form_validation->set_rules('tanggal_kembali', 'tanggal_kembali', 'required|trim', [
            'required' => 'Tanggal Kembali Tidak Boleh Kosong!'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Checkout";
            $data['id_mobil'] = $this->uri->segment(3, 0);
            $data['mobil'] = $this->db->get_where('mobil', array('id_mobil' => $data['id_mobil']))->row_array();
            $data['kontak'] = $this->M_Profil->index();
            //$data['penyewa'] = $this->M_Customer->index();
            $this->load->view('pengunjung/template/header', $data);
            $this->load->view('pengunjung/katalog/tambah', $data);
            $this->load->view('pengunjung/template/footer', $data);
        } else {
            $id_mobil
                = $this->input->post('id_mobil');
            $x = $this->db->get_where('mobil', array('id_mobil' => $id_mobil))->row_array();
            $tarif = $x['tarif'];
            $diskon = $x['diskon'];

            $id_penyewa = $this->session->userdata('id_pengguna');
            $alamat = $this->input->post('alamat');

            $opsi = $this->input->post('opsi');
            $tanggal_pinjam =
                date('Y-m-d', strtotime($this->input->post('tanggal_pinjam')));
            $tanggal_kembali =
                date('Y-m-d', strtotime($this->input->post('tanggal_kembali')));
            $diff = strtotime($tanggal_pinjam) - strtotime($tanggal_kembali);
            // 1 day = 24 hours 
            // 24 * 60 * 60 = 86400 seconds
            $berapa_hari = ceil(abs($diff / 86400));
            $tanggal_transaksi = date('Y-m-d');
            $dp = $this->input->post('dp');

            $jl_tarif = $tarif - (($tarif * $diskon) / 100);

            //$denda = $this->input->post('denda');
            $tanggal_lewat = date('Y-m-d');
            $tanggal_kembali =
                date('Y-m-d', strtotime($this->input->post('tanggal_kembali')));
            $diff = strtotime($tanggal_lewat) - strtotime($tanggal_kembali);
            // 1 day = 24 hours 
            // 24 * 60 * 60 = 86400 seconds
            $jml_hari_lewat = ceil(abs($diff / 86400));
            $denda = $jml_hari_lewat * $jl_tarif;


            $data = array(
                'id_penyewa' => $id_penyewa,
                'id_mobil' => $id_mobil,
                'alamat' => $alamat,
                'status' => 'pengajuan',
                'opsi' => $opsi,
                'tanggal_pinjam' => $tanggal_pinjam,
                'tanggal_kembali' => $tanggal_kembali,
                'tanggal_transaksi' => $tanggal_transaksi,
                'dp' => $dp,
                'denda' => $denda,
                'bayar' => $jl_tarif * $berapa_hari  + $denda - $dp,
            );
            $this->M_Transaksi->tambah('transaksi', $data);
            $this->_sendmail();
            $this->session->set_flashdata('success', 'Tunggu konfirmasi pihak rental dan cek email');
            redirect('katalog/index');
        }
    }
    private function _sendmail()
    {
        $customer = $this->session->userdata('id_pengguna');
        $user = $this->db->get_where('pengguna', ['id_pengguna' => $customer])->row_array();

        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' => 2525,
            'smtp_user' => '59b2958d9247bd',
            'smtp_pass' => '20abef486bad12',
            'crlf' => "\r\n",
            'newline' => "\r\n"
        );

        $this->load->library('email', $config);
        $this->email->from('rental_maribersaudara@gmail.com');
        $this->email->to($user['email']);
        $this->email->subject('Penyewaan Mobil | Rental Mari Bersaudara');
        $this->email->message('Penyewaan mobil telah berhasil tunggu dikonfirmasi oleh pihak rental melalui telepon.');

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }
    function ubah()
    {
        $config['upload_path'] = './assets/foto/pengguna/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
        $config['file_name'] = $this->input->post('nik');
        $this->upload->initialize($config);
        $config['upload_path'] = './assets/foto/pengguna/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
        $config['file_name'] = $this->input->post('nik');
        $this->upload->initialize($config);
        if (!empty($_FILES['foto']['name'])) {
            if ($this->upload->do_upload('foto')) {
                $gbr = $this->upload->data();
                $config['image_library'] = 'gd2';
                $config['source_image'] = './assets/foto/pengguna/' . $gbr['file_name'];
                $config['maintain_ratio'] = FALSE;
                $config['overwrite'] = TRUE;
                $config['max_size']  = 1024;
                $config['new_image'] = './assets/foto/pengguna/' . $gbr['file_name'];
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                $file = $gbr['file_name'];
                $nik = $this->input->post('nik');
                $nama_lengkap = $this->input->post('nama_lengkap');
                $email = $this->input->post('email');

                $alamat = $this->input->post('alamat');
                $no_hp = $this->input->post('no_hp');
                $jenis_kelamin = $this->input->post('jenis_kelamin');
                $tempat_lahir = $this->input->post('tempat_lahir');

                $x = $this->input->post('tanggal_lahir');
                $tanggal_lahir = date('Y-m-d', strtotime($x));
                $id_pengguna = $this->session->userdata('id_pengguna');
                $username = $this->input->post('username');
                $password = $this->input->post('password');

                $data = array(
                    'foto' => $file,
                    'nik' => $nik,
                    'nama_lengkap' => $nama_lengkap,
                    'alamat' => $alamat,
                    'no_hp' => $no_hp,
                    'email' => $email,
                    'tempat_lahir' => $tempat_lahir,
                    'tanggal_lahir' => $tanggal_lahir,
                    'jenis_kelamin' => $jenis_kelamin,
                    'username' => $username,
                    'password' => $password,
                );
                $this->M_Akun->update('pengguna', $data, array('id_pengguna' => $id_pengguna));
                $this->session->set_flashdata('success', 'Berhasil ubah data');
                redirect('pengunjung/transaksi/profil', 'refresh');
            } else {

                $this->session->set_flashdata('info', 'Gagal ubah data');
                redirect('pengunjung/transaksi/profil', 'refresh');
            }
        } else {
            $nik = $this->input->post('nik');
            $nama_lengkap = $this->input->post('nama_lengkap');
            $email = $this->input->post('email');
            $alamat = $this->input->post('alamat');

            $no_hp = $this->input->post('no_hp');
            $jenis_kelamin = $this->input->post('jenis_kelamin');
            $tempat_lahir = $this->input->post('tempat_lahir');
            $x = $this->input->post('tanggal_lahir');
            $tanggal_lahir = date('Y-m-d', strtotime($x));

            $id_pengguna = $this->session->userdata('id_pengguna');
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $data = array(
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'email' => $email,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'jenis_kelamin' => $jenis_kelamin,
                'username' => $username,
                'password' => $password,
            );
            $this->M_Akun->update('pengguna', $data, array('id_pengguna' => $id_pengguna));
            $this->session->set_flashdata('success', 'Berhasil ubah data');
            redirect('pengunjung/transaksi/profil', 'refresh');
        }
    }
    public function hapusberkas($id_berkas)
    {
        $data =
            $this->db->get_where('berkas', array('id_berkas' => $id_berkas))->row_array();
        if ($data) {
            $this->M_Pengguna->hapusberkas($id_berkas);
            $this->session->set_flashdata('success', 'Berhasil Hapus Data');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('Info', 'Gagal Hapus Data');
            redirect('pengunjung/profil/profil', 'refresh');
        }
    }
    function berkas()
    {
        $this->form_validation->set_rules('judul', 'judul', 'required|trim', [
            'required' => 'Judul Berkas Tidak Boleh Kosong!'
        ]);
        if (empty($_FILES['berkas']['name'])) {
            $this->form_validation->set_rules('berkas', 'berkas', 'required', [
                'required' => 'File Tidak Boleh Kosong!'
            ]);
        }
        if ($this->form_validation->run() == FALSE) {
            $id_pengguna = $this->session->userdata('id_pengguna');
            $data['index'] = $this->M_Pengguna->index();
            $data['index2'] = $this->db->get_where('pengguna', array('id_pengguna' => $id_pengguna))->row_array();
            $data['berkas'] = $this->db->get_where('berkas', array('id_pemilik' => $id_pengguna))->result_array();
            if ($data) {
                $this->session->set_flashdata('info', 'Form Upload Tidak Boleh Kosong');
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata('info', 'Tidak Ada Data');
                redirect('pengunjung/transaksi/profil', 'refresh');
            }
        } else {
            $config['upload_path'] = './assets/berkas/pengguna/';
            $config['allowed_types'] = 'pdf';
            $config['file_name'] = $this->input->post('nik');
            $this->upload->initialize($config);
            if ($this->upload->do_upload('berkas')) {
                $gbr = $this->upload->data();
                $config['image_library'] = 'gd2';
                $config['source_image'] = './assets/berkas/pengguna/' . $gbr['file_name'];
                $config['maintain_ratio'] = FALSE;
                $config['overwrite'] = TRUE;
                $config['max_size']  = 1024;
                $config['new_image'] = './assets/berkas/pengguna/' . $gbr['file_name'];
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                $file = $gbr['file_name'];

                $id_pemilik = $this->session->userdata('id_pengguna');
                $judul = $this->input->post('judul');
                $tanggal = date('Y-m-d');
                $data = array(
                    'berkas' => $file,
                    'judul' => $judul,
                    'daftar' => $tanggal,
                    'id_pemilik' => $id_pemilik,
                );
                $this->M_Pengguna->tambah('berkas', $data);
                $this->session->set_flashdata('success', 'Berhasil Upload Berkas');
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata('info', 'Gagal Upload Berkas');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }
    function mobil()
    {
        $this->form_validation->set_rules('tipe', 'tipe', 'required|trim', [
            'required' => 'Tipe Mobil Tidak Boleh Kosong!',
        ]);
        $this->form_validation->set_rules('jenis', 'jenis', 'required|trim', [
            'required' => 'Jenis Mobil Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('warna', 'warna', 'required|trim', [
            'required' => 'Warna Mobil Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('jumlah_kursi', 'jumlah_kursi', 'required|trim', [
            'required' => 'Jumlah Kursi Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('transmisi', 'transmisi', 'required|trim', [
            'required' => 'Jenis Transmisi Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('sewa', 'sewa', 'required|trim', [
            'required' => 'Biaya Sewa Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('tarif', 'tarif', 'required|trim', [
            'required' => 'Tarif Sewa Tidak Boleh Kosong!'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('info', 'Form Upload Tidak Boleh Kosong');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $config['upload_path'] = './assets/foto/mobil/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
            $config['file_name'] =
                $this->session->userdata('id_pengguna');
            $this->upload->initialize($config);
            if (!empty($_FILES['thumbnail']['name'])) {
                if ($this->upload->do_upload('thumbnail')) {
                    $gbr = $this->upload->data();
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = './assets/foto/mobil/' . $gbr['file_name'];
                    $config['maintain_ratio'] = FALSE;
                    $config['overwrite'] = TRUE;
                    $config['max_size']  = 1024;
                    $config['new_image'] = './assets/foto/mobil/' . $gbr['file_name'];
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                    $file = $gbr['file_name'];

                    $id_pemilik =
                        $this->session->userdata('id_pengguna');
                    $tipe = $this->input->post('tipe');
                    $jenis = $this->input->post('jenis');
                    $warna = $this->input->post('warna');
                    $jumlah_kursi = $this->input->post('jumlah_kursi');
                    $transmisi = $this->input->post('transmisi');
                    $sewa = $this->input->post('sewa');
                    $tarif = $this->input->post('tarif');
                    $diskon = $this->input->post('tanggal_lahir');
                    $status = 'pengajuan';
                    $info = $this->input->post('info');
                    $daftar = date('Y-m-d');
                    $data = array(
                        'thumbnail' => $file,
                        'id_pemilik' => $id_pemilik,
                        'tipe' => $tipe,
                        'warna' => $warna,
                        'jumlah_kursi' => $jumlah_kursi,
                        'jenis' => $jenis,
                        'transmisi' => $transmisi,
                        'sewa' => $sewa,
                        'tarif' => $tarif,
                        'diskon' => $diskon,
                        'status' => $status,
                        'info' => $info,
                        'daftar' => $daftar,
                    );
                    $this->M_Mobil->tambah('mobil', $data);
                    $this->session->set_flashdata('success', 'Berhasil tambah data');
                    redirect('pengunjung/transaksi/profil', 'refresh');
                } else {
                    $this->session->set_flashdata('info', 'Gagal tambah data');
                    redirect('pengunjung/transaksi/profil', 'refresh');
                }
            } else {
                $id_pemilik =
                    $this->session->userdata('id_pengguna');
                $tipe = $this->input->post('tipe');
                $jenis = $this->input->post('jenis');
                $warna = $this->input->post('warna');
                $jumlah_kursi = $this->input->post('jumlah_kursi');
                $transmisi = $this->input->post('transmisi');
                $sewa = $this->input->post('sewa');
                $tarif = $this->input->post('tarif');
                $diskon = $this->input->post('tanggal_lahir');
                $status = 'pengajuan';
                $info = $this->input->post('info');
                $daftar = date('Y-m-d');
                $data = array(
                    'id_pemilik' => $id_pemilik,
                    'tipe' => $tipe,
                    'warna' => $warna,
                    'jumlah_kursi' => $jumlah_kursi,
                    'jenis' => $jenis,
                    'transmisi' => $transmisi,
                    'sewa' => $sewa,
                    'tarif' => $tarif,
                    'diskon' => $diskon,
                    'status' => $status,
                    'info' => $info,
                    'daftar' => $daftar,
                );
                $this->M_Mobil->tambah('mobil', $data);
                $this->session->set_flashdata('success', 'Berhasil tambah data');
                redirect('pengunjung/transaksi/profil', 'refresh');
            }
        }
    }
    public function hapusmobil($id_mobil)
    {
        $data =
            $this->db->get_where('mobil', array('id_mobil' => $id_mobil))->row_array();
        if ($data) {
            $this->M_Akun->hapusmobil($id_mobil);
            $this->session->set_flashdata('success', 'Berhasil Hapus Data');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('Info', 'Gagal Hapus Data');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    function logout()
    {
        $this->session->sess_destroy();
        redirect('pengunjung/login/index', 'refresh');
    }
}