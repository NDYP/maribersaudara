<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Berita extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Berita');
        $this->load->model('M_Profil');
    }
    function index()
    {
        $config['base_url'] = site_url('berita/index'); //site url
        $config['total_rows'] = $this->db->count_all('berita'); //total row
        $config['per_page'] = 5;  //show record per halaman
        $config["uri_segment"] = 3;  // uri parameter
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);

        $config['first_link']       = 'First';
        $config['last_link']        = 'Last';
        $config['next_link']        = 'Next';
        $config['prev_link']        = 'Prev';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';

        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        //panggil function get_mahasiswa_list yang ada pada mmodel mahasiswa_model. 
        $data['pagination'] = $this->pagination->create_links();

        $data['title'] = 'Berita';
        $data['berita'] = $this->M_Berita->pengunjung($config["per_page"], $data['page']);
        $data['kontak'] = $this->M_Profil->index();
        $this->load->view('pengunjung/template/header', $data);
        $this->load->view('pengunjung/berita/index', $data);
        $this->load->view('pengunjung/template/footer', $data);
    }
    function get($id_berita)
    {
        $data['id_berita'] = $this->M_Berita->get($id_berita);
        $url = $data['id_berita']['judul'];
        $url_slug = url_title($url, 'dash', TRUE);
        redirect(base_url('berita/baca/' . $id_berita . '/' . $url_slug));
    }
    function baca($id_berita)
    {
        $data['title'] = 'Berita';
        $data['title1'] = 'Baca Berita';
        $data['berita'] = $this->M_Berita->get($id_berita);
        $data['kontak'] = $this->M_Profil->index();
        $this->load->view('pengunjung/template/header', $data);
        $this->load->view('pengunjung/berita/detail', $data);
        $this->load->view('pengunjung/template/footer', $data);
    }
}