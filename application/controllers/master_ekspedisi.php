<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_ekspedisi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_ekspedisi_model');
	}

	public function index()
	{
		$this->load->view('master_ekspedisi');
	}

	public function get_master_ekspedisi()
	{
		$data['rows']=$this->master_ekspedisi_model->getJson('rows');
		$data['total']=$this->master_ekspedisi_model->getJson('total');
		echo json_encode($data);
	}

	public function tambah_master_ekspedisi(){
		//echo "test ";
		$result=$this->master_ekspedisi_model->tambah_master_ekspedisi();
		echo json_encode($result);
	}

	public function hapus_master_ekspedisi($id_jenis_ekspedisi)
	{
		$this->db->where('id_jenis_ekspedisi', $id_jenis_ekspedisi);
		if ($this->db->delete('berkas_master_ekspedisi')) {
			$result['error']=false;
		} else {
			$result['error']=true;
		}
		echo json_encode($result);
	}

	public function update_master_ekspedisi($id_jenis_ekspedisi)
	{
		$result=$this->master_ekspedisi_model->update_master_ekspedisi($id_jenis_ekspedisi);
		echo json_encode($result);
    }

}

/* End of file master_master_ekspedisi.php */
/* Location: ./application/controllers/master_master_ekspedisi.php */