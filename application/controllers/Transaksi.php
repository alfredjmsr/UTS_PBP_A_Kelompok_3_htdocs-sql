<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Transaksi extends REST_Controller {
    //0=?
    //1=sudah lunas dibayar
    //2=?

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('TransaksiModel');
        $this->model = $this->TransaksiModel;

    }

    //Menampilkan data detailtransaksi yang status 1
    function index_post() {
    
        $status = 1;
        $id_cabang = $this->post('id_cabang');
        $namapembeli = '';
        $multipleWhere = ['nama_pembeli' => '', 'id_cabang' => $id_cabang, 'status' => $status];
        $detailtransaksi = $this->db->select('product.foto_produk, product.kategori_produk, product.nama_produk, detailtransaksi.jumlah_item, detailtransaksi.harga_subtotal')
                                ->from('product')
                                ->where('detailtransaksi.status', $status)
                                ->where('detailtransaksi.id_cabang', $id_cabang)
                                ->where('detailtransaksi.nama_pembeli', $namapembeli)
                                ->join('detailtransaksi', 'detailtransaksi.id_produk = product.id_produk', 'LEFT')
                                ->get()->result();
        $this->response(array("result"=>$detailtransaksi, 200));
                                

    }

    // public function bayar_post(){
    //     $date=date("Y-m-d");

    //     $data = [
    //         'id_transaksi' => $this->input->post('id_transaksi', TRUE),
    //         'id_cabang' => $this->input->post('id_cabang', TRUE),
    //         'nama_pembeli' => $this->input->post('nama_pembeli', TRUE),
    //         'total_bayar' => $this->input->post('total_bayar', TRUE),
    //         'nama_user' => $this->input->post('nama_user', TRUE),
    //         'tanggal'=> $date,
    //         'status' => '1'
    //     ];
    //     $response = $this->TransaksiModel->save_transaksi($data);
        
    //     if($response){
    //         $this->response(array('status' => 'Transaksi sukses'), 200); 

    //     }else{
    //         $this->response(['error'=>true, 'status'=> 'Transaksi gagal'], 401);
    //     }

        
    // }

    public function bayar_post(){
        $date=date("Y-m-d");
        $data = [
            'id_transaksi' => $this->input->post('id_transaksi', TRUE),
            'id_diskon' => '0',
            'id_cabang' => $this->input->post('id_cabang', TRUE),
            'nama_pembeli' => $this->input->post('nama_pembeli', TRUE),
            'total_bayar' => $this->input->post('total_bayar', TRUE),
            'nama_user' => $this->input->post('nama_user', TRUE),
            'tanggal'=> $date,
            'status' => '1'
        ];
        $response = $this->TransaksiModel->save_transaksi($data);
        
        if($response){
            $this->response(array('status' => 'Transaksi sukses'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Transaksi gagal'], 401);
        }

        
    }

    public function diskonbayar_post(){
        $date=date("Y-m-d");
        $data = [
            'id_transaksi' => $this->input->post('id_transaksi', TRUE),
            'id_cabang' => $this->input->post('id_cabang', TRUE),
            'id_diskon' => $this->input->post('id_diskon', TRUE),
            'nama_pembeli' => $this->input->post('nama_pembeli', TRUE),
            'total_bayar' => $this->input->post('total_bayar', TRUE),
            'nama_user' => $this->input->post('nama_user', TRUE),
            'tanggal'=> $date,
            'status' => '1'
        ];
        $response = $this->TransaksiModel->save_transaksi($data);
        
        if($response){
            $this->response(array('status' => 'Transaksi sukses'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Transaksi gagal'], 401);
        }

        
    }

    public function updatecart_post(){
        $id_cabang = $this->post('id_cabang',TRUE);
        $nama_user = $this->post('nama_user',TRUE);
        $nama_pembeli = $this->post('nama_pembeli',TRUE);
        $queryid = $this->db->query("SELECT id_transaksi FROM transaksi WHERE nama_user = '".$nama_user."' AND nama_pembeli = '".$nama_pembeli."' AND id_cabang = '".$id_cabang."' ORDER BY id_transaksi DESC LIMIT 1");
        $row = $queryid->row();
        $id = $row->id_transaksi;
        $status = 0;
        $updatecart = $this->db->set('id_transaksi',$id)
                                ->where('status', $status)
                                ->where('id_cabang', $id_cabang)  
                                ->where('nama_pembeli', $nama_pembeli)
                                ->where('nama_user', $nama_user)
                                ->update('detailtransaksi');
        
    }


}