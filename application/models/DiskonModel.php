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

}

?>