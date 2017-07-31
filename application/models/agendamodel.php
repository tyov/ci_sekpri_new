<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agendamodel extends CI_Model {

	public function getJson($jenis)
	{
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id_agenda';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page-1) * $rows;
        $this->limit = $rows;
        $this->offset = $offset;
        //searching
        $searchKey=isset($_POST['searchKey']) ? strval($_POST['searchKey']) : '';
		$searchValue=isset($_POST['searchValue']) ? strval($_POST['searchValue']) : '';

        if ($jenis=='total') {
        	$result = $this->db->query("select * from agenda")->num_rows();
        	return $result;
        } elseif ($jenis=='rows') {
        	$this->db->limit($rows,$offset);
        	$this->db->order_by($sort,$order);
			$this->db->select("a.id_agenda,a.id_ruangan,a.id_pemesan,
				convert(varchar(20),tgl_pemesanan,120) as tgl_pemesanan,
				a.keterangan,
				convert(varchar(20),a.tgl_mulai,120) as tgl_mulai,
				convert(varchar(20),a.tgl_selesai,120) as tgl_selesai, 
				b.keterangan id_ruangan_desc, c.nama_lengkap id_pemesan_desc");
			$this->db->from("agenda a");
			$this->db->join("master_ruangan b", "a.id_ruangan=b.id_ruangan");
			$this->db->join("karyawan c", "a.id_pemesan=c.nip");
        	if($searchKey<>''){
				$this->db->where($searchKey." like '%".$searchValue."%'");	
			}
        	$hasil=$this->db->get ('',$this->limit, $this->offset)->result_array();
        	return $hasil;
    	}
	}

	public function tambah_agenda(){

		$id_pemesan = htmlspecialchars($_REQUEST['id_pemesan']);
		$id_ruangan = htmlspecialchars($_REQUEST['id_ruangan']);
		$keterangan = htmlspecialchars($_REQUEST['keterangan']);
		$tgl_mulai = htmlspecialchars($_REQUEST['tgl_mulai']);
		$tgl_selesai = htmlspecialchars($_REQUEST['tgl_selesai']);

		$id_agenda = $this->db->query("select dbo.getNomorAgenda() as baru")->row_array();
		$tgl_pemesanan = $this->db->query("select getDate() as baru")->row_array();

		$data = array(
		        'id_agenda' => $id_agenda['baru'],
		        'tgl_pemesanan' => $tgl_pemesanan['baru'],
		        'id_ruangan' => $id_ruangan,
		        'id_pemesan' => $id_pemesan,
		        'tgl_mulai' => $tgl_mulai,
		        'tgl_selesai' => $tgl_selesai,
		        'keterangan' => $keterangan,
		);

		if ($this->db->insert('agenda', $data)) {
			return "success";
		} else {
			return "insert failed";
		}
	}

	public function hapus_agenda($id_agenda)
	{
		$this->db->where('id_agenda', $id_agenda);
		if ($this->db->delete('agenda')) {
			return "success";
		} else {
			return "delete failed";
		}
	}

	public function update_agenda($id_agenda)
	{
		$id_ruangan = htmlspecialchars($_REQUEST['id_ruangan']);
		$id_pemesan = htmlspecialchars($_REQUEST['id_pemesan']);
		$tgl_mulai = htmlspecialchars($_REQUEST['tgl_mulai']);
		$tgl_selesai = htmlspecialchars($_REQUEST['tgl_selesai']);
		$keterangan = htmlspecialchars($_REQUEST['keterangan']);

		$data = array(
		        'id_ruangan' => $id_ruangan,
		        'id_pemesan' => $id_pemesan,
		        'tgl_mulai' => $tgl_mulai,
		        'tgl_selesai' => $tgl_selesai,
		        'keterangan' => $keterangan,
		);

		$this->db->where('id_agenda', $id_agenda);

		if ($this->db->update('agenda', $data)) {
			return "success";
		} else {
			return "update failed";
		}
	}
}