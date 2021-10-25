<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class FlightModel extends CI_Model {

    public $table = 'flights';
    public $primaryKey = 'id_flight';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    function addFlights($data){
        return $this->db->insert($this->table,$data);
    }
    

  
}

?>