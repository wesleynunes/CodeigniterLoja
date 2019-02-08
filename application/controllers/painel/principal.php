<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Principal extends CI_Controller {
	
	public function index() {
		$this->layout = 'dashboard';
		$this->load->view('welcome_message');
	}
	
}