<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawanmodel extends CI_Model {

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
		$hasil = $this->db->query("select distinct nip, nama_lengkap from karyawan where nama_lengkap is not null order by nama_lengkap")->result_array();
		//$hasil = $this->db->get()->result_array();
        return $hasil;
	}
}

/* End of file karyawanmodel.php */
/* Location: ./application/models/karyawanmodel.php */