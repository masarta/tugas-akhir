<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('spi_model');
    }

    function index()
    {
        $this->load->view('auth/login');
    }

    function login()
    {
        $nippos   = $this->input->post('nippos', TRUE);
        $password = $this->input->post('password', TRUE);
        $validate = $this->spi_model->validate($nippos, $password);
        if ($validate->num_rows() > 0) {
            $data  = $validate->row_array();
            $name  = $data['nama'];
            $nippos = $data['nippos'];
            $tempat_kerja = $data['tempat_kerja'];
            $no_hp = $data['no_hp'];
            $email = $data['email'];
            $level = $data['level'];
            $sesdata = array(
                'nama'      => $name,
                'nippos'    => $nippos,
                'level'     => $level,
                'tempat_kerja' => $tempat_kerja,
                'no_hp'     => $no_hp,
                'email'     => $email,
                'logged_in' => TRUE
            );
            $this->session->set_userdata($sesdata);
            // akses login untuk admin
            if ($sesdata['level'] === '1') {
                redirect('page');

                // akses login untuk manajer audit upt
            } elseif ($sesdata['level'] === '2') {
                redirect('page/man_audit_upt');

                // access login for author
            } elseif ($sesdata['level' === '3']) {
                # code...
            } {
                redirect('page/pengawas');
            }
        } else {
            echo $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Nippos atau Password Salah !!</div>');
            redirect('auth');
        }
    }

    function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
