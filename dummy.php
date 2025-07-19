validasi sspd

1. rekam ke setoran_ke-bank_header
2. insert ke setoran_pajak-retribusi


setoran_pajak_retribusi 

CREATE TABLE setoran_pajak_retribusi
(
  setorpajret_id serial NOT NULL,
  setorpajret_id_spt integer,
  setorpajret_no_bukti integer NOT NULL,
  setorpajret_tgl_bayar date NOT NULL,
  setorpajret_jlh_bayar double precision NOT NULL DEFAULT 0,
  setorpajret_via_bayar smallint NOT NULL,
  setorpajret_jenis_ketetapan smallint,
  setorpajret_id_wp integer,
  setorpajret_jenis_pajakretribusi smallint,
  setorpajret_spt_periode smallint,
  setorpajret_no_spt integer,
  setorpajret_periode_jual1 date,
  setorpajret_periode_jual2 date,
  setorpajret_jatuh_tempo date,
  setorpajret_dibuat_tanggal timestamp without time zone DEFAULT now(),
  setorpajret_dibuat_oleh character varying(100)
)
npwprd character varying(20) NOT NULL,
  kode_billing character varying(20) NOT NULL,
  tahun_pajak character(4) NOT NULL,
  pembayaran_ke integer NOT NULL,
  kd_rekening character varying(10) NOT NULL,
  nm_rekening character varying(50),
  masa_awal date NOT NULL,
  masa_akhir date NOT NULL,
  tagihan double precision,
  denda double precision,
  sptpd_yg_dibayar double precision,
  tgl_pembayaran timestamp without time zone NOT NULL,
  tgl_rekam_byr timestamp without time zone NOT NULL,
  nip_rekam_byr character varying(10) NOT NULL,
  ntp character varying(20) NOT NULL,
  tgl_ntp timestamp without time zone,
  status_reversal integer,
  tgl_reversal timestamp without time zone,
  nip_reversal character varying(10)



insert into setoran_pajak_retribusi