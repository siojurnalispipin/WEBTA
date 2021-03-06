<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // untuk load database
        // $this->load->database();

        // load model
        $this->load->model('model_auth');
        $this->load->model('model_landing');
        $this->load->model('model_buku');

    }

    public function index()
    { 
        redirect('controller_auth/login');
    }

    public function login()
    {

        if ($this->model_auth->logged_id()) {
            redirect('Controller_landing/index');
        } else {

            $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna', 'trim|required|min_length[3]|max_length[20]');
            $this->form_validation->set_rules('kata_sandi', 'Kata Sandi', 'trim|required|min_length[4]|max_length[20]');

            if ($this->form_validation->run() == true) {
                $nama_pengguna = htmlspecialchars(
                    $this->input->post('nama_pengguna', true),
                    ENT_QUOTES
                );
                $kata_sandi = htmlspecialchars(
                    $this->input->post('kata_sandi', true),
                    ENT_QUOTES
                );

                $cek_admin = $this->model_auth->adminMdl($nama_pengguna, $kata_sandi);

                if ($cek_admin->num_rows() > 0) {
                    $data = $cek_admin->row_array();
                    if ($data['a_level'] == '1') {

                        $admin_data = array(
                            'akses' => '1',
                            'ses_id' => $data['a_id'],
                            'ses_nama' => $data['a_namapengguna'],
                            'ses_foto' => $data['a_fotoprofil'],
                            'masuk' => true,
                        );
                        $this->session->set_userdata($admin_data);
                        redirect('controller_auth');
                    } else {
                        $demo_data = array(
                            'akses' => '0',
                            'ses_id' => $data['a_id'],
                            'ses_nama' => $data['a_namapengguna'],
                            'masuk' => true,
                        );
                        $this->session->set_userdata($demo_data);
                        redirect('controller_auth');
                    }
                } else {
                    $cek_pengguna = $this->model_auth->penggunaMdl($nama_pengguna, $kata_sandi);
                    if ($cek_pengguna->num_rows() > 0) {
                        $data = $cek_pengguna->row_array();
                        $pengguna_data = array(
                            'akses' => '3',
                            'ses_id' => $data['p_id'],
                            'ses_nama' => $data['p_namapengguna'],
                            'ses_foto' => $data['p_fotoprofil'],
                            'masuk' => true,
                        );
                        $this->session->set_userdata($pengguna_data);
                        redirect('controller_auth');
                    } else { // jika username dan password tidak ditemukan atau salah

                        echo $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center" role="alert">Username Atau Password Salah!!</div>');
                        redirect('controller_auth/login/', 'refresh');

                    }
                }
            } else {
                // untuk direct ke halaman login langsung
                $data['view'] = 'admin/auth/view_login';
                $this->load->view('layouts/layout_auth/template_login', $data);
            }
        }
    }

    public function registration()
    {

        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim|required|min_length[3]|max_length[20]');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna', 'trim|required|min_length[3]|max_length[20]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tbl_pengguna.p_email]');
        $this->form_validation->set_rules('kata_sandi', 'Kata sandi', 'required|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('konfirmasi_kata_sandi', 'Konfirmasi kata sandi', 'required|matches[kata_sandi]');

        if ($this->form_validation->run() == false) {
            $data['view'] = 'admin/auth/view_register';
            $this->load->view('layouts/layout_auth/template_register', $data);
        } else {
            $nama = $this->input->post('nama');
            $nama_pengguna = $this->input->post('nama_pengguna');
            $email = $this->input->post('email');
            $kata_sandi = $this->input->post('kata_sandi');
            $passhash = hash('md5', $kata_sandi);

            $saltid = md5($email);
            $status = 0;

            $data = array(
                'p_nama' => $nama,
                'p_namapengguna' => $nama_pengguna,
                'p_email' => $email,
                'p_katasandi' => $passhash,
                'p_status' => $status,
            );

            if ($this->model_auth->insertuser($data)) {
                if ($this->sendemail($email, $saltid)) {
                    // successfully sent mail to user email
                    echo $this->session->set_flashdata('msg', '<div class="alert alert-success text-center" role="alert">Silahkan konfirmasi pendaftaran anda di email untuk melengkapi pendaftaran!!</div>');

                    redirect('controller_auth/login/', 'refresh');
                } else {
                    echo $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center" role="alert">Silahkan Coba Lagi!!</div>');

                    redirect('controller_auth/login/', 'refresh');
                }
                redirect('controller_auth/login/', 'refresh');

            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Something Wrong. Please try again ...</div>');
                redirect('controller_auth/login/', 'refresh');
            }
        }
    }
    public function sendemail($email, $saltid)
    {
        // configure the email setting
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.mailtrap.io'; //smtp host name
        $config['smtp_port'] = 2525; //smtp port number
        $config['smtp_user'] = '2e2a4c9602d592';
        $config['smtp_pass'] = 'd8938f3f8d9a38'; //$from_email password
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = true;
        $this->email->initialize($config);
        $url = base_url() . "controller_auth/confirmation/" . $saltid;
        $this->email->from('TAperpus@gmail.com', 'CodesQuery');
        $this->email->to($email);
        $this->email->subject('Please Verify Your Email Address');
        $message = "<html><head><head></head><body><p>Hallo,</p><p>Terimakasih telah mendaftar pada sistem EPERPUS.</p><p>Silahkan menuju link dibawah ini untuk melengkapi pendaftaran anda.</p>" . $url . "<br/><p>Sincerely,</p><p>EPERPUS | SIO | ALBERT | HERU</p></body></html>";
        $this->email->message($message);
        return $this->email->send();
    }

    public function confirmation($key)
    {
        if ($this->model_auth->verifyemail($key)) {
            echo $this->session->set_flashdata('msg', '<div class="alert alert-success text-center" role="alert">Alamat email mu telah berhasil di konfirmasi, terima kasih!!</div>');

            redirect('controller_auth/login/', 'refresh');
        } else {
            echo $this->session->set_flashdata('msg', '<div class="alert alert-success" role="alert">Verifikasi email salah, silahkan ulangi kembali!!</div>');
            redirect('controller_auth/login/', 'refresh');
        }
    }

    // fungsi logout
    public function logout()
    {
        $this->session->sess_destroy();
        $url = base_url('');
        redirect($url);
    }

}
