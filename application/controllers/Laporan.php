<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Laporan extends REST_Controller {

    //1=masih aktif
    //2=tidak aktif
    //3=sudah tidak aktif


    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    
    }

    function riwayattransaksibycabang_post() {
        $id_cabang = $this->post('id_cabang',TRUE);
        $laporan = $this->db->get_where('transaksi',['id_cabang '=> $id_cabang])->result();
        $this->response(array("result"=>$laporan, 200));
       
    }

    function pendapatantahunanbycabang_post() {
       //pendapatantahunan
       
    }
    function gettransaksibulan_post() {
        $vtanggal=$this->input->post('tanggal');
        $vbulan=date("m",strtotime($vtanggal));
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->input->post('id_cabang');
        $laporan = $this->db->SELECT('SUM(total_bayar) as total_transaksi, COUNT(id_transaksi) as jumlah_transaksi')
                            ->FROM('transaksi')
                            ->WHERE('id_cabang', $id_cabang)
                            ->WHERE('status', '1')
                            ->WHERE('month(tanggal)', $vbulan)
                            ->WHERE('year(tanggal)', $vtahun)
                            ->get()->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
 
    }

    function gettransaksidiskonbulan_post() {
        $vtanggal=$this->input->post('tanggal');
        $vbulan=date("m",strtotime($vtanggal));
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->input->post('id_cabang');
        $laporan = $this->db->SELECT('COUNT(id_transaksi) as jumlah_transaksidiskon')
                            ->FROM('transaksi')
                            ->WHERE('id_cabang', $id_cabang)
                            ->WHERE('status', '1')
                            ->WHERE('id_diskon >', '0')
                            ->WHERE('month(tanggal)', $vbulan)
                            ->WHERE('year(tanggal)', $vtahun)
                            ->get()->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
 
    }

    function gettransaksirefundbulan_post() {
        $vtanggal=$this->input->post('tanggal');
        $vbulan=date("m",strtotime($vtanggal));
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->input->post('id_cabang');
        $laporan = $this->db->SELECT('SUM(total_bayar) as total_refund, COUNT(id_transaksi) as jumlah_transaksirefund')
                            ->FROM('transaksi')
                            ->WHERE('id_cabang', $id_cabang)
                            ->WHERE('status', '2')
                            ->WHERE('month(tanggal)', $vbulan)
                            ->WHERE('year(tanggal)', $vtahun)
                            ->get()->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
 
    }

    
    function gettransaksihbpbulan_post() {
        //total_transaksi tidak digunakan
        $vtanggal=$this->input->post('tanggal');
        $vbulan=date("m",strtotime($vtanggal));
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->input->post('id_cabang');
        $laporan = $this->db->SELECT('SUM(transaksi.total_bayar) as total_transaksi, SUM(detailtransaksi.jumlah_item * product.biaya_produk) as total_biayaproduk')
                            ->FROM('transaksi')
                            ->WHERE('transaksi.id_cabang', $id_cabang)
                            ->WHERE('transaksi.status', '2')
                            ->WHERE('month(transaksi.tanggal)', $vbulan)
                            ->WHERE('year(transaksi.tanggal)', $vtahun)
                            ->join('detailtransaksi', 'detailtransaksi.id_transaksi = transaksi.id_transaksi', 'LEFT')
                            ->join('product', 'product.id_produk = detailtransaksi.id_produk', 'LEFT')
                            ->get()->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
 
    }
    function trxbulanan_post() {
        $vtanggal=$this->input->post('tanggal');
        $vbulan=date("m",strtotime($vtanggal));
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->post('id_cabang',TRUE);
        $status = $this->post('status',TRUE);
        $data = [
            'id_cabang '=> $id_cabang,
            //'id_diskon'=>'0',
            'status'=>'1',
            'month(tanggal)'=>$vbulan,
            'year(tanggal)'=>$vtahun,
        ];
        $laporan = $this->db->get_where('transaksi',$data)->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
       
    }
    function trxbulananwithdiskon_post() {
        $vtanggal=$this->input->post('tanggal');
        $vbulan=date("m",strtotime($vtanggal));
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->post('id_cabang',TRUE);
        $status = $this->post('status',TRUE);
        $data = [
            'id_cabang '=> $id_cabang,
            'id_diskon >'=>'0',
            'status'=>1,
            'month(tanggal)'=>$vbulan,
            'year(tanggal)'=>$vtahun,
        ];
        $laporan = $this->db->get_where('transaksi',$data)->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
       
    }
    function trxbulananwithrefund_post() {
        $vtanggal=$this->input->post('tanggal');
        $vbulan=date("m",strtotime($vtanggal));
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->post('id_cabang',TRUE);
        $status = $this->post('status',TRUE);
        $data = [
            'id_cabang '=> $id_cabang,
            'status'=>'2',
            'month(tanggal)'=>$vbulan,
            'year(tanggal)'=>$vtahun,
        ];
        $laporan = $this->db->get_where('transaksi',$data)->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
       
    }


    /////////////////////////////////////Tahun//////////////////////////////////////////
    function gettransaksitahun_post() {
        $vtanggal=$this->input->post('tanggal');
        $vbulan=date("m",strtotime($vtanggal));
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->input->post('id_cabang');
        $laporan = $this->db->SELECT('SUM(total_bayar) as total_transaksi, COUNT(id_transaksi) as jumlah_transaksi')
                            ->FROM('transaksi')
                            ->WHERE('id_cabang', $id_cabang)
                            ->WHERE('status', '1')
                            ->WHERE('year(tanggal)', $vtahun)
                            ->get()->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
 
    }

    function gettransaksidiskontahun_post() {
        $vtanggal=$this->input->post('tanggal');
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->input->post('id_cabang');
        $laporan = $this->db->SELECT('COUNT(id_transaksi) as jumlah_transaksidiskon')
                            ->FROM('transaksi')
                            ->WHERE('id_cabang', $id_cabang)
                            ->WHERE('status', '1')
                            ->WHERE('id_diskon >', '0')
                            ->WHERE('year(tanggal)', $vtahun)
                            ->get()->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
 
    }

    function gettransaksirefundtahun_post() {
        $vtanggal=$this->input->post('tanggal');
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->input->post('id_cabang');
        $laporan = $this->db->SELECT('SUM(total_bayar) as total_refund, COUNT(id_transaksi) as jumlah_transaksirefund')
                            ->FROM('transaksi')
                            ->WHERE('id_cabang', $id_cabang)
                            ->WHERE('status', '2')
                            ->WHERE('year(tanggal)', $vtahun)
                            ->get()->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
 
    }

    
    function gettransaksihbptahun_post() {
        //total_transaksi tidak digunakan
        $vtanggal=$this->input->post('tanggal');
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->input->post('id_cabang');
        $laporan = $this->db->SELECT('SUM(transaksi.total_bayar) as total_transaksi, SUM(detailtransaksi.jumlah_item * product.biaya_produk) as total_biayaproduk')
                            ->FROM('transaksi')
                            ->WHERE('transaksi.id_cabang', $id_cabang)
                            ->WHERE('transaksi.status', '2')
                            ->WHERE('year(transaksi.tanggal)', $vtahun)
                            ->join('detailtransaksi', 'detailtransaksi.id_transaksi = transaksi.id_transaksi', 'LEFT')
                            ->join('product', 'product.id_produk = detailtransaksi.id_produk', 'LEFT')
                            ->get()->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
 
    }

    function gettransaksiperbulan_post() {
        //total_transaksi tidak digunakan
        $vtanggal=$this->input->post('tanggal');
        $vtahun=date("Y",strtotime($vtanggal));
        $id_cabang = $this->input->post('id_cabang');
        $laporan = $this->db->SELECT('month(transaksi.tanggal) as bulan, SUM(transaksi.total_bayar) as total_transaksi, SUM(detailtransaksi.jumlah_item * product.biaya_produk) as total_biayaproduk')
                            ->FROM('transaksi')
                            ->WHERE('transaksi.id_cabang', $id_cabang)
                            ->WHERE('transaksi.status', '2')
                            ->WHERE('year(transaksi.tanggal)', $vtahun)
                            ->join('detailtransaksi', 'detailtransaksi.id_transaksi = transaksi.id_transaksi', 'LEFT')
                            ->join('product', 'product.id_produk = detailtransaksi.id_produk', 'LEFT')
                            ->group_by('bulan')
                            ->order_by('bulan')
                            ->get()->result();
        if($laporan){
            $this->response(array("result"=>$laporan, 200));
        }else{
            $this->response(['error'=>true, 'status'=> 'Gagal'], 401);
        }
 
    }



}

?>