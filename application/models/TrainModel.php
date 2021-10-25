<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TrainModel extends CI_Model {

    public $table = 'trains';
    public $primaryKey = 'id_train';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    function addtrains($data){
        return $this->db->insert($this->table,$data);
    }
    

  
}

?>