<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Products extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //Menampilkan data produk
    function index_get() {
        $produk = $this->db->get('product')->result();
        $this->response(array("result"=>$produk, 200));
    }
	
	//Mengirim atau menambah data produk baru
    function index_post() {
		
		$flag=$this->post('flag');
		$date=date("Y-m-d");
		//Insert
		if($flag=="INSERT")
		{	
			//Config Upload
			$config['upload_path'] = './assets/files/image/';
			$config['allowed_types'] = 'png|jpg';
			$config['max_size'] = '20480';
			$foto_produk = $_FILES['foto_produk']['name'];
			$path="./assets/files/image/";
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('foto_produk')) 
			{
				$this->response(array('status' => 'fail', 502));
			} 
			else 
			{
				$data = array(
							'id_produk'=> $this->post('id_produk'),
							'nama_produk' => $this->post('nama_produk'),
							'harga_produk' => $this->post('harga_produk'),
							'biaya_produk' => $this->post('biaya_produk'),
							//'jumlah_produk'=> $this->post('jumlah_produk'),
							//'tanggal_produk'=> $this->post('tanggal_produk'),
							'tanggal_produk'=> $date,
							'kategori_produk'=> $this->post('kategori_produk'),
							'foto_produk'=> $foto_produk);
				$insert = $this->db->insert('product', $data);
				$this->response($data, 200);
			}
		}
		else if($flag=="UPDATE") //Update
		{
			//Config Upload
			$config['upload_path'] = './assets/files/image/';
			$config['allowed_types'] = 'png|jpg';
			$config['max_size'] = '20480';
			$path="./assets/files/image/";
			$foto_produk = $_FILES['foto_produk']['name'];
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('foto_produk')) 
			{
				$this->response(array('status' => 'fail', 502));
			} 
			else 
			{
				$id = $this->post('id_produk');
				
				//Hapus Image Lama
				$queryimg = $this->db->query("SELECT foto_produk FROM `".$this->db->dbprefix('product')."` WHERE id_produk='".$id."'");
				$row = $queryimg->row();
				$picturepath="./assets/files/image/".$row->foto_produk;	
				unlink($picturepath);
				
				$data = array(
					'id_produk'=> $this->post('id_produk'),
					'nama_produk' => $this->post('nama_produk'),
					'harga_produk'=> $this->post('harga_produk'),
					'biaya_produk' => $this->post('biaya_produk'),
					'tanggal_produk'=> $date,
					'kategori_produk'=>$this->post('kategori_produk'),
					'foto_produk'=> $foto_produk);
				$this->db->where('id_produk', $id);
				$update = $this->db->update('product', $data);
				$this->response($data, 200);	
			}
		}	
    }
	
	//Menghapus salah satu data produk
    function index_delete() {
        $id = $this->delete('id_produk');
		
		//Hapus Image Lama
		$queryimg = $this->db->query("SELECT foto_produk FROM `".$this->db->dbprefix('product')."` WHERE id_produk='".$id."'");
		$row = $queryimg->row();
		$picturepath="./assets/files/image/".$row->foto_produk;	
		unlink($picturepath);
		
        $this->db->where('id_produk', $id);
        $delete = $this->db->delete('product');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

}
?>