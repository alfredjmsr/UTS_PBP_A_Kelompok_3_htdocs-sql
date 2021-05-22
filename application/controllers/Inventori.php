<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Inventori extends REST_Controller {

    //1=masih aktif
    //2=tidak aktif
    //3=sudah tidak aktif


    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('InventoriModel');
        $this->model = $this->InventoriModel;
    }

    function tampilinventori_post() {
            $status = 1;
            $id_cabang = $this->post('id_cabang');
            $inventori = $this->db->select('id_inventori,nama_bahanbaku,tanggal_buat')
                                    ->from('inventori')
                                    ->where('status', $status)
                                    ->where('id_cabang', $id_cabang)
                                    ->get()->result();
            $this->response(array("result"=>$inventori, 200));
                                    
    }

    function tampilbahanbaku_post() {
        $id_inventori = $this->post('id_inventori');
        $id_cabang = $this->post('id_cabang');
        $nama_bahanbaku = $this->post('nama_bahanbaku');
        $bahanbaku = $this->db->select('jumlah_bahanbaku, harga_bahanbaku, exp_bahanbaku, tanggal_masuk, tanggal_keluar')
                                ->from('detailinventori')
                                ->where('id_inventori', $id_inventori)
                                ->where('id_cabang', $id_cabang)
                                ->where('nama_bahanbaku', $nama_bahanbaku)
                                ->get()->result();
        $this->response(array("result"=>$bahanbaku, 200));
                                
    }

    public function tambahinventori_post(){
        $date=date("Y-m-d");
        $data = [
            'id_cabang' => $this->input->post('id_cabang', TRUE),
            'nama_bahanbaku' => $this->input->post('nama_bahanbaku', TRUE),
            'tanggal_buat' => $date,
            'status' => '1'
        ];
        $response = $this->InventoriModel->save_inventori($data);
        
        if($response){
            $this->response(array('status' => 'Inventori sukses ditambah'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Inventori gagal ditambah'], 401);
        }

    }

    public function tambahbahanbaku_post(){
        $date=date("Y-m-d");
        $data = [
            'id_inventori' => $this->input->post('id_inventori', TRUE),
            'id_cabang' => $this->input->post('id_cabang', TRUE),
            'nama_bahanbaku' => $this->input->post('nama_bahanbaku', TRUE),
            'jumlah_bahanbaku' => $this->input->post('jumlah_bahanbaku', TRUE),
            'harga_bahanbaku' => $this->input->post('harga_bahanbaku', TRUE),
            'exp_bahanbaku' => $this->input->post('exp_bahanbaku', TRUE),
            'tanggal_masuk' => $date,
            'tanggal_keluar' => $this->input->post('tanggal_keluar', TRUE),
            'status' => '1'
        ];
        $response = $this->InventoriModel->save_bahanbaku($data);
        
        if($response){
            $this->response(array('status' => 'Bahan baku sukses ditambah'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Bahan baku gagal ditambah'], 401);
        }

    }

    public function getbahanbaku_post(){
        $date=date("Y-m-d");
        $id_inventori = $this->post('id_inventori');
        $id_cabang = $this->post('id_cabang');
        $nama_bahanbaku = $this->post('nama_bahanbaku');
        $bahanbaku = $this->db->select('id_detailinventori,jumlah_bahanbaku')
                              ->from('detailinventori')
                              ->where('id_inventori', $id_inventori)
                              ->where('id_cabang', $id_cabang)
                              ->where('nama_bahanbaku', $nama_bahanbaku)
                              ->where('exp_bahanbaku >=', $date)
                              ->where('status', '1')
                              ->order_by('tanggal_masuk', 'ASC')
                              ->order_by('id_detailinventori', 'ASC')
                              ->limit(1)
                              ->get()->result();
        if($bahanbaku){
            $this->response(array("result"=>$bahanbaku, "status" =>"true", 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Bahan baku tidak tersedia'], 401);
        }
        
    }

    public function updatebahanbaku_post(){
        $date=date("Y-m-d");
        $id_cabang = $this->input->post('id_cabang', TRUE);
        $id_detailinventori = $this->input->post('id_detailinventori', TRUE);
        $data = [
            'tanggal_keluar' => $date,
            'jumlah_bahanbaku' => $this->input->post('jumlah_bahanbaku', TRUE),
            'status' => $this->input->post('status', TRUE),
        ];
        $response = $this->InventoriModel->update_bahanbaku($id_cabang,$id_detailinventori,$data);
        
        if($response){
            $this->response(array('status' => 'bahanbaku sukses diupdate'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'bahanbaku gagal diupdate'], 401);
        }
        
    }  

   
}

?>