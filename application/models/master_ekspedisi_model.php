<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_ekspedisi_model extends CI_Model {

	public function getJson($jenis)
	{
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id_jenis_ekspedisi';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page-1) * $rows;
        $this->limit = $rows;
        $this->offset = $offset;
        //searching
        $searchKey=isset($_POST['searchKey']) ? strval($_POST['searchKey']) : '';
		$searchValue=isset($_POST['searchValue']) ? strval($_POST['searchValue']) : '';

        if ($jenis=='total') {
        	$result = $this->db->query("select * from master_ekspedisi")->num_rows();
        	return $result;
        } elseif ($jenis=='rows') {
        	$this->db->limit($rows,$offset);
        	$this->db->order_by($sort,$order);
			$this->db->select("a.*");
			$this->db->from("master_ekspedisi a");
			// $this->db->join("(SELECT left(kode_jabatan,4) as kode, nama_bagian FROM bagian group by left(kode_jabatan,4), nama_bagian) b", "a.kode_bagian = b.kode");
			// $this->db->join("karyawan c", "a.penerima_berkas=c.nip");
			// $this->db->join("karyawan d", "a.pemilik_berkas=d.nip");
        	if($searchKey<>''){
				$this->db->where($searchKey." like '%".$searchValue."%'");	
			}
        	$hasil=$this->db->get ('',$this->limit, $this->offset)->result_array();
        	return $hasil;
    	}
	}

	public function tambah_master_ekspedisi(){

		$keterangan = htmlspecialchars($_REQUEST['keterangan']);

		$id_jenis_ekspedisi = $this->db->query("select dbo.getNomorMasterEkspedisi() as baru")->row_array();

		$data = array(
		        'id_jenis_ekspedisi' => $id_jenis_ekspedisi['baru'],
		        'keterangan' => $keterangan
		);

		if ($this->db->insert('master_ekspedisi', $data)) {
			return "success";
		} else {
			return "insert failed";
		}
	}

	public function update_master_ekspedisi($id_jenis_ekspedisi)
	{
		$keterangan = htmlspecialchars($_REQUEST['keterangan']);

		$data = array(
		        'keterangan' => $keterangan
		);

		$this->db->where('id_jenis_ekspedisi', $id_jenis_ekspedisi);

		if ($this->db->update('master_ekspedisi', $data)) {
			return "success";
		} else {
			return "update failed";
		}
	}

}

/* End of file master_master_ekspedisi_model.php */
/* Location: ./application/models/master_master_ekspedisi_model.php */