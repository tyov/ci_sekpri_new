<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ekspedisi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ekspedisimodel');
	}

	public function index()
	{
		$this->load->view('ekspedisi');
	}

}

/* End of file ekspedisi.php */
/* Location: ./application/controllers/ekspedisi.php */