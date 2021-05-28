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
            $inventori = $this->db->select('inventori.id_inventori,.inventori.nama_bahanbaku,SUM(detailinventori.jumlah_bahanbaku) as total_bahanbaku')
                                    ->from('inventori')
                                    ->where('inventori.status', $status)
                                    ->where('inventori.id_cabang', $id_cabang)
                                    ->join('detailinventori', 'detailinventori.id_inventori = inventori.id_inventori', 'LEFT')
                                    ->group_by('inventori.id_inventori')
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
        $id_cabang = $this->input->post('id_cabang', TRUE);
        $id_inventori = $this->input->post('id_inventori', TRUE);
        $status = '1';
        $jumlahbahanbaku1 = $this->input->post('jumlah_bahanbaku', TRUE);
        $hargabahanbaku1 = $this->input->post('harga_bahanbaku', TRUE);
        $nama_bahanbaku = $this->input->post('nama_bahanbaku', TRUE);
        $query = $this->db->query("SELECT * FROM `".$this->db->dbprefix('detailinventori')."` WHERE id_cabang='".$id_cabang."' AND id_inventori='".$id_inventori."' AND 
                                nama_bahanbaku='".$nama_bahanbaku."' AND status = '".$status."' AND tanggal_masuk = '".$date."'");
        if ($query->num_rows() > 0 )
        {  
            $row=$query->row();
            $iddetailinventori=$row->id_detailinventori;
            $idcabang=$row->id_cabang;
            $idinventori=$row->id_inventori;
			$jumlahbahanbaku=$row->jumlah_bahanbaku;
            $hargabahanbaku=$row->harga_bahanbaku;
            $tanggalmasuk=$row->tanggal_masuk;
            $data = [
                'jumlah_bahanbaku' => $this->input->post('jumlah_bahanbaku', TRUE),
                'harga_bahanbaku' => $this->input->post('harga_bahanbaku', TRUE),
                //'exp_bahanbaku' => $this->input->post('exp_bahanbaku', TRUE),
            ];    
           // $response = $this->InventoriModel->tambahplus_bahanbaku($idcabang,$idinventori,$nama_bahanbaku,$tanggalmasuk,$data);
            $response = $this->db->query("UPDATE detailinventori SET jumlah_bahanbaku = jumlah_bahanbaku + $jumlahbahanbaku1, harga_bahanbaku = harga_bahanbaku + $hargabahanbaku1 WHERE id_detailinventori = $iddetailinventori AND id_cabang = $idcabang");
            if($response){
                $this->response(array('status' => 'Bahan baku sukses ditambah'), 200);  
            }else{
                $this->response(['error'=>true, 'status'=> 'Bahan baku gagal ditambah'], 401);
            }
        }else{
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
        

    }

    public function getbahanbaku_post(){
        $date=date("Y-m-d");
        $id_inventori = $this->post('id_inventori');
        $id_cabang = $this->post('id_cabang');
        $nama_bahanbaku = $this->post('nama_bahanbaku');
        $bahanbaku = $this->db->select('id_detailinventori,sum(jumlah_bahanbaku) as jumlah_bahanbaku')
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

    public function getbahanbakustok_post(){
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

    public function fifobahanbaku_post(){
        $date=date("Y-m-d");
        $id_cabang = $this->input->post('id_cabang', TRUE);
        $id_inventori = $this->input->post('id_inventori', TRUE);
        $nama_bahanbaku = $this->input->post('nama_bahanbaku', TRUE);
        $jumlah_bahanbaku = $this->input->post('jumlah_bahanbaku', TRUE);
        $id_detailinventori = $this->input->post('id_detailinventori', TRUE);

        // $que=$this->db->query("select password_user,email_user from users where nama_user='$nama_user' and id_cabang ='$id_cabang'");
		// 	$row=$que->row();
		// 	$user_email=$row->email_user;
        //     $password_user=$row->password_user;

       // Jumlahkan keseluruhan Stok barang yg terpilih
        $totalku=$this->db->query("SELECT SUM(jumlah_bahanbaku) AS total FROM detailinventori WHERE nama_bahanbaku = '$nama_bahanbaku' AND id_inventori = '$id_inventori' AND id_cabang = '$id_cabang' AND exp_bahanbaku >='$date'");
        $row=$totalku->row();
        $stok_all = $row->total;


        $sql = $this->db->query("SELECT * FROM detailinventori WHERE nama_bahanbaku = '$nama_bahanbaku' AND id_inventori = '$id_inventori' AND id_cabang = '$id_cabang' AND jumlah_bahanbaku > 0 AND exp_bahanbaku >='$date' ORDER by tanggal_masuk ASC");
    
        // ayo mulai mikir , siapkan logikanya yaaa....

        // bandingkan dulu boss qty yg dibeli dg stok brg digudang ...
        if($jumlah_bahanbaku <= $stok_all) {

            foreach($sql->result() as $row) {
                
                $tgl  = $row->tanggal_masuk;
                $stok = $row->jumlah_bahanbaku;
                $idinventori = $row->id_inventori;
                if($jumlah_bahanbaku > 0) {   
                    // buat var $temp sbg. pengurang
                    $temp = $jumlah_bahanbaku;
                    //proses pengurangan
                    $jumlah_bahanbaku = $jumlah_bahanbaku - $stok;
                    if($jumlah_bahanbaku > 0 || $jumlah_bahanbaku = 0) {  
                       // $status = '0';    
                        $stok_update = 0;
                    }else{
                       // $status = '1'; 
                        $stok_update = $stok - $temp;
                    }
                    $data = [
                        'tanggal_keluar' => $date,
                        'jumlah_bahanbaku' => $stok_update
                    ];

                    //$sqlk = $this->db->query("UPDATE detailinventori SET jumlah_bahanbaku = $stok_update AND tanggal_keluar =$date AND status = $status WHERE nama_bahanbaku = '$nama_bahanbaku' AND tanggal_masuk = '$tgl' AND id_inventori = '$idinventori'");
                    $response = $this->InventoriModel->keluarkan_bahanbaku($nama_bahanbaku,$idinventori,$tgl,$data);
                    if($response){
                        $this->response(array('status' => 'bahanbaku sukses diupdate'), 200);  
                    }else{
                        $this->response(['error'=>true, 'status'=> 'bahanbaku gagal diupdate'], 401);
                    }
                }
            }
        }else{
            $this->response(['error'=>true, 'status'=> 'bahanbaku kurang'], 401);
        }   
                
    }  

    function deleteinventori_post(){
        $status = 1;
        $id_inventori = $this->post('id_inventori',TRUE);
        $id_cabang = $this->post('id_cabang',TRUE);
        $deleteinventori = $this->db->set('status','0')
                                ->where('id_inventori', $id_inventori)
                                ->where('id_cabang', $id_cabang)
                                ->where('status', $status)
                                ->update('inventori');
        if($deleteinventori){
            $this->response(array('status' => 'Inventori sukses dihapus'), 200);
        }else{
            $this->response(['error'=>true, 'status'=> 'Inventori gagal didelete'], 401);
        }
    
        
    }


   
}

?>