<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Trains extends REST_Controller {

    //1=sudah dikonfirmasi admin
    //2=masih belum dikonfirmasi
    //3=sudah tidak aktif


    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('TrainModel');
        $this->model = $this->TrainModel;
        $this->load->model('OrderModel');
        $this->model = $this->OrderModel;
    }

    function index_get() {
        $trains = $this->db->get('trains')->result();
        $this->response(array("result"=>$trains, 200));
    }
    public function addtrains_post(){
        $data = [
            'id_train' => $this->input->post('id_train', TRUE),
            'from_train' => $this->input->post('from_train', TRUE),
            'to_train' => $this->input->post('to_train', TRUE),
            'fromtime_train' => $this->input->post('fromtime_train', TRUE),
            'totime_train' => $this->input->post('totime_train', TRUE),
            'price_train'=> $this->input->post('price_train', TRUE),
            'class_train'=> $this->input->post('class_train', TRUE),
            'name_train'=> $this->input->post('name_train', TRUE),
        ];
        $response = $this->TrainModel->addtrains($data);
        
        if($response){
            $this->response(array('status' => 'Tiket sukses ditambah'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Ticket gagal ditambah'], 401);
        }

    }
    public function buyTicket_post(){
        $data = [
            'id_order' => $this->input->post('id_order', TRUE),
            'firstname_order' => $this->input->post('firstname_order', TRUE),
            'lastname_order' => $this->input->post('lastname_order', TRUE),
            'email_order' => $this->input->post('email_order', TRUE),
            'phonenumber_order' => $this->input->post('phonenumber_order', TRUE),
            'fromflight_order'=> $this->input->post('fromflight_order', TRUE),
            'toflight_order'=> $this->input->post('toflight_order', TRUE),
            'fromflighttime_order'=> $this->input->post('fromflighttime_order', TRUE),
            'toflighttime_order'=> $this->input->post('toflighttime_order', TRUE),
            'nameflight_order'=> $this->input->post('nameflight_order', TRUE),
            'username_order' => $this->input->post('username_order', TRUE),
            'price_order' => $this->input->post('price_order', TRUE),
        ];
        $response = $this->OrderModel->buyTicket($data);
        
        if($response){
            $this->response(array('status' => 'Ticket sukses dibeli'), 200);  
        }else{
            $this->response(['error'=>true, 'status'=> 'Ticket gagal dibeli'], 401);
        }

    }

  

}

?>