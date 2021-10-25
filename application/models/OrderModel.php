<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OrderModel extends CI_Model {

    public $table = 'orders';
    public $primaryKey = 'id_order';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    function buyTicket($data){
        return $this->db->insert($this->table,$data);
    }

  
}

?>