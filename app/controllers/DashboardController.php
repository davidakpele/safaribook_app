<?php

use Session\AuthSession;

final class DashboardController extends Controller
{
    private $repository;
    private $session;
    private $jwt;

    public function __construct() {
        $this->session= new AuthSession();
        $this->repository = $this->loadModel('User');
    }

    public function index(){
        if ($this->session->authCheck()) {
            $data = [
                'page_title'=> 'Dashboard',
                'settings'=>[
                    'company_name'=> 'SAFARI BOOKS LIMITED'
                ],
            ];
            $this->view("dashboard/index", $data);
        }else{
            redirect("auth/logout");
        }
    }

    public function ceo(){
        $this->view("index"); 
    } 

    public function manager(){
        $this->view("index"); 
    }

    public function accountant(){
        $this->view("index"); 
    } 
    
    public function business_dev(){
        if ($this->session->authCheck()) {
            $data = [
                'page_title'=> 'Dashboard',
                'settings'=>[
                    'company_name'=> 'SAFARI BOOKS LIMITED'
                ],
            ];
            $this->view("dashboard/index", $data);
        }else{
            redirect("auth/logout");
        }
    }

    public function create_quotation(){
        if ($this->session->authCheck()) {
            $data = [
                'page_title'=> 'Quotation',
                'settings'=>[
                    'company_name'=> 'SAFARI BOOKS LIMITED'
                ],
            ];
            $this->view("dashboard/index", $data);
        }else{
            redirect("auth/logout");
        }
    }
    
    public function create_invoice(){
        if ($this->session->authCheck()) {
            $data = [
                'page_title'=> 'Manage Quotation',
                'settings'=>[
                    'company_name'=> 'SAFARI BOOKS LIMITED'
                ],
            ];
            $this->view("dashboard/quotation", $data);
        }else{
            redirect("auth/logout");
        }
    }  


    public function stock(){
        if ($this->session->authCheck()) {
            $data = [
                'page_title'=> 'Manage Stocks',
                'settings'=>[
                    'company_name'=> 'SAFARI BOOKS LIMITED'
                ],
            ];
            $this->view("dashboard/stock", $data);
        }else{
            redirect("auth/logout");
        }
    }

    public function create_product(){

        if ($this->session->authCheck()) {
            $action = isset($_GET['action']) ? $_GET['action'] :null;
            if($action == null || $action ==""){
                $response['message']= 'Sorry..! Provide action base parameter.';
                http_response_code(401);
                header("Content-Type: application/json");
                echo json_encode($response, JSON_PRETTY_PRINT);
            }else{

                $data = [
                    'page_title'=>  $action =='edit_stock' ? 'Edit Book' : 'Create New Book',
                    'settings'=>[
                        'company_name'=> 'SAFARI BOOKS LIMITED'
                    ],
                ];
                $this->view("dashboard/create_form", $data);
            }
           
        }else{
            redirect("auth/logout");
        }
    }


    

    
}
