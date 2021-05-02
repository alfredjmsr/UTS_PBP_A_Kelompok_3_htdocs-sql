<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DetailTransaksiModel extends CI_Model {

    public $table = 'detailtransaksi';
    public $primaryKey = 'id_detailtransaksi';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function save_detaildtransaksi($data){
        return $this->db->insert($this->table,$data);
    }

  
}

?>