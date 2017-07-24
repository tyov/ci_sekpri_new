<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class karyawan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('karyawanmodel');
	}

	public function index()
	{
		
	}

	public function get_karyawan()
	{
		$data=$this->karyawanmodel->getJson();
		echo json_encode($data);
	}

}

/* End of file bagian.php */
/* Location: ./application/controllers/bagian.php */
?>