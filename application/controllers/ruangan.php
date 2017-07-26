<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bagian extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ruanganmodel');
	}

	public function index()
	{
		
	}

	public function get_ruangan()
	{
		$data=$this->ruanganmodel->getJson();
		echo json_encode($data);
	}

	public function get_direktur()
	{
		$data=$this->ruanganmodel->getJsonDirektur();
		echo json_encode($data);
	}

}

/* End of file bagian.php */
/* Location: ./application/controllers/bagian.php */
?>