<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Users extends REST_Controller {

    //1=sudah dikonfirmasi admin
    //2=masih belum dikonfirmasi
    //3=sudah tidak aktif


    function __construct($config = 'rest') {
        parent::__construct($config);
       // $this->load->database();
        $this->load->model('UsersModel');
        $this->model = $this->UsersModel;
    }

    function index_get() {
        $users = $this->db->get('users')->result();
        $this->response(array("result"=>$users, 200));
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

    public function Register_post(){ 
        $data = [
            'id_user' => $this->input->post('id_user', TRUE),
            'id_cabang' => $this->input->post('id_cabang', TRUE),
            'nama_user' => $this->input->post('nama_user', TRUE),
            'nohp_user' => $this->input->post('nohp_user', TRUE),
            'noktp_user' => $this->input->post('noktp_user', TRUE),
            'email_user' => $this->input->post('email_user', TRUE),
            'password_user' => md5($this->input->post('password_user', TRUE)),
            'jabatan_user' => $this->input->post('jabatan_user', TRUE),
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
   
        // if()

    //    // $cek = $this->UsersModel->cek_user($data);
    //     $row = $this->db->get_where('users', $data)->row();
    //     $result = $row->num_row();
    //     $count = $result['COUNT(*)'];
    //     if($count >= 1){
    //         $this->response(array("result"=>$data,200));
    //     }
    //     else{
    //     $this->response(['error'=>true, 'message'=> 'Login Gagal'], 401);
    //     }
    }

}

?>