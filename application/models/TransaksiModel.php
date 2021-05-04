<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TransaksiModel extends CI_Model {

    public $table = 'transaksi';
    public $primaryKey = 'id_transaksi';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function save_transaksi($data){
        return $this->db->insert($this->table,$data);
    }

  
}

?>