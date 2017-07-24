<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berkasmodel extends CI_Model {

	public function tampil_data_berkas()
	{
		$query = "select c.keterangan as KE, d.keterangan as POSISI, a.TGL_KIRIM, b.nama_bagian as bagian, a.keterangan, e.nama_lengkap as PENGIRIM, f.nama_lengkap as pengambil, a.status from tbl_berkas a
left join (SELECT left(kode_jabatan,4) as kode, nama_bagian FROM bagian group by left(kode_jabatan,4), nama_bagian) b on a.bagian_id = b.kode
left join (SELECT * FROM bagian where kode_manajer = '00' and kode_asisten_manajer = '00' and kode_supervisor = '00' and kode_staff = '00' and kode_direktur in (1,2,3)) c on a.DIR_AWAL_ID = c.kode_direktur
left join (SELECT * FROM bagian where kode_manajer = '00' and kode_asisten_manajer = '00' and kode_supervisor = '00' and kode_staff = '00' and kode_direktur in (1,2,3)) d on a.DIR_AKHIR_ID = d.kode_direktur
left join karyawan e on a.USER_KIRIM = e.nip
left join karyawan f on a.USER_AMBIL = F.nip";
	return $query->result();
	}

}