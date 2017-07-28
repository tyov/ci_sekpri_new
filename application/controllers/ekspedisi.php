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

	public function tambah_ekspedisi(){
		//echo "test ";
		$result=$this->ekspedisimodel->tambah_ekspedisi();
		echo json_encode($result);
	}

	public function hapus_ekspedisi($id_ekspedisi)
	{
		$this->db->where('id_ekspedisi', $id_ekspedisi);
		if ($this->db->delete('berkas_ekspedisi')) {
			$result['error']=false;
		} else {
			$result['error']=true;
		}
		echo json_encode($result);
	}

	public function update_ekspedisi($id_ekspedisi)
	{
		$result=$this->ekspedisimodel->update_ekspedisi($id_ekspedisi);
		echo json_encode($result);
    }

}

/* End of file ekspedisi.php */
/* Location: ./application/controllers/ekspedisi.php */