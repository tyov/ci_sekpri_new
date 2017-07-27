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

	public function get_ekspedisi()
	{
		$data['rows']=$this->ekspedisimodel->getJson('rows');
		$data['total']=$this->ekspedisimodel->getJson('total');
		echo json_encode($data);
	}

}

/* End of file ekspedisi.php */
/* Location: ./application/controllers/ekspedisi.php */