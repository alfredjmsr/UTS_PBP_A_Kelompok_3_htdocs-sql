<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Orders extends REST_Controller {

    //1=sudah dikonfirmasi admin
    //2=masih belum dikonfirmasi
    //3=sudah tidak aktif


    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('OrderModel');
        $this->model = $this->OrderModel;
    }

    function showOrder_post() {
        $username_order = $this->post('username_order');
        $order = $this->db->get_where('orders',['username_order'=>$username_order])->result();
        $this->response(array("result"=>$order, 200));
    }


  

}

?>