<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Agenda extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Agendamodel');
	}

	public function index(){
		//$databerkas['json'] = $this->Berkasmodel->getJson();
		//$data_berkas['databerkas']=$this->Berkasmodel->tampil_data_berkas();
		//$data_berkas['datakaryawan']
		$this->load->view('agenda');
	}

	public function get_agenda()
	{
		$data['rows']=$this->Agendamodel->getJson('rows');
		$data['total']=$this->Agendamodel->getJson('total');
		echo json_encode($data);
	}

	public function tambah_agenda(){
		//echo "test ";
		$result=$this->Agendamodel->tambah_agenda();
		echo json_encode($result);
	}

	public function hapus_agenda($nomor)
	{
		$result=$this->Agendamodel->hapus_agenda($nomor);
		echo json_encode($result);
	}

	public function update_agenda($nomor)
	{
		$result=$this->Agendamodel->update_agenda($nomor);
		echo json_encode($result);
    }
}