<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bagianmodel extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}


	public function getJson()
	{
		/*$this->db->select("distinct nama_bagian");
		$this->db->from("bagian");
		$this->db->where("nama_bagian is not null");
		$this->db->order_by("nama_bagian");*/
		$hasil = $this->db->query("select distinct a.kode_jabatan, a.keterangan, a.nama_bagian
 from bagian a where nama_bagian is not null")->result_array();
		//$hasil = $this->db->get()->result_array();
        return $hasil;
	}

	public function getJsonDirektur()
	{
		$hasil = $this->db->query("SELECT kode_direktur, keterangan FROM bagian where kode_manajer = '00' and kode_asisten_manajer = '00' and kode_supervisor = '00' and kode_staff = '00' and kode_direktur in (1,2,3)")->result_array();

		return $hasil;
	}

}

/* End of file bagianmodel.php */
/* Location: ./application/models/bagianmodel.php */
?>