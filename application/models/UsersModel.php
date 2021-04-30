<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UsersModel extends CI_Model {

    public $table = 'users';
    public $primaryKey = 'id_user';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function save_user($data){
        return $this->db->insert($this->table,$data);
    }

  
}

?>