<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Master_Ruangan extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_ruangan_model');
	}

	public function index(){
		//$databerkas['json'] = $this->Berkasmodel->getJson();
		//$data_berkas['databerkas']=$this->Berkasmodel->tampil_data_berkas();
		//$data_berkas['datakaryawan']
		$this->load->view('master_ruangan');
	}

	public function get_master_ruangan()
	{
		$data['rows']=$this->master_ruangan_model->getJson('rows');
		$data['total']=$this->master_ruangan_model->getJson('total');
		echo json_encode($data);
	}

	public function tambah_master_ruangan(){
		//echo "test ";
		$result=$this->master_ruangan_model->tambah_master_ruangan();
		echo json_encode($result);
	}

	public function hapus_master_ruangan($nomor)
	{
		$result=$this->master_ruangan_model->hapus_master_ruangan($nomor);
		echo json_encode($result);
	}

	public function update_master_ruangan($nomor)
	{
		$result=$this->master_ruangan_model->update_master_ruangan($nomor);
		echo json_encode($result);
    }
}


