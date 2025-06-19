<?php

use Session\AuthSession;
use Exception\RequestException;
use Request\RequestHandler;

final class DashboardController extends Controller
{
    private $repository;
    private $session;
    private $jwt;

    public function __construct() {
        $this->session= new AuthSession();
        $this->repository = $this->loadModel('User');
        $this->data_repository = $this->loadModel('DataRepository');
        $this->entryHeaderHandle =new RequestException();
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
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('GET')) {
                    $action = isset($_GET['action']) ? $_GET['action'] :null;
                    if($action == null || $action ==""){
                        $response['message']= 'Sorry..! Provide action base parameter.';
                        http_response_code(401);
                        header("Content-Type: application/json");
                        echo json_encode($response, JSON_PRETTY_PRINT);
                    }else{
                        if($action =='edit_stock'){
                            $id = strip_tags(trim(filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT)));
                            $product_edit =  $this->data_repository->edit_product($id);
                        }
                        $data = [
                            'page_title' => $action == 'edit_stock' ? 'Edit Book' : 'Create New Book',
                            'settings' => [
                                'company_name' => 'SAFARI BOOKS LIMITED'
                            ]
                        ] + ($action == 'edit_stock' ? ['edit_product' => $product_edit] : []);
                        
                        $this->view("dashboard/create_form", $data);
                    }
                }else{
                    $this->entryHeaderHandle->sendErrorResponse("Method Not Allowed", 405);
                }
            }else{
                $this->entryHeaderHandle->sendErrorResponse("Cors misconfigured.", 400);
            }
        }else{
            $this->entryHeaderHandle->sendErrorResponse("Access denied", 401);
        }
    }

    
}
