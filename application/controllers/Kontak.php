<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kontak extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Akun');
        $this->load->model('M_Mobil');
        $this->load->model('M_Layanan');
        $this->load->model('M_Pengguna');
        $this->load->model('M_Berita');
        $this->load->model('M_Profil');
        $this->load->model('M_Customer');
    }
    function index()
    {
        $browser = $this->agent->browser();
        $os = $this->agent->platform();
        $ip = $this->input->ip_address();
        date_default_timezone_set("ASIA/JAKARTA");
        $tanggal = date('Y-m-d');
        $data = [
            'tanggal' => $tanggal,
            'ip' => $ip,
            'os' => $os,
            'browser' => $browser,
            'online' => time()
        ];
        $query = $this->M_Akun->get($ip, $tanggal);
        if ($query == 0) {
            $this->M_Akun->tambah('pengunjung', $data);
        } else {
            $this->M_Akun->update('pengunjung', $data, array('ip' => $ip, 'tanggal' => $tanggal));
        }
        $data['title'] = 'Beranda';
        $data['jumlah_partner'] = $this->db->get_where('pengguna', array('id_akses' => 4))->num_rows();
        $data['jumlah_customer'] = $this->db->get_where('pengguna', array('id_akses' => 3))->num_rows();
        $data['jumlah_mobil'] = $this->db->get_where('mobil', array('status' => 'tersedia'))->num_rows();
        $data['jumlah_transaksi'] = $this->db->get_where('transaksi', array('status' => 'selesai'))->num_rows();

        $data['layanan'] = $this->M_Layanan->index();
        $data['mobil'] = $this->M_Mobil->index();
        $data['berita'] = $this->M_Berita->index();
        $data['karyawan'] = $this->M_Pengguna->indexkaryawan();
        $data['kontak'] = $this->M_Profil->index();
        $this->load->view('pengunjung/template/header', $data);
        $this->load->view('pengunjung/kontak/index', $data);
        $this->load->view('pengunjung/template/footer', $data);
    }
    function pesan()
    {
        $nama = $this->input->post('nama');
        $email = $this->input->post('email');
        $pesan = $this->input->post('pesan');
        $tanggal = date('Y-m-d');
        $data = array(
            'nama' => $nama,
            'email' => $email,
            'pesan' => $pesan,
            'tanggal' => $tanggal,
            'status' => 'unread',
        );
        $this->M_Customer->tambah('pesan', $data);
        $this->session->set_flashdata('success', 'Pesan telah terkirim');
        redirect('kontak/index', 'refresh');
    }
}