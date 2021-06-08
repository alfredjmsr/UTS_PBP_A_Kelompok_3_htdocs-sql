<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Users extends REST_Controller {

    //0=tidak aktif
    //1=sudah dikonfirmasi admin
    //2=masih belum dikonfirmasi
    //3=konfirmasi dibatalkan



    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('UsersModel');
        $this->model = $this->UsersModel;
    }

    function index_get() {
        $users = $this->db->get('users')->result();
        $this->response(array("result"=>$users, 200));
    }
    function searchuser_post() {
        $nama_user = $this->post('nama_user');
        $id_cabang = $this->post('id_cabang');
		//$nama_produk = $this->db->get_where('product',['nama_produk'=>$nama])->result();
		$this->db->like('nama_user',$nama_user,'both');
		$namauser = $this->db->get_where('users',['id_cabang'=>$id_cabang,'status_user'=>'1'])->result();
        $this->response(array("result"=>$namauser, 200));
    }
	
    function getidcabang_post() {
        $id_cabang = $this->post('id_cabang');
        $query = $this->db->query("SELECT id_cabang FROM `".$this->db->dbprefix('cabang')."` WHERE id_cabang='".$id_cabang."'");
        if ($query->num_rows() > 0 )
        {
            $this->response(array('status' => 'Sukses ambil'), 200);
        }else{
            $this->response(array('status' => 'Gagal ambil'), 502);
        }
     
    }

    function tampilregistrasi_post(){
        $status = 2;
        $id_cabang = $this->post('id_cabang');
        $multipleWhere = ['id_cabang' => $id_cabang, 'status' => $status];
        $tampilregis = $this->db->select('id_user,nama_user, noktp_user, jabatan_user, nohp_user, email_user')
                                ->from('users')
                                ->where('status_user', $status)
                                ->where('id_cabang', $id_cabang)
                                ->get()->result();
        $this->response(array("result"=>$tampilregis, 200));
    }

    function tampilregistrasikasir_post(){
        $status = 2;
        $id_cabang = $this->post('id_cabang');
        $jabatan_user = $this->post('jabatan_user');
        $multipleWhere = ['id_cabang' => $id_cabang, 'status' => $status];
        $tampilregis = $this->db->select('nama_user, noktp_user, jabatan_user')
                                ->from('users')
                                ->where('status_user', $status)
                                ->where('id_cabang', $id_cabang)
                                ->where('jabatan_user', $jabatan_user)
                                ->get()->result();
        $this->response(array("result"=>$tampilregis, 200));
    }

    function getjabatan_post(){
        $id_cabang = $this->post('id_cabang',TRUE);
        $nama_user = $this->post('nama_user',TRUE);
        $getjabatan = $this->db->select('jabatan_user')
                            ->from('users')
                            ->where('status_user', '1')
                            ->where('id_cabang', $id_cabang)
                            ->where('nama_user', $nama_user)
                            ->get()->result();
        if ($getjabatan)
        {
            $this->response(array('status' => 'Sukses cari'), 200);
        }else{
            $this->response(array('status' => 'Gagal cari'), 502);
        }
    }

    function updateregistrasi_post(){
        $status = 2;
        $id_user = $this->post('id_user',TRUE);
        $id_cabang = $this->post('id_cabang',TRUE);
        $nama_user = $this->post('nama_user',TRUE);
        $updateregis = $this->db->set('status_user','1')
                                ->where('id_user', $id_user)
                                ->where('status_user', $status)
                                ->where('id_cabang', $id_cabang)
                                ->where('nama_user', $nama_user)
                                ->update('users');
        $this->response(array("result"=>$updateregis, 200));
    }

    function deleteregistrasi_post(){
        $status = 2;
        $id_user = $this->post('id_user',TRUE);
        $id_cabang = $this->post('id_cabang',TRUE);
        $nama_user = $this->post('nama_user',TRUE);
        $updateregis = $this->db->set('status_user','3')
                                ->where('id_user', $id_user)
                                ->where('status_user', $status)
                                ->where('id_cabang', $id_cabang)
                                ->where('nama_user', $nama_user)
                                ->update('users');
        $this->response(array("result"=>$updateregis, 200));
    
        
    }

    public function Register_post(){ 
        $date = date("Y-m-d");
        $data = [
            'id_user' => $this->input->post('id_user', TRUE),
            'id_cabang' => $this->input->post('id_cabang', TRUE),
            'nama_user' => $this->input->post('nama_user', TRUE),
            'nohp_user' => $this->input->post('nohp_user', TRUE),
            'noktp_user' => $this->input->post('noktp_user', TRUE),
            'email_user' => $this->input->post('email_user', TRUE),
            'password_user' => md5($this->input->post('password_user', TRUE)),
            'jabatan_user' => $this->input->post('jabatan_user', TRUE),
            'tanggal_user' => $date,
            'status_user' => '2'
        ];
        $response = $this->UsersModel->save_user($data);

        if($response){
            $this->response(array('status' => 'Sukses register'), 200);
        }else{
            $this->response(['error'=>true, 'status'=> 'Register Gagal'], 401);
        }
    }



    public function Login_post(){

            $id_cabang = $this->post('id_cabang');
            $nama_user = $this->post('nama_user');
            $password_user = $this->post('password_user');
            $jabatan_user = $this->post('jabatan_user');
            $status_user = 1;
            $query = $this->db->query("SELECT id_user FROM `".$this->db->dbprefix('users')."` WHERE id_cabang='".$id_cabang."' AND nama_user='".$nama_user."' AND 
            password_user='".md5($password_user)."' AND jabatan_user='".$jabatan_user."' AND status_user = '".$status_user."'");
           	
            if ($query->num_rows() > 0 )
            {  
                $this->response(array('status' => 'Sukses login'), 200);
            }else{
                $this->response(array('status' => 'Gagal login'), 502);
            }
    }

    public function forgotpass_post()
	{
            $nama_user=$this->input->post('nama_user');
            $id_cabang=$this->input->post('id_cabang');
			$que=$this->db->query("select password_user,email_user from users where nama_user='$nama_user' and id_cabang ='$id_cabang'");
			$row=$que->row();
			$user_email=$row->email_user;
            $password_user=$row->password_user;

            $this->load->library('email');
            $config = array();
            $config['protocol']="smtp";
            $config['charset'] ='utf-8';
            $config['useragent'] = 'Codeigniter';
            $config['mailtype']= "html";
            $config['smtp_host']= "smtp.gmail.com";
            $config['smtp_port']= "465";
            $config['smtp_timeout']= "400";
            $config['smtp_user']= "eccafex@gmail.com"; 
            $config['smtp_pass']= "ec123456!";
            $config['smtp_crypto']  = "ssl" ;
            $config['crlf']="\r\n"; 
            $config['newline']="\r\n"; 
            $config['wordwrap'] = TRUE;
            
        //memanggil library email dan set konfigurasi untuk pengiriman email
       
            $this->email->initialize($config);
            $this->email->from('eccafex@gmail.com','EC Cafe'); 
            $this->email->to($user_email);
            $this->email->subject('Kelola Akun');
            $this->email->message(
                'Salin kode berikut dan paste ke aplikasi untuk validasi => '.$password_user); 
            $resp = $this->email->send();
             if($resp)
             {
                $this->response(array('status' => 'berhasil'),200);
             }       
             else{
                $this->response(array('status' => 'Gagal'), 502);
                 echo "tidak terkirim"; 
                echo $this->email->print_debugger();
                die ;
             }
    }

    function verifikasikode_post(){
        $status = 1;
        $id_cabang = $this->post('id_cabang');
        $nama_user = $this->post('nama_user');
        $password_user = $this->post('password_user');
       // $jabatan_user = $this->post('jabatan_user');
        $verifikasi = $this->db->select('id_user, nama_user')
                                ->from('users')
                                ->where('status_user', $status)
                                ->where('id_cabang', $id_cabang)
                                ->where('nama_user', $nama_user)
                                ->where('password_user', $password_user)
                                //->where('jabatan_user', $jabatan_user)
                                ->get()->row();
        if($verifikasi){  
            $this->response(array('status'=>'berhasil', 200));
        }else{
            $this->response(array('status' => 'Gagal'), 502);
        }
    }

    public function resetpassword_post(){
        $status = 1;
        $id_cabang = $this->input->post('id_cabang', TRUE);
        $nama_user = $this->input->post('nama_user', TRUE);
        $data = [
            'password_user' => md5($this->input->post('password_user', TRUE)),
        ];
        $response = $this->UsersModel->update_password($id_cabang,$data,$nama_user);
        if($response){
            $this->response(array('status' => 'berhasil'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'User gagal diupdate'], 401);
        }

    }

    function tampiluser_post(){
        $status = 1;
        $id_cabang = $this->post('id_cabang');
       // $jabatan_user = $this->post('jabatan_user');
        $tampiluser = $this->db->select('id_user,nama_user,nohp_user, noktp_user, jabatan_user, email_user')
                                ->from('users')
                                ->where('status_user', $status)
                                ->where('id_cabang', $id_cabang)
                                //->where('jabatan_user', $jabatan_user)
                                ->get()->result();
        if($tampiluser){  
            $this->response(array("result"=>$tampiluser, 200));
        }else{
            $this->response(array('status' => 'Gagal login'), 502);
        }
    }

    function profileuser_post(){
        $status = 1;
        $id_cabang = $this->post('id_cabang');
        $nama_user = $this->post('nama_user');
       // $jabatan_user = $this->post('jabatan_user');
        $profile = $this->db->select('id_user,nama_user,nohp_user, noktp_user, jabatan_user, email_user')
                                ->from('users')
                                ->where('status_user', $status)
                                ->where('id_cabang', $id_cabang)
                                ->where('nama_user', $nama_user)
                                //->where('jabatan_user', $jabatan_user)
                                ->get()->result();
        if($profile){  
            $this->response(array("result"=>$profile, 200));
        }else{
            $this->response(array('status' => 'Gagal'), 502);
        }
    }

    public function updateprofile_post(){
        $id = $this->input->post('id_user', TRUE);
        $id_cabang = $this->input->post('id_cabang', TRUE);
        $jabatan_user = $this->input->post('jabatan_user', TRUE);
        $data = [
            'nama_user' => $this->input->post('nama_user', TRUE),
            'nohp_user' => $this->input->post('nohp_user', TRUE),
            'noktp_user' => $this->input->post('noktp_user', TRUE),
            'email_user' => $this->input->post('email_user', TRUE),
        ];
        $response = $this->UsersModel->update_user($id,$data,$jabatan_user);
        
        if($response){
            $this->response(array('message' => 'berhasil'), 200);  
        }else{
            $this->response(['error'=>true, 'message'=> 'User gagal diupdate'], 401);
        }

    }

    public function updateuser_post(){
        $id = $this->input->post('id_user', TRUE);
        $id_cabang = $this->input->post('id_cabang', TRUE);
        $jabatan_user = $this->input->post('jabatan_user', TRUE);
        $data = [
            'nama_user' => $this->input->post('nama_user', TRUE),
            'nohp_user' => $this->input->post('nohp_user', TRUE),
            'noktp_user' => $this->input->post('noktp_user', TRUE),
            'email_user' => $this->input->post('email_user', TRUE),
        ];
        $response = $this->UsersModel->update_user($id,$data,$jabatan_user);
        
        if($response){
            $this->response(array('status' => 'User sukses diupdate'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'User gagal diupdate'], 401);
        }

    }

    function deleteuser_post(){
        $status = 1;
        $id_user = $this->post('id_user',TRUE);
        $id_cabang = $this->post('id_cabang',TRUE);
        $nama_user = $this->post('nama_user',TRUE);
        $deleteuser = $this->db->set('status_user','0')
                                ->where('id_user', $id_user)
                                ->where('status_user', $status)
                                ->where('nama_user', $nama_user)
                                ->update('users');
        if($deleteuser){
            $this->response(array('status' => 'User sukses dihapus'), 200);
        }else{
            $this->response(['error'=>true, 'status'=> 'User gagal diupdate'], 401);
        }
    
        
    }

    public function getusername_post(){
      
        $status = '1';
        $nama_user = $this->input->post('nama_user', TRUE);
        $query = $this->db->query("SELECT * FROM `".$this->db->dbprefix('users')."` WHERE nama_user = '".$nama_user."'");
        if ($query->num_rows() > 0 )
        {  
            $this->response(['error'=>true, 'status'=> 'Username sudah ada'], 401);         
        }else{
            $this->response(array('status' => 'username tidak ada'), 200);   
        }
    }
}


?>