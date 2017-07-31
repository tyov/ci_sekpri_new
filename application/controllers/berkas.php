<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Berkas extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Berkasmodel');
	}

	public function index(){
		//$databerkas['json'] = $this->Berkasmodel->getJson();
		//$data_berkas['databerkas']=$this->Berkasmodel->tampil_data_berkas();
		//$data_berkas['datakaryawan']
		$this->load->view('berkas');
	}

	public function get_berkas()
	{
		$data['rows']=$this->Berkasmodel->getJson('rows');
		$data['total']=$this->Berkasmodel->getJson('total');
		echo json_encode($data);
	}

	public function tambah_berkas(){
		//echo "test ";
		$result=$this->Berkasmodel->tambah_berkas();
		echo json_encode($result);
	}

	public function hapus_berkas($nomor)
	{
		$result=$this->Berkasmodel->hapus_berkas($nomor);
		echo json_encode($result);
	}

	public function update_berkas($nomor)
	{
		$result=$this->Berkasmodel->update_berkas($nomor);
		echo json_encode($result);
    }

    public function get_berkas_desc()
	{
		$data=$this->Berkasmodel->getJson('rows');
		echo json_encode($data);
	}
}