<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CabangModel extends CI_Model {

    public $table = 'cabang';
    public $primaryKey = 'id_cabang';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function save_cabang($data){
        return $this->db->insert($this->table,$data);
    }
    function update_cabang($id,$data){
        $update = $this->db->where('id_cabang', $id);
        $this->db->update('cabang', $data);
        return $update;
    }

    function owner_cabang($data){
        return $this->db->insert('users', $data);
    }

}

?>