<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DiskonModel extends CI_Model {

    public $table = 'diskon';
    public $primaryKey = 'id_diskon';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function save_diskon($data){
        return $this->db->insert($this->table,$data);
    }
    function update_diskon($id,$data){
        $update = $this->db->where('id_diskon', $id);
        $this->db->update('diskon', $data);
        return $update;
    }

}

?>