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
