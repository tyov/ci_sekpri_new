<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berkasmodel extends CI_Model {

	public function getJson($jenis)
	{
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id_berkas';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page-1) * $rows;
        $this->limit = $rows;
        $this->offset = $offset;
        //searching
        $searchKey=isset($_POST['searchKey']) ? strval($_POST['searchKey']) : '';
		$searchValue=isset($_POST['searchValue']) ? strval($_POST['searchValue']) : '';

        if ($jenis=='total') {
        	$result = $this->db->query("select * from berkas")->num_rows();
        	return $result;
        } elseif ($jenis=='rows') {
        	$this->db->limit($rows,$offset);
        	$this->db->order_by($sort,$order);
			$this->db->select("a.id_berkas, convert(varchar(20),a.tgl_terima,120) as tgl_terima_desc, a.penerima_berkas, a.pemilik_berkas, a.kode_bagian, a.isi_berkas, b.nama_bagian bagian_desc, c.nama_lengkap penerima_berkas_desc, d.nama_lengkap pemilik_berkas_desc");
			$this->db->from("berkas a");
			$this->db->join("(SELECT left(kode_jabatan,4) as kode, nama_bagian FROM bagian group by left(kode_jabatan,4), nama_bagian) b", "a.kode_bagian = b.kode");
			$this->db->join("karyawan c", "a.penerima_berkas=c.nip");
			$this->db->join("karyawan d", "a.pemilik_berkas=d.nip");
        	if($searchKey<>''){
				$this->db->where($searchKey." like '%".$searchValue."%'");	
			}
        	$hasil=$this->db->get ('',$this->limit, $this->offset)->result_array();
        	return $hasil;
    	}
	}

	public function tambah_berkas(){

		$penerima_berkas = htmlspecialchars($_REQUEST['penerima_berkas']);
		$pemilik_berkas = htmlspecialchars($_REQUEST['pemilik_berkas']);
		$kode_bagian = htmlspecialchars($_REQUEST['kode_bagian']);
		$isi_berkas = htmlspecialchars($_REQUEST['isi_berkas']);

		$id_berkas = $this->db->query("select dbo.getNomorDokumen() as baru")->row_array();
		$tgl_terima = $this->db->query("select getDate() as baru")->row_array();

		$data = array(
		        'id_berkas' => $id_berkas['baru'],
		        'tgl_terima' => $tgl_terima['baru'],
		        'penerima_berkas' => $penerima_berkas,
		        'pemilik_berkas' => $pemilik_berkas,
		        'kode_bagian' => $kode_bagian,
		        'isi_berkas' => $isi_berkas,
		);

		if ($this->db->insert('berkas', $data)) {
			return "success";
		} else {
			return "insert failed";
		}
	}

	public function hapus_berkas($id_berkas)
	{
		$this->db->where('id_berkas', $id_berkas);
		if ($this->db->delete('berkas')) {
			return "success";
		} else {
			return "delete failed";
		}
	}

	public function update_berkas($id_berkas)
	{
		$penerima_berkas = htmlspecialchars($_REQUEST['penerima_berkas']);
		$pemilik_berkas = htmlspecialchars($_REQUEST['pemilik_berkas']);
		$kode_bagian = htmlspecialchars($_REQUEST['kode_bagian']);
		$isi_berkas = htmlspecialchars($_REQUEST['isi_berkas']);

		$data = array(
		        'penerima_berkas' => $penerima_berkas,
		        'pemilik_berkas' => $pemilik_berkas,
		        'kode_bagian' => $kode_bagian,
		        'isi_berkas' => $isi_berkas,
		);

		$this->db->where('id_berkas', $id_berkas);

		if ($this->db->update('berkas', $data)) {
			return "success";
		} else {
			return "update failed";
		}
	}
}