<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Detailtransaksi extends REST_Controller {
    //0=sudah dikonfirmasi
    //1=belum dikonfirmasi
    //2=refund

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('DetailTransaksiModel');
        $this->model = $this->DetailTransaksiModel;
    }

    //Menampilkan data detailtransaksi yang status 1
    function index_get() {
        $namapembeli = $this->post('nama_pembeli');
        $idcabang = $this->post('id_cabang');
        
        $this->db->select('id_detailtransaksi');
        $this->db->from('detailtransaksi');
        $this->db->where('status','1');
        $this->db->where('nama_pembeli', $namapembeli);
        $this->db->where('id_cabang', $idcabang);
        $detail = $this->db->get()->result();
        $this->response(array("result"=>$detail, 200));
    }

    public function Addcart_post(){
        $date=date("Y-m-d");
        $data = [
            'id_detailtransaksi' => $this->input->post('id_detailtransaksi', TRUE),
            'id_produk' => $this->input->post('id_produk', TRUE),
            'id_cabang' => $this->input->post('id_cabang', TRUE),
            'nama_user' => $this->input->post('nama_user', TRUE),
            'jumlah_item' => $this->input->post('jumlah_item', TRUE),
            'harga_subtotal' => $this->input->post('harga_subtotal', TRUE),
            'tanggal_buat'=> $date,
            'nama_pembeli' => $this->input->post('nama_pembeli', TRUE),
            'status' => '1'
        ];
        $response = $this->DetailTransaksiModel->save_detaildtransaksi($data);

        if($response){
            $this->response(array('status' => 'Sukses ditambah ke keranjang'), 200);
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal menambah ke keranjang'], 401);
        }
    }
}