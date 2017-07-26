# ci_sekpri
bismillahhh.....

TABEL BERKAS
--------------
id_berkas			int

tgl_terima			smalldatetime

penerima_berkas		varchar(10)

pemilik_berkas		varchar(10)

bagian				varchar(10)

isi_berkas			text


BERKAS EKSPEDISI
----------------
id_ekspedisi		int

id_berkas			int

tgl_ekspedisi		smalldatetime

tujuan				varchar(10)

keterangan			text

petugas_ekspedisi	varchar(10)

id_jenis_ekspedisi	int


MASTER EKSPEDISI
---------------
id_jenis_ekspedisi	int

keterangan			text
----------------------------------------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------------------------------------------
TABEL_RUANGAN
----------------
id_ruangan			int

keterangan			text


TABEL_AGENDA
----------------
id_agenda			int
  
id_ruangan			int
  
id_pemesanan		(NIP KARYAWAN)
  
tgl_pemesanan		smalldatetime
  
peserta(ket)		text
  
tgl_mulai			smalldatetime
  
tgl_selesai			smalldatetime