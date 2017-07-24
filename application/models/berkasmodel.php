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
/*        	$this->db->select("a.Nomor as NOMOR, c.keterangan as KE_desc, d.keterangan as POSISI_desc, (CONVERT(varchar(10),TGL_KIRIM,101)+''+RIGHT(CONVERT(varchar(19),TGL_KIRIM,120),9)) as TGL_KIRIM, b.nama_bagian as bagian_desc, a.keterangan, e.nama_lengkap as PENGIRIM_desc, f.nama_lengkap as pengambil_desc,case when a.status='1' then 'Sudah Terkirim' else 'Belum Terkirim' end as
							status_desc, a.DIR_AWAL_ID as KE, a.DIR_AKHIR_ID as POSISI, b.kode as bagian, e.nip as PENGIRIM, f.nip as pengambil, a.status as status");
        	$this->db->from("tbl_berkas a
left join (SELECT left(kode_jabatan,4) as kode, nama_bagian FROM bagian group by left(kode_jabatan,4), nama_bagian) b on a.bagian_id = b.kode
left join (SELECT * FROM bagian where kode_manajer = '00' and kode_asisten_manajer = '00' and kode_supervisor = '00' and kode_staff = '00' and kode_direktur in (1,2,3)) c on a.DIR_AWAL_ID = c.kode_direktur
left join (SELECT * FROM bagian where kode_manajer = '00' and kode_asisten_manajer = '00' and kode_supervisor = '00' and kode_staff = '00' and kode_direktur in (1,2,3)) d on a.DIR_AKHIR_ID = d.kode_direktur
left join karyawan e on a.USER_KIRIM = e.nip
left join karyawan f on a.USER_AMBIL = F.nip");*/
			$this->db->select("a.*, b.nama_bagian bagian_desc, c.nama_lengkap penerima_berkas_desc, d.nama_lengkap pemilik_berkas_desc");
			$this->db->from("berkas a");
			$this->db->join("bagian b", "a.kode_jabatan=b.kode_jabatan");
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

/*		$KE = htmlspecialchars($_REQUEST['KE']);
		$POSISI = htmlspecialchars($_REQUEST['POSISI']);
		$TGL_KIRIM = htmlspecialchars($_REQUEST['TGL_KIRIM']);
		$bagian = htmlspecialchars($_REQUEST['bagian']);
		$keterangan = htmlspecialchars($_REQUEST['keterangan']);
		$PENGIRIM = htmlspecialchars($_REQUEST['PENGIRIM']);
		$pengambil = htmlspecialchars($_REQUEST['pengambil']);
		$status = htmlspecialchars($_REQUEST['status']);

		$nomor = $this->db->query("select dbo.getNomorDokumen() as baru")->row_array();

		$data = array(
		        'NOMOR' => $nomor['baru'],
		        'BAGIAN_ID' => $bagian,
		        'TGL_KIRIM' => $TGL_KIRIM,
		        'USER_KIRIM' => $PENGIRIM,
		        'USER_AMBIL' => $pengambil,
		        'DIR_AWAL_ID' => $KE,
		        'DIR_AKHIR_ID' => $POSISI,
		        'KETERANGAN' => $keterangan,
		        'STATUS' => $status,
		);

		if ($this->db->insert('TBL_BERKAS', $data)) {
			return "success";
		} else {
			return "insert failed";
		}*/
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
/*		$KE = htmlspecialchars($_REQUEST['KE']);
		$POSISI = htmlspecialchars($_REQUEST['POSISI']);
		$TGL_KIRIM = htmlspecialchars($_REQUEST['TGL_KIRIM']);
		$bagian = htmlspecialchars($_REQUEST['bagian']);
		$keterangan = htmlspecialchars($_REQUEST['keterangan']);
		$PENGIRIM = htmlspecialchars($_REQUEST['PENGIRIM']);
		$pengambil = htmlspecialchars($_REQUEST['pengambil']);
		$status = htmlspecialchars($_REQUEST['status']);

		$data = array(
		        'BAGIAN_ID' => $bagian,
		        'TGL_KIRIM' => $TGL_KIRIM,
		        'USER_KIRIM' => $PENGIRIM,
		        'USER_AMBIL' => $pengambil,
		        'DIR_AWAL_ID' => $KE,
		        'DIR_AKHIR_ID' => $POSISI,
		        'KETERANGAN' => $keterangan,
		        'STATUS' => $status,
		);

		$this->db->where('id_berkas', $id_berkas);

		if ($this->db->update('berkas', $data)) {
			return "success";
		} else {
			return "update failed";
		}*/
	}
}