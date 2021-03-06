<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tentang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Profil');
        $this->load->model('M_Mobil');
        $this->load->model('M_Pengguna');
        $this->load->model('M_Layanan');
        $this->load->model('M_Customer');
        $this->load->model('M_Berita');
        $this->load->model('M_Profil');
    }
    function index()
    {
        $data['berita'] = $this->M_Berita->index();
        $data['kontak'] = $this->M_Profil->index();
        $data['title'] = "Tentang Kami";
        $data['index'] = $this->M_Profil->index();
        $data['karyawan'] = $this->M_Pengguna->indexkaryawan();
        $data['layanan'] = $this->M_Layanan->index();
        $this->load->view('pengunjung/template/header', $data);
        $this->load->view('pengunjung/tentang/index', $data);
        $this->load->view('pengunjung/template/footer', $data);
    }
    function registrasi()
    {
        $this->form_validation->set_rules('nama_lengkap', 'nama_lengkap', 'required|trim|is_unique[pengguna.username]', [
            'required' => 'Tidak Boleh Kosong!',
            'is_unique' => 'Username Sudah Ada'
        ]);
        $this->form_validation->set_rules('nik', 'nik', 'required|trim|is_unique[pengguna.nik]', [
            'required' => 'Tidak Boleh Kosong!',
            'is_unique' => 'NIK Telah Terdaftar'
        ]);
        $this->form_validation->set_rules('alamat', 'alamat', 'required|trim', [
            'required' => 'Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('email', 'email', 'required|trim', [
            'required' => 'Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('no_hp', 'no_hp', 'required|trim', [
            'required' => 'Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('tempat_lahir', 'tempat_lahir', 'required|trim', [
            'required' => 'Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('tanggal_lahir', 'tanggal_lahir', 'required|trim', [
            'required' => 'Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('username', 'username', 'required|trim', [
            'required' => 'Tidak Boleh Kosong!'
        ]);
        $this->form_validation->set_rules('password', 'password', 'required|trim', [
            'required' => 'Tidak Boleh Kosong!'
        ]);
        if (empty($_FILES['ktp']['name'])) {
            $this->form_validation->set_rules('ktp', 'ktp', 'required|trim', [
                'required' => 'Tidak Boleh Kosong!'
            ]);
        }
        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $config['upload_path']          = './assets/foto/ktp/';
            $config['allowed_types']        = 'gif|jpg|png|pdf';
            $config['max_size']             = 5000;

            $this->upload->initialize($config);
            if (!$this->upload->do_upload('ktp')) {
                echo $this->upload->display_errors();
            } else {
                $nik = $this->input->post('nik');
                $nama_lengkap = $this->input->post('nama_lengkap');
                $email = $this->input->post('email');
                $alamat = $this->input->post('alamat');
                $no_hp = $this->input->post('no_hp');
                $jabatan = $this->input->post('jabatan');
                $jenis_kelamin = $this->input->post('jenis_kelamin');
                $tanggal = date('Y-m-d');
                $tempat_lahir = $this->input->post('tempat_lahir');
                $x = $this->input->post('tanggal_lahir');
                $tanggal_lahir = date('Y-m-d', strtotime($x));
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $gbr = $this->upload->data();
                $file = $gbr['file_name'];
                $data = array(
                    'username' => $username,
                    'password' => $password,
                    'nik' => $nik,
                    'nama_lengkap' => $nama_lengkap,
                    'alamat' => $alamat,
                    'no_hp' => $no_hp,
                    'email' => $email,
                    'jabatan' => $jabatan,
                    'daftar' => $tanggal,
                    'jenis_kelamin' => $jenis_kelamin,
                    'tempat_lahir' => $tempat_lahir,
                    'tanggal_lahir' => $tanggal_lahir,
                    'id_akses' => 3,
                    'ktp' => $file
                );
                $this->M_Customer->tambah('pengguna', $data);
                $this->session->set_flashdata('success', 'Silahkan Login');
                redirect('login', 'refresh');
            }
        }
    }
    private function _sendmail()
    {
        $nik = $this->input->post('nik');
        $user = $this->db->get_where('pengguna', ['nik' => $nik])->row_array();

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
        $this->email->message('Pengajuan menjadi partner Mari Bersaudara ditolak.');

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }
    public function aktif()
    {
        $email = $this->input->get('email');
        $user = $this->db->get_where('pengguna', ['email' => $email])->row_array();
        $id_pengguna = $user['id_pengguna'];

        $this->M_Pengguna->update('pengguna', array('id_akses' => 3, 'id_pengguna' => $id_pengguna));

        redirect("login/index");
    }
}