<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bagian extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('bagianmodel');
	}

	public function index()
	{
		
	}

	public function get_bagian()
	{
		$data=$this->bagianmodel->getJson();
		echo json_encode($data);
	}

	public function get_direktur()
	{
		$data=$this->bagianmodel->getJsonDirektur();
		echo json_encode($data);
	}

}

/* End of file bagian.php */
/* Location: ./application/controllers/bagian.php */
?>