<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$this->_rules();
		if($this->form_validation->run()==FALSE){
			$data['title']="Login - Gaji.com";
			$this->load->view('templates_admin/adminheader',$data);
			$this->load->view('formLogin');
		}else{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$cek = $this->penggajianModel->cek_login($username, $password);
			if($cek == FALSE){
				$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Incorrect username or password.</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span></button></div>');
				redirect('welcome');
			}else{
				$this->session->set_userdata('hak_akses',$cek->hak_akses);
				$this->session->set_userdata('nama_pegawai',$cek->nama_pegawai);
				$this->session->set_userdata('photo',$cek->photo);
				$this->session->set_userdata('id_pegawai',$cek->id_pegawai);
				$this->session->set_userdata('nik',$cek->nik);
				switch ($cek->hak_akses) {
					case 1 : 	redirect('admin/dashboard');
								break;
					case 2 : 	redirect('pegawai/dashboard');
								break;
					default:	break;
				}
			}
		}
	}
	public function _rules(){
		$this->form_validation->set_rules('username','username','required');
		$this->form_validation->set_rules('password','password','required');
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect('welcome');
	}
}