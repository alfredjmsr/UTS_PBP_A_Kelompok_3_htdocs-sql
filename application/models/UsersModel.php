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
    function update_user($id,$data,$jabatan_user){
        $update = $this->db->where('id_user', $id);
                  $this->db->where('status_user', '1');
                  $this->db->update('users', $data);
        return $update;
    }
    function update_password($id_cabang,$data,$nama_user){
        $update = $this->db->where('id_cabang', $id_cabang);
                  $this->db->where('nama_user', $nama_user);
                  $this->db->where('status_user', '1');
                  $this->db->update('users', $data);
        return $update;
    }

  
}

?>