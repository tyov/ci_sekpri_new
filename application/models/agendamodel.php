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
/*        	$this->db->select("a.Nomor as NOMOR, c.keterangan as KE_desc, d.keterangan as POSISI_desc, (CONVERT(varchar(10),TGL_KIRIM,101)+''+RIGHT(CONVERT(varchar(19),TGL_KIRIM,120),9)) as TGL_KIRIM, b.nama_bagian as bagian_desc, a.keterangan, e.nama_lengkap as PENGIRIM_desc, f.nama_lengkap as pengambil_desc,case when a.status='1' then 'Sudah Terkirim' else 'Belum Terkirim' end as
							status_desc, a.DIR_AWAL_ID as KE, a.DIR_AKHIR_ID as POSISI, b.kode as bagian, e.nip as PENGIRIM, f.nip as pengambil, a.status as status");
        	$this->db->from("tbl_berkas a
left join (SELECT left(kode_jabatan,4) as kode, nama_bagian FROM bagian group by left(kode_jabatan,4), nama_bagian) b on a.bagian_id = b.kode
left join (SELECT * FROM bagian where kode_manajer = '00' and kode_asisten_manajer = '00' and kode_supervisor = '00' and kode_staff = '00' and kode_direktur in (1,2,3)) c on a.DIR_AWAL_ID = c.kode_direktur
left join (SELECT * FROM bagian where kode_manajer = '00' and kode_asisten_manajer = '00' and kode_supervisor = '00' and kode_staff = '00' and kode_direktur in (1,2,3)) d on a.DIR_AKHIR_ID = d.kode_direktur
left join karyawan e on a.USER_KIRIM = e.nip
left join karyawan f on a.USER_AMBIL = F.nip");*/
			$this->db->select("a.*, b.id_ruangan, c.nip id_pemesan_desc");
			$this->db->from("agenda a");
			$this->db->join("ruangan b", "a.id_ruangan=a.id_ruangan");
			$this->db->join("karyawan c", "c.nip=a.id_pemesan");
			/*$this->db->join("karyawan d", "a.pemilik_berkas=d.nip"); */
        	if($searchKey<>''){
				$this->db->where($searchKey." like '%".$searchValue."%'");	
			}
        	$hasil=$this->db->get ('',$this->limit, $this->offset)->result_array();
        	return $hasil;
    	}
	}

	public function tambah_agenda(){

		$id_ruangan = htmlspecialchars($_REQUEST['id_ruangan']);
		$id_pemesan = htmlspecialchars($_REQUEST['id_pemesan']);
		$keterangan = htmlspecialchars($_REQUEST['keterangan']);
		/*$isi_berkas = htmlspecialchars($_REQUEST['isi_berkas']);*/

		$id_agenda = $this->db->query("select dbo.getNomorDokumen() as baru")->row_array();
		$tgl_pemesanan = $this->db->query("select getDate() as baru")->row_array();

		$data = array(
		        'id_agenda' => $id_agenda['baru'],
		        'tgl_pemesanan' => $tgl_pemesanan['baru'],
		        'id_ruangan' => $id_ruangan,
		        'id_pemesanan' => $id_pemesanan,
		        'keterangan' => $keterangan,
		        'tgl_mulai' => $tgl_mulai,
		        'tgl_selesai' => $tgl_selesai,
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
		$keterangan = htmlspecialchars($_REQUEST['keterangan']);

		$data = array(
		        'id_agenda' => $id_agenda['baru'],
		        'tgl_pemesanan' => $tgl_pemesanan['baru'],
		        'id_ruangan' => $id_ruangan,
		        'id_pemesan' => $id_pemesan,
		        'keterangan' => $keterangan,
		        'tgl_mulai' => $tgl_mulai,
		        'tgl_selesai' => $tgl_selesai,
		);

		$this->db->where('id_agenda', $id_agenda);

		if ($this->db->update('agenda', $data)) {
			return "success";
		} else {
			return "update failed";
		}
	}
}