<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Cabang extends REST_Controller {

    //1=masih aktif
    //2=tidak aktif
    //3=sudah tidak aktif


    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('CabangModel');
        $this->model = $this->CabangModel;
    }

    function index_get() {
        $cabang = $this->db->get_where('cabang',['status'=>'1'])->result();
        $this->response(array("cabang"=>$cabang, 200));
    }

    function searchcabang_post() {
        $nama_cabang = $this->post('nama_cabang');
		//$nama_produk = $this->db->get_where('product',['nama_produk'=>$nama])->result();
		$this->db->like('nama_cabang',$nama_cabang,'both');
		$namacabang = $this->db->get_where('cabang',['status'=>'1'])->result();
        $this->response(array("cabang"=>$namacabang, 200));
    }

    public function addcabang_post(){
        $date=date("Y-m-d");
        $data = [
            'nama_cabang' => $this->input->post('nama_cabang', TRUE),
            'notelp_cabang' => $this->input->post('notelp_cabang', TRUE),
            'alamat_cabang' => $this->input->post('alamat_cabang', TRUE),
            'tanggal_cabang' => $date,
            'status' => '1'
        ];
        $response = $this->CabangModel->save_cabang($data);
        
        if($response){
            $this->response(array('status' => 'Cabang sukses ditambah'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Cabang gagal ditambah'], 401);
        }

    }

    public function updatecabang_post(){
        $date=date("Y-m-d");
        $id = $this->input->post('id_cabang', TRUE);
        $data = [
            'nama_cabang' => $this->input->post('nama_cabang', TRUE),
            'notelp_cabang' => $this->input->post('notelp_cabang', TRUE),
            'alamat_cabang' => $this->input->post('alamat_cabang', TRUE),
            'update_cabang' => $date,
            'status' => '1'

        ];
        $response = $this->CabangModel->update_cabang($id,$data);
        
        if($response){
            $this->response(array('status' => 'Cabang sukses diupdate'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Cabang gagal diupdate'], 401);
        }

    }
  

    public function deletecabang_post(){
        $date=date("Y-m-d");
        $id = $this->input->post('id_cabang', TRUE);
        $data = [
            'status' => '2'
        ];
        $response = $this->CabangModel->update_cabang($id,$data);   
        if($response){
            $this->response(array('status' => 'Cabang sukses dihapus'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Cabang gagal dihapus'], 401);
        }

    }


    public function registerowner_post(){ 

        $id = $this->db->select('id_cabang')
                        ->from('cabang')
                        ->order_by('tanggal_cabang', 'DESC')
                        ->limit(1)
                        ->get()->row();

        $data = [
            'id_cabang' => $id->id_cabang,
            'nama_user' => $this->input->post('nama_user', TRUE),
            'nohp_user' => '1234435656',
            'noktp_user' => '49574560968',
            'email_user' => 'ownerowner@gmail.com',
            'password_user' => md5('111111'),
            'jabatan_user' => '1',
            'status_user' => '1'
        ];
        $response = $this->CabangModel->owner_cabang($data);

        if($response){
            $this->response(array('status' => 'Sukses register'), 200);
        }else{
            $this->response(['error'=>true, 'status'=> 'Register Gagal'], 401);
        }
    }

}

?>