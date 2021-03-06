<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mobil extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        login();
        akses();
        $this->load->model('M_Mobil');
        $this->load->model('M_Partner');
        $this->load->model('M_Profil');
        $this->load->model('M_Kontak');
    }
    function index()
    {
        $data['profil'] = $this->M_Profil->index();

        $data['pengajuan_partner'] = $this->db->get_where('pengguna', array('id_akses' => 6))->num_rows();
        $data['pengajuan_mobil'] = $this->db->get_where('mobil', array('status' => 'pengajuan'))->num_rows();
        $data['pesan'] = $this->db->get_where('pesan', array('status' => 'unread'))->num_rows();
        $data['pesan_index'] = $this->db->get_where('pesan', array('status' => 'unread'))->result_array();
        $data['title'] = "Mobil";
        $data['title2'] = "Index Data";

        $data['index'] = $this->M_Mobil->index();
        $data['pemilik'] = $this->M_Partner->index();
        $this->load->view('admin/template/header', $data);
        $this->load->view('admin/mobil/index', $data);
        $this->load->view('admin/template/footer', $data);
    }
    public function lihat($id_mobil)
    {
        $data['profil'] = $this->M_Profil->index();
        $data['mobil'] = $this->M_Mobil->get($id_mobil);
        $data['berkas'] = $this->db->get_where('berkas', array('id_pemilik' => $id_mobil))->result_array();
        //$data['mobil'] = $this->db->get_where('mobil', array('id_mobil' => $id_mobil))->result_array();
        $data['pesan'] = $this->db->get_where('pesan', array('status' => 'unread'))->num_rows();
        $data['pesan_index'] = $this->db->get_where('pesan', array('status' => 'unread'))->result_array();
        $data['title'] = "Mobil";
        $data['title2'] = "Detail";
        $data['pengajuan_partner'] = $this->db->get_where('pengguna', array('id_akses' => 6))->num_rows();
        $data['pengajuan_mobil'] = $this->db->get_where('mobil', array('status' => 'pengajuan'))->num_rows();
        if ($data) {

            $this->load->view('admin/template/header', $data);
            $this->load->view('admin/mobil/detail', $data);
            $this->load->view('admin/template/footer', $data);
        } else {
            $this->session->set_flashdata('Info', 'Tidak Ada Data');
            redirect('admin/mobil/index', 'refresh');
        }
    }
    function tambahberkas($id_mobil)
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
            //$id_mobil = $this->uri->segment(4);
            $data['profil'] = $this->M_Profil->index();
            $data['pengajuan_partner'] = $this->db->get_where('pengguna', array('id_akses' => 6))->num_rows();
            $data['pengajuan_mobil'] = $this->db->get_where(
                'mobil',
                array('status' => 'pengajuan')
            )->num_rows();
            $data['pesan'] = $this->db->get_where('pesan', array('status' => 'unread'))->num_rows();
            $data['pesan_index'] = $this->db->get_where('pesan', array('status' => 'unread'))->result_array();
            $data['title'] = "Mobil";
            $data['title2'] = "Index Data";

            $data['index2'] = $this->M_Mobil->get($id_mobil);
            $data['berkas'] = $this->db->get_where('berkas', array('id_pemilik' => $id_mobil))->result_array();
            // $data['galeri'] = $this->db->get_where('galeri', array('id_mobil' => $id_mobil))->result_array();
            $this->load->view('admin/template/header', $data);
            $this->load->view('admin/mobil/berkas', $data);
            $this->load->view('admin/template/footer', $data);
        } else {
            $config['upload_path'] = './assets/berkas/pengguna/';
            $config['allowed_types'] = 'pdf';
            $config['file_name'] = $this->input->post('judul');
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
                $judul = $this->input->post('judul');
                $id_pemilik = $this->input->post('id_pemilik');
                $tanggal = date('Y-m-d');
                $data = array(
                    'berkas' => $file,
                    'judul' => $judul,
                    'daftar' => $tanggal,
                    'id_pemilik' => $id_pemilik,
                );
                $this->M_Mobil->tambah('berkas', $data);
                $this->session->set_flashdata('success', 'Berhasil Upload Berkas');
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata('info', 'Gagal Upload Berkas');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }
    public function edit($id_mobil)
    {
        $this->form_validation->set_rules('id_pemilik', 'id_pemilik', 'required|trim', [
            'required' => 'Pemilik Mobil Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('tahun', 'tahun', 'required|trim', [
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

        $this->form_validation->set_rules('tarif', 'tarif', 'required|trim', [
            'required' => 'Tarif Sewa Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('bagian_rental', 'bagian_rental', 'required|trim', [
            'required' => 'Bagian rental Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('status', 'status', 'required|trim', [
            'required' => 'Tarif Sewa Tidak Boleh Kosong!'
        ]);
        if ($this->form_validation->run() == FALSE) {
            //$id_mobil = $this->uri->segment(4, 0);
            $data['title'] = "Mobil";
            $data['title2'] = "Edit Data";
            $data['pesan'] = $this->db->get_where('pesan', array('status' => 'unread'))->num_rows();
            $data['pesan_index'] = $this->db->get_where('pesan', array('status' => 'unread'))->result_array();
            $data['pengajuan_partner'] = $this->db->get_where('pengguna', array('id_akses' => 6))->num_rows();
            $data['pengajuan_mobil'] = $this->db->get_where('mobil', array('status' => 'pengajuan'))->num_rows();
            $data['profil'] = $this->M_Profil->index();

            $data['mobil'] = $this->M_Mobil->get($id_mobil);
            $this->load->view('admin/template/header', $data);
            $this->load->view('admin/mobil/edit', $data);
            $this->load->view('admin/template/footer', $data);
        } else {
            $config['upload_path'] = './assets/foto/mobil/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
            $config['file_name'] = $this->input->post('id_pemilik');
            $this->upload->initialize($config);
            if (!empty($_FILES['thumbnail']['name'])) {
                if ($this->upload->do_upload('thumbnail')) {
                    $gbr = $this->upload->data();

                    $file = $gbr['file_name'];
                    $id_pemilik = $this->input->post('id_pemilik');
                    $id_mobil = $this->input->post('id_mobil');
                    $tahun = $this->input->post('tahun');
                    $jenis = $this->input->post('jenis');
                    $warna = $this->input->post('warna');
                    $jumlah_kursi = $this->input->post('jumlah_kursi');
                    $transmisi = $this->input->post('transmisi');
                    $bagian_rental = $this->input->post('bagian_rental');

                    $tarif = $this->input->post('tarif');
                    $diskon = $this->input->post('diskon');
                    $status = $this->input->post('status');

                    $data = array(
                        'thumbnail' => $file,
                        'id_pemilik' => $id_pemilik,
                        'tahun' => $tahun,
                        'jenis' => $jenis,
                        'warna' => $warna,
                        'jumlah_kursi' => $jumlah_kursi,
                        'transmisi' => $transmisi,
                        'tarif' => $tarif,
                        'diskon' => $diskon,
                        'tarif' => $tarif,
                        'status' => $status,
                        'bagian_rental' => $bagian_rental,
                    );
                    $this->M_Mobil->update('mobil', $data, array('id_mobil' => $id_mobil));
                    $this->session->set_flashdata('success', 'Berhasil update data');
                    redirect('admin/mobil/index', 'refresh');
                } else {
                    $this->session->set_flashdata('info', 'Gagal update data');
                    redirect('admin/mobil/index', 'refresh');
                }
            } else {
                $id_pemilik = $this->input->post('id_pemilik');
                $id_mobil = $this->input->post('id_mobil');
                $tahun = $this->input->post('tahun');
                $jenis = $this->input->post('jenis');

                $warna = $this->input->post('warna');
                $jumlah_kursi = $this->input->post('jumlah_kursi');
                $transmisi = $this->input->post('transmisi');

                $tarif = $this->input->post('tarif');
                $diskon = $this->input->post('diskon');
                $status = $this->input->post('status');
                $bagian_rental = $this->input->post('bagian_rental');
                $data = array(
                    'id_pemilik' => $id_pemilik,
                    'tahun' => $tahun,
                    'jenis' => $jenis,
                    'warna' => $warna,
                    'jumlah_kursi' => $jumlah_kursi,
                    'transmisi' => $transmisi,
                    'tarif' => $tarif,
                    'diskon' => $diskon,
                    'tarif' => $tarif,
                    'status' => $status,
                    'bagian_rental' => $bagian_rental,
                );
                $this->M_Mobil->update('mobil', $data, array('id_mobil' => $id_mobil));
                $this->session->set_flashdata('success', 'Berhasil update data');
                redirect('admin/mobil/index', 'refresh');
            }
        }
    }
    public function hapus($id_mobil)
    {
        $this->M_Mobil->hapus($id_mobil);
        $this->session->set_flashdata('info', 'Berhasil Hapus Data');
        redirect('admin/mobil/index', 'refresh');
    }
    public function hapusberkas($id_berkas)
    {
        $data =
            $this->db->get_where('berkas', array('id_berkas' => $id_berkas))->row_array();
        if ($data) {
            $this->M_Mobil->hapusberkas($id_berkas);
            $this->session->set_flashdata('success', 'Berhasil Hapus Data');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('Info', 'Gagal Hapus Data');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    function tambah()
    {
        $this->form_validation->set_rules('id_pemilik', 'id_pemilik', 'required|trim', [
            'required' => 'Pemilik Mobil Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('tahun', 'tahun', 'required|trim', [
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
        $this->form_validation->set_rules('tarif', 'tarif', 'required|trim', [
            'required' => 'Tarif Sewa Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('bagian_rental', 'bagian_rental', 'required|trim', [
            'required' => 'Bagian rental Tidak Boleh Kosong!'
        ]);


        if ($this->form_validation->run() == FALSE) {
            $data['profil'] = $this->M_Profil->index();

            $data['title'] = "Mobil";
            $data['title2'] = "Tambah Data";
            $data['pengajuan_partner'] = $this->db->get_where('pengguna', array('id_akses' => 6))->num_rows();
            $data['pengajuan_mobil'] = $this->db->get_where('mobil', array('status' => 'pengajuan'))->num_rows();
            $data['pesan'] = $this->db->get_where('pesan', array('status' => 'unread'))->num_rows();
            $data['pesan_index'] = $this->db->get_where('pesan', array('status' => 'unread'))->result_array();
            $data['pemilik'] = $this->M_Partner->index();
            $this->load->view('admin/template/header', $data);
            $this->load->view('admin/mobil/tambah', $data);
            $this->load->view('admin/template/footer', $data);
        } else {
            $config['upload_path'] = './assets/foto/mobil/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
            $config['file_name'] = $this->input->post('id_pemilik');
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

                    $id_pemilik = $this->input->post('id_pemilik');
                    $tahun = $this->input->post('tahun');
                    $jenis = $this->input->post('jenis');
                    $warna = $this->input->post('warna');
                    $jumlah_kursi = $this->input->post('jumlah_kursi');
                    $transmisi = $this->input->post('transmisi');
                    $tarif = $this->input->post('tarif');
                    $diskon = $this->input->post('tanggal_lahir');
                    $bagian_rental = $this->input->post('bagian_rental');

                    $info = $this->input->post('info');
                    $daftar = date('Y-m-d');
                    $data = array(
                        'thumbnail' => $file,
                        'id_pemilik' => $id_pemilik,
                        'tahun' => $tahun,
                        'warna' => $warna,
                        'jumlah_kursi' => $jumlah_kursi,
                        'jenis' => $jenis,
                        'transmisi' => $transmisi,
                        'tarif' => $tarif,
                        'bagian_rental' => $bagian_rental,
                        'diskon' => $diskon,
                        'status' => 'Tersedia',
                        'info' => $info,
                        'daftar' => $daftar,
                    );
                    $this->M_Mobil->tambah('mobil', $data);
                    $this->session->set_flashdata('success', 'Berhasil tambah data');
                    redirect('admin/mobil/index', 'refresh');
                } else {
                    $this->session->set_flashdata('info', 'Gagal tambah data');
                    redirect('admin/mobil/index', 'refresh');
                }
            } else {
                $id_pemilik = $this->input->post('id_pemilik');
                $tahun = $this->input->post('tahun');
                $jenis = $this->input->post('jenis');
                $warna = $this->input->post('warna');
                $jumlah_kursi = $this->input->post('jumlah_kursi');
                $transmisi = $this->input->post('transmisi');
                $tarif = $this->input->post('tarif');
                $bagian_rental = $this->input->post('bagian_rental');
                $diskon = $this->input->post('tanggal_lahir');
                $status = $this->input->post('status');
                $info = $this->input->post('info');
                $daftar = date('Y-m-d');
                $data = array(
                    'id_pemilik' => $id_pemilik,
                    'tahun' => $tahun,
                    'warna' => $warna,
                    'jumlah_kursi' => $jumlah_kursi,
                    'jenis' => $jenis,
                    'transmisi' => $transmisi,
                    'tarif' => $tarif,
                    'diskon' => $diskon,
                    'status' => $status,
                    'info' => $info,
                    'daftar' => $daftar,
                    'bagian_rental' => $bagian_rental,
                );
                $this->M_Mobil->tambah('mobil', $data);
                $this->session->set_flashdata('success', 'Berhasil tambah data');
                redirect('admin/mobil/index', 'refresh');
            }
        }
    }
    public function hapusgaleri($id_galeri)
    {
        $data =
            $this->db->get_where('galeri', array('id_galeri' => $id_galeri))->row_array();
        if ($data) {
            $this->M_Mobil->hapusgaleri($id_galeri);
            $this->session->set_flashdata('success', 'Berhasil Hapus Data');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('Info', 'Gagal Hapus Data');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    function tambahgaleri()
    {
        $this->form_validation->set_rules('id_mobil', 'id_mobil', 'required|trim', [
            'required' => 'Judul Berkas Tidak Boleh Kosong!'
        ]);
        if (empty($_FILES['foto']['name'])) {
            $this->form_validation->set_rules('foto', 'foto', 'required', [
                'required' => 'File Tidak Boleh Kosong!'
            ]);
        }
        if ($this->form_validation->run() == FALSE) {
            $id_mobil = $this->uri->segment(4);
            $data['index'] = $this->M_Mobil->index();
            $data['index2'] = $this->M_Mobil->get($id_mobil);
            $data['berkas'] = $this->db->get_where('berkas', array('id_pemilik' => $id_mobil))->result_array();
            $data['galeri'] = $this->db->get_where('galeri', array('id_mobil' => $id_mobil))->result_array();
            if ($data) {
                $this->session->set_flashdata('info', 'Form upload tidak boleh kosong');
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata('info', 'Tidak Ada Data');
                redirect('admin/album/index', 'refresh');
            }
        } else {
            $config['upload_path'] = './assets/foto/mobil/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
            $config['file_name'] = $this->input->post('id_mobil');
            $this->upload->initialize($config);
            if ($this->upload->do_upload('foto')) {
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
                $tanggal = date('Y-m-d');
                $id_mobil = $this->input->post('id_mobil');
                $data = array(
                    'foto' => $file,
                    'daftar' => $tanggal,
                    'id_mobil' => $id_mobil,
                );
                $this->M_Mobil->tambah('galeri', $data);
                $this->session->set_flashdata('success', 'Berhasil tambah foto');
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata('info', 'Gagal tambah foto');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }
    function ubah()
    {
        $config['upload_path'] = './assets/foto/mobil/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
        $config['file_name'] = $this->input->post('id_mobil');
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
                $id_pemilik = $this->input->post('id_pemilik');
                $id_mobil = $this->input->post('id_mobil');
                $tahun = $this->input->post('tahun');
                $jenis = $this->input->post('jenis');
                $warna = $this->input->post('warna');
                $jumlah_kursi = $this->input->post('jumlah_kursi');
                $transmisi = $this->input->post('transmisi');

                $sewa = $this->input->post('sewa');
                $tarif = $this->input->post('tarif');
                $diskon = $this->input->post('diskon');

                $data = array(
                    'thumbnail' => $file,
                    'id_pemilik' => $id_pemilik,
                    'tahun' => $tahun,
                    'jenis' => $jenis,
                    'warna' => $warna,
                    'jumlah_kursi' => $jumlah_kursi,
                    'transmisi' => $transmisi,
                    'sewa' => $sewa,
                    'tarif' => $tarif,
                    'diskon' => $diskon,
                    'tarif' => $tarif,
                );
                $this->M_Mobil->update('mobil', $data, array('id_mobil' => $id_mobil));
                $this->session->set_flashdata('success', 'Berhasil update data');
                redirect('admin/mobil/index', 'refresh');
            } else {
                $this->session->set_flashdata('info', 'Gagal update data');
                redirect('admin/mobil/index', 'refresh');
            }
        } else {
            $id_pemilik = $this->input->post('id_pemilik');
            $id_mobil = $this->input->post('id_mobil');
            $tahun = $this->input->post('tahun');
            $jenis = $this->input->post('jenis');

            $warna = $this->input->post('warna');
            $jumlah_kursi = $this->input->post('jumlah_kursi');
            $transmisi = $this->input->post('transmisi');
            $sewa = $this->input->post('sewa');

            $tarif = $this->input->post('tarif');

            $diskon = $this->input->post('diskon');

            $data = array(
                'id_pemilik' => $id_pemilik,
                'tahun' => $tahun,
                'jenis' => $jenis,
                'warna' => $warna,
                'jumlah_kursi' => $jumlah_kursi,
                'transmisi' => $transmisi,
                'sewa' => $sewa,
                'tarif' => $tarif,
                'diskon' => $diskon,
                'tarif' => $tarif,

            );
            $this->M_Mobil->update('mobil', $data, array('id_mobil' => $id_mobil));
            $this->session->set_flashdata('success', 'Berhasil update data');
            redirect('admin/mobil/index', 'refresh');
        }
    }

    function pengajuan()
    {
        $data['profil'] = $this->M_Profil->index();
        $data['pengajuan_partner'] = $this->db->get_where('pengguna', array('id_akses' => 6))->num_rows();
        $data['pengajuan_mobil'] = $this->db->get_where('mobil', array('status' => 'pengajuan'))->num_rows();
        $data['pesan'] = $this->db->get_where('pesan', array('status' => 'unread'))->num_rows();
        $data['pesan_index'] = $this->db->get_where('pesan', array('status' => 'unread'))->result_array();
        $data['title'] = "Mobil";
        $data['title2'] = "Pengajuan Partner";
        $data['index'] = $this->M_Mobil->pengajuan();
        $data['pemilik'] = $this->M_Partner->index();
        $this->load->view('admin/template/header', $data);
        $this->load->view('admin/mobil/pengajuan', $data);
        $this->load->view('admin/template/footer', $data);
    }
    function aktif($id_mobil)
    {
        $data = array('status' => 'Belum Publish');
        $this->M_Mobil->update('mobil', $data, array('id_mobil' => $id_mobil));
        $this->_sendmail($id_mobil);
        $this->session->set_flashdata('success', 'Pengajuan mobil disetujui');
        redirect($_SERVER['HTTP_REFERER']);
    }
    function tolak($id_mobil)
    {
        $data = array('status' => 'Ditolak');
        $this->M_Mobil->update('mobil', $data, array('id_mobil' => $id_mobil));
        $this->session->set_flashdata('success', 'Pengguna belum memenuhi kriteria');
        $this->_sendmail1($id_mobil);
        redirect($_SERVER['HTTP_REFERER']);
    }
    private function _sendmail($id_mobil)
    {
        $customer = $this->session->userdata('id_pengguna');
        $user = $this->M_Mobil->get($id_mobil);

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
        $this->email->subject('Pengajuan Partner | Rental Mari Bersaudara');
        $this->email->message('Selamat mobil yang ada ajukan telah diterima oleh pihak Rental Mari Bersaudara, partner dapat datang ke lokasi rental untuk administrasi lanjutan.');

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }
    private function _sendmail1($id_mobil)
    {
        $customer = $this->session->userdata('id_pengguna');
        $user = $this->M_Mobil->get($id_mobil);

        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' => 2525,
            'smtp_user' => '43111cc6037b8d',
            'smtp_pass' => '8b752bd5412080',
            'crlf' => "\r\n",
            'newline' => "\r\n"
        );

        $this->load->library('email', $config);
        $this->email->from('rental_maribersaudara@gmail.com');
        $this->email->to($user['email']);
        $this->email->subject('Pengajuan Partner | Rental Mari Bersaudara');
        $this->email->message('Pengajuan mobil ditolak, belum memenuhi spesifikasi.');

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }
    function download($id_mobil)
    {
        //download foto
        $data = $this->db->get_where('mobil', ['id_mobil' => $id_mobil])->row_array();
        force_download('assets/foto/mobil/' . $data['thumbnail'], NULL);
        //download berkas
        //$data = $this->db->get_where('berkas', ['id_pemilik' => $id_mobil])->row_array();
        //force_download('assets/berkas/pengguna/' . $data['berkas'], NULL);
    }
}