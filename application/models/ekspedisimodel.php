<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ekspedisimodel extends CI_Model {

	public function getJson($jenis)
	{
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id_ekspedisi';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page-1) * $rows;
        $this->limit = $rows;
        $this->offset = $offset;
        //searching
        $searchKey=isset($_POST['searchKey']) ? strval($_POST['searchKey']) : '';
		$searchValue=isset($_POST['searchValue']) ? strval($_POST['searchValue']) : '';

        if ($jenis=='total') {
        	$result = $this->db->query("select * from berkas_ekspedisi")->num_rows();
        	return $result;
        } elseif ($jenis=='rows') {
        	$this->db->limit($rows,$offset);
        	$this->db->order_by($sort,$order);
			$this->db->select("a.*, b.keterangan id_jenis_ekspedisi_desc, c.nama_lengkap as petugas_ekspedisi_desc, d.nama_lengkap as tujuan_desc");
			$this->db->from("berkas_ekspedisi a");
			// $this->db->join("(SELECT left(kode_jabatan,4) as kode, nama_bagian FROM bagian group by left(kode_jabatan,4), nama_bagian) b", "a.kode_bagian = b.kode");
			$this->db->join("master_ekspedisi b", "a.id_jenis_ekspedisi=b.id_jenis_ekspedisi");
			$this->db->join("karyawan c", "a.petugas_ekspedisi=c.nip");
			$this->db->join("karyawan d", "a.tujuan=d.nip");
        	if($searchKey<>''){
				$this->db->where($searchKey." like '%".$searchValue."%'");	
			}
        	$hasil=$this->db->get ('',$this->limit, $this->offset)->result_array();
        	return $hasil;
    	}
	}
	
	public function tambah_ekspedisi(){

		$id_jenis_ekspedisi = htmlspecialchars($_REQUEST['id_jenis_ekspedisi']);
		$id_berkas = htmlspecialchars($_REQUEST['id_berkas']);
		$tujuan = htmlspecialchars($_REQUEST['tujuan']);
		$keterangan = htmlspecialchars($_REQUEST['keterangan']);
		$petugas_ekspedisi = htmlspecialchars($_REQUEST['petugas_ekspedisi']);

		$id_ekspedisi = $this->db->query("select dbo.getNomorEkspedisi() as baru")->row_array();
		$tgl_ekspedisi = $this->db->query("select getDate() as baru")->row_array();

		$data = array(
		        'id_ekspedisi' => $id_ekspedisi['baru'],
		        'tgl_ekspedisi' => $tgl_ekspedisi['baru'],
		        'id_jenis_ekspedisi' => $id_jenis_ekspedisi,
		        'id_berkas' => $id_berkas,
		        'tujuan' => $tujuan,
		        'keterangan' => $keterangan,
		        'petugas_ekspedisi' => $petugas_ekspedisi
		);

		if ($this->db->insert('berkas_ekspedisi', $data)) {
			return "success";
		} else {
			return "insert failed";
		}
	}

	public function update_ekspedisi($id_ekspedisi)
	{
		$id_jenis_ekspedisi = htmlspecialchars($_REQUEST['id_jenis_ekspedisi']);
		$id_berkas = htmlspecialchars($_REQUEST['id_berkas']);
		$tujuan = htmlspecialchars($_REQUEST['tujuan']);
		$keterangan = htmlspecialchars($_REQUEST['keterangan']);
		$petugas_ekspedisi = htmlspecialchars($_REQUEST['petugas_ekspedisi']);

		$data = array(
		        'id_jenis_ekspedisi' => $id_jenis_ekspedisi,
		        'id_berkas' => $id_berkas,
		        'tujuan' => $tujuan,
		        'keterangan' => $keterangan,
		        'petugas_ekspedisi' => $petugas_ekspedisi
		);

		$this->db->where('id_ekspedisi', $id_ekspedisi);

		if ($this->db->update('berkas_ekspedisi', $data)) {
			return "success";
		} else {
			return "update failed";
		}
	}
}

/* End of file ekspedisimodel.php */
/* Location: ./application/models/ekspedisimodel.php */