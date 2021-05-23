<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class InventoriModel extends CI_Model {

    public $table = 'inventori';
    public $primaryKey = 'id_inventori';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function save_inventori($data){
        return $this->db->insert($this->table,$data);
    }
    function save_bahanbaku($data){
        return $this->db->insert('detailinventori',$data);
    }
    function update_inventori($id,$data){
        $update = $this->db->where('id_inventori', $id);
        $this->db->update('inventori', $data);
        return $update;
    }

    function update_bahanbaku($id_cabang,$id_detailinventori,$data){
        $update = $this->db->where('id_detailinventori', $id_detailinventori);
                  $this->db->where('id_cabang', $id_cabang);
                  $this->db->update('detailinventori', $data);
        return $update;
    }

    function keluarkan_bahanbaku($nama_bahanbaku,$idinventori,$tgl,$data){
        $update = $this->db->where('nama_bahanbaku', $nama_bahanbaku);
                  $this->db->where('id_inventori', $idinventori);
                  $this->db->where('tanggal_masuk', $tgl);
                  $this->db->update('detailinventori', $data);
        return $update;
    }

    function tambahplus_bahanbaku($idcabang,$idinventori,$nama_bahanbaku,$tanggalmasuk,$data){
        $update = $this->db->where('id_cabang', $idcabang);
                  $this->db->where('id_inventori', $idinventori);
                  $this->db->where('nama_bahanbaku', $nama_bahanbaku);
                  $this->db->where('tanggal_masuk', $tanggalmasuk);
                  $this->db->update('detailinventori', $data);
        return $update;
    }


}

?>