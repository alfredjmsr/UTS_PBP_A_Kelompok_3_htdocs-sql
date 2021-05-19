<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Diskon extends REST_Controller {

    //1=sudah dikonfirmasi admin
    //2=masih belum dikonfirmasi
    //3=sudah tidak aktif


    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('DiskonModel');
        $this->model = $this->DiskonModel;
    }

    function index_get() {
        $diskon = $this->db->get_where('diskon',['status'=>'1'])->result();
        $this->response(array("diskon"=>$diskon, 200));
    }
    public function adddiskon_post(){
        $date=date("Y-m-d");
        $data = [
            'nama_diskon' => $this->input->post('nama_diskon', TRUE),
            'min_bayar' => $this->input->post('min_bayar', TRUE),
            'persen_diskon' => $this->input->post('persen_diskon', TRUE),
            'harga_diskon' => $this->input->post('harga_diskon', TRUE),
            'max_diskon' => $this->input->post('max_diskon', TRUE),
            'exp_diskon'=> $this->input->post('exp_diskon', TRUE),
            'tgl_diskon'=> $date,
            'status' => '1'
        ];
        $response = $this->DiskonModel->save_diskon($data);
        
        if($response){
            $this->response(array('status' => 'Diskon sukses ditambah'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Diskon gagal ditambah'], 401);
        }

    }

    public function updatediskon_post(){
        $date=date("Y-m-d");
        $id = $this->input->post('id_diskon', TRUE);
        $data = [
            'nama_diskon' => $this->input->post('nama_diskon', TRUE),
            'min_bayar' => $this->input->post('min_bayar', TRUE),
            'persen_diskon' => $this->input->post('persen_diskon', TRUE),
            'harga_diskon' => $this->input->post('harga_diskon', TRUE),
            'max_diskon' => $this->input->post('max_diskon', TRUE),
            'exp_diskon'=> $this->input->post('exp_diskon', TRUE),
            'tgl_diskon'=> $date,
            'status' => '1'
        ];
        $response = $this->DiskonModel->update_diskon($id,$data);
        
        if($response){
            $this->response(array('status' => 'Diskon sukses diupdate'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Diskon gagal diupdate'], 401);
        }

    }


    public function getdiskonpersen_post(){
        $nama_diskon = $this->post('nama_diskon');
        $date=date("Y-m-d");
        $diskon = $this->db->select('id_diskon,min_bayar,persen_diskon,harga_diskon,max_diskon,exp_diskon')
                                ->from('diskon')
                                ->where('nama_diskon', $nama_diskon)
                                ->where('exp_diskon >=', $date)
                                ->get()->row();
        if($diskon){
            $this->response($diskon);                  
        }else{
            $this->response(['error'=>true, 'status'=> 'Diskon tidak tersedia'], 401);
        }                      
        
    }    

    public function deletediskon_post(){
        $date=date("Y-m-d");
        $id = $this->input->post('id_diskon', TRUE);
        $data = [
            'status' => '2'
        ];
        $response = $this->DiskonModel->update_diskon($id,$data);   
        if($response){
            $this->response(array('status' => 'Diskon sukses dihapus'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Diskon gagal dihapus'], 401);
        }

    }

}

?>