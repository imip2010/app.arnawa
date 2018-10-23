<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class LoginM extends CI_Model{
	function __construct(){
		parent:: __construct();
		$this->load->database();
	}
	public function ceknum($email_akun, $password){ //cek akun di db pengguna jabatan (berapa rows)
		$this->db->where('email_akun', $email_akun);
		$this->db->where('password', $password);
		return $this->db->get('akun');
	}

	public function cek_fitur($id_akun){ //cek akun di db pengguna jabatan (berapa rows)
		$this->db->where('id_akun', $id_akun);
		return $this->db->get('detail_fitur');
	}

	// alamat
	public function get_all_provinsi(){
		$response = array();
		$this->db->select('*');
		$this->db->from('propinsi');
		$this->db->order_by('nama_propinsi');
		$query = $this->db->get();
		$response = $query->result_array();
		return $response;
	}

	public function get_kabupaten_kota($postData){
		$response = array();
		$this->db->select('*');
		$this->db->from('kabupaten_kota');
		$this->db->where('id_propinsi', $postData['id_propinsi']);
		$this->db->order_by('nama_kabupaten_kota');
		$query = $this->db->get();
		$response = $query->result_array();
		return $response;
	}

	public function get_kecamatan($postData){
		$response = array();
		$this->db->select('*');
		$this->db->from('kecamatan');
		$this->db->where('id_kabupaten_kota', $postData['id_kabupaten_kota']);
		$this->db->order_by('nama_kecamatan');
		$query = $this->db->get();
		$response = $query->result_array();
		return $response;
	}

	public function get_kelurahan($postData){
		$response = array();
		$this->db->select('*');
		$this->db->from('kelurahan');
		$this->db->where('id_kecamatan', $postData['id_kecamatan']);
		$this->db->order_by('nama_kelurahan');
		$query = $this->db->get();
		$response = $query->result_array();
		return $response;
	}

	public function get_all_data($id_akun){
		$this->db->select('*');
		$this->db->from('akun R');
		// $this->db->join('kelurahan L', 'R.id_kelurahan = L.id_kelurahan');
		// $this->db->join('kecamatan C', 'L.id_kecamatan = C.id_kecamatan');
		// $this->db->join('kabupaten_kota K', 'C.id_kabupaten_kota = K.id_kabupaten_kota');
		// $this->db->join('propinsi P', 'K.id_propinsi = P.id_propinsi');
		$this->db->where('R.id_akun', $id_akun);
		$query = $this->db->get();
		if($query){
			return $query;
		}else{
			echo "tidak ditemukan";
		}
	}

	public function get_all_data2(){
		$this->db->select('*');
		$this->db->from('akun R');
		$this->db->join('kelurahan L', 'R.id_kelurahan = L.id_kelurahan');
		$this->db->join('kecamatan C', 'L.id_kecamatan = C.id_kecamatan');
		$this->db->join('kabupaten_kota K', 'C.id_kabupaten_kota = K.id_kabupaten_kota');
		$this->db->join('propinsi P', 'K.id_propinsi = P.id_propinsi');
		$query = $this->db->get();
		if($query){
			return $query;
		}else{
			echo "tidak ditemukan";
		}
	}
	
	public function verifyemail($key){  //post konfirmasi email ubah value status_email jadi 1 (aktif)
		$data = array(
			'status_email' => 'aktif',
		);  
		$this->db->where('md5(email_akun)', $key);
		return $this->db->update('akun', $data);  
	}

	public function update($id, $data){
		$this->db->where('id_detail_fitur', $id);
		if($this->db->update('detail_fitur', $data)){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function get_history_status($id_detail_fitur){
		$this->db->select('*');
		$this->db->from('log_status L');
		$this->db->join('detail_fitur D','D.id_detail_fitur = L.id_detail_fitur');
		$this->db->where('D.id_detail_fitur', $id_detail_fitur);
		$query = $this->db->get();
		if($query){
			return $query;
		}else{
			echo "tidak ditemukan";
		}
	}

	public function insert_fitur($data){
		$this->db->insert('detail_fitur', $data);
		return TRUE;
	}

	public function get_all_fitur(){
		$query = $this->db->get('fitur');
		return $query;
	}

	public function get_fitur_by_akun($id_akun){
		$this->db->select('*');
		$this->db->from('detail_fitur D');
		$this->db->join('akun A', 'D.id_akun = A.id_akun');
		$this->db->join('fitur F', 'D.id_fitur = F.id_fitur');
		$this->db->where('D.id_akun',$id_akun);
		// $this->db->where('D.approval = "diterima"');
		$query = $this->db->get();
		if($query){
			return $query;
		}else{
			echo "tidak ditemukan";
		}
	}

	public function get_fitur_by_akun_setuju($id_akun){
		$this->db->select('*');
		$this->db->from('detail_fitur D');
		$this->db->join('akun A', 'D.id_akun = A.id_akun');
		$this->db->join('fitur F', 'D.id_fitur = F.id_fitur');
		$this->db->where('D.id_akun',$id_akun);
		$this->db->where('D.status = "menunggu"');
		$query = $this->db->get();
		if($query){
			return $query;
		}else{
			echo "tidak ditemukan";
		}
	}

	public function get_detail_fitur_by_akun($id_akun){
		$this->db->select('*');
		$this->db->from('detail_fitur D');
		$this->db->join('akun A', 'D.id_akun = A.id_akun');
		$this->db->join('fitur F', 'D.id_fitur = F.id_fitur');
		$this->db->join('tagihan T', 'D.id_detail_fitur = T.id_detail_fitur');
		$this->db->where('D.id_akun', $id_akun);
		$this->db->order_by('D.id_detail_fitur');
		$this->db->group_by('D.id_detail_fitur');
		$query = $this->db->get();
		if($query){
			return $query;
		}else{
			echo "tidak ditemukan";
		}
	}

	public function get_tagihan_by_fitur($id_detail_fitur){
		$this->db->select('*');
		$this->db->from('tagihan T');
		$this->db->join('detail_fitur D','T.id_detail_fitur = D.id_detail_fitur');
		$this->db->join('akun A','A.id_akun = D.id_akun');
		$this->db->join('fitur F','D.id_fitur = F.id_fitur');
		$this->db->where('D.id_detail_fitur', $id_detail_fitur);
		$this->db->order_by('T.id_tagihan','DESC');
		$query = $this->db->get();
		if($query){
			return $query;
		}else{
			echo "tidak ditemukan";
		}

		
	}

	public function get_tagihan_by_fitur_group($id_detail_fitur){
		$this->db->select('*');
		$this->db->from('tagihan T');
		$this->db->where('T.id_detail_fitur', $id_detail_fitur);
		$this->db->order_by('T.id_tagihan','DESC');
		$query = $this->db->get();
		if($query){
			return $query;
		}else{
			echo "tidak ditemukan";
		}

		
	}

	// reset_password
	public function getByEmail($email){
		$this->db->where('email_akun',$email);
		$result = $this->db->get('akun');
		return $result;
	}

	public function simpanToken($data){
		$this->db->insert('forget_password', $data);
		return $this->db->affected_rows();
	}

	public function cekToken($token){
		$this->db->where('token',$token);
		$result = $this->db->get('forget_password');
		return $result;
	}

	public function ubahData($data, $id_akun){
		$this->db->where('id_akun', $id_akun);
		$this->db->update('akun', $data);
		return TRUE;
	}

	// user
	// fitur
	public function hapus_detail_fitur($id_detail_fitur){
		$this->db->where('id_detail_fitur', $id_detail_fitur);
		$this->db->delete('detail_fitur');
		return TRUE;
	}

	// tagihan
	public function get_tagihan_by_id($id_detail_fitur){
		$this->db->where('id_detail_fitur', $id_detail_fitur);
		$this->db->order_by('id_tagihan','DESC');
		return $this->db->get('tagihan');
	}

	public function insert_tagihan($data){
		$this->db->insert('tagihan', $data);
		return TRUE;
	}
}