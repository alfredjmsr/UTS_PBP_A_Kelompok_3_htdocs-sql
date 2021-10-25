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



    public function Register_post(){    
        $data = [
            'id_user' => $this->input->post('id_user', TRUE),
            'nama_user' => $this->input->post('nama_user', TRUE),
            'password_user' => md5($this->input->post('password_user', TRUE)),
            'phonenumber_user' => $this->input->post('phonenumber_user', TRUE),
            'birthdate_user' => $this->input->post('birthdate_user', TRUE),
            'role_user' => '2'
        ];
        $response = $this->UsersModel->save_user($data);
        if($response){
            $this->response(array('status' => 'Sukses register'), 200);
        }else{
            $this->response(['error'=>true, 'status'=> 'Register Gagal'], 401);
        }
    }



    public function Login_post(){
            $nama_user = $this->post('nama_user');
            $password_user = $this->post('password_user');
           	$query = $this->db->select('nama_user, role_user, phonenumber_user,birthdate_user')
                ->from('users')
                ->where('nama_user', $nama_user)
                ->where('password_user', md5($password_user))
                ->get()->result();
            if($query)
            {  
                $this->response(array("result"=>$query,'status' => 'Sukses login'), 200);
            }else{
                $this->response(array('status' => 'Gagal login'), 401);
            }
    }

    public function Profil_post(){
        $nama_user = $this->post('nama_user');
        $query = $this->db->select('nama_user, role_user, phonenumber_user,birthdate_user')
            ->from('users')
            ->where('nama_user', $nama_user)
            ->get()->result();
        if($query)
        {  
            $this->response(array("result"=>$query,'status' => 'Berhasil'), 200);
        }else{
            $this->response(array('status' => 'Gagal login'), 401);
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
                                ->where('jabatan_user>','1')
                                ->get()->result();
        if($tampiluser){  
            $this->response(array("result"=>$tampiluser, 200));
        }else{
            $this->response(array('status' => 'Gagal'), 502);
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