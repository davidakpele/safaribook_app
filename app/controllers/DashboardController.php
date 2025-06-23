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
            $records =$this->data_repository->get_record();
            $data = [
                'page_title'=> 'Dashboard',
                'records'=> $records,
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
                'page_title'=> 'Create Client Invoice',
                'settings'=>[
                    'company_name'=> 'SAFARI BOOKS LIMITED'
                ],
            ];
            $this->view("dashboard/invoice", $data);
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

    public function manage_invoice(){
        if ($this->session->authCheck()) {
            $data = [
                'page_title'=> 'Manage Invoice',
                'settings'=>[
                    'company_name'=> 'SAFARI BOOKS LIMITED'
                ],
            ];
            $this->view("dashboard/manage_invoice", $data);
        }else{
            redirect("auth/logout");
        }
    }

    public function edit_invoice(){
        if ($this->session->authCheck()) {
            $data = [
                'page_title'=> 'Edit Invoice',
                'settings'=>[
                    'company_name'=> 'SAFARI BOOKS LIMITED'
                ],
            ];
            $this->view("dashboard/edit_invoice", $data);
        }else{
            redirect("auth/logout");
        }
    }

    public function edituser($url){
        if ($this->session->authCheck()) {
            if (RequestHandler::isRequestMethod('GET')) {
                $urlParts = explode('/', $url); 
                $controller = !empty($urlParts[0])? $urlParts[0] : '';
                $controllerName = $controller;
                $id= trim(filter_var((int)$controllerName, FILTER_SANITIZE_NUMBER_INT));
                if (!is_numeric($id)) {
                    $this->entryHeaderHandle->sendErrorResponse("Id must be interger", 401);
                }
                $user  = $this->data_repository->getUserById($id);
                if ($user ==null || !$user) {
                    $this->entryHeaderHandle->sendErrorResponse("User not found", 404);
                }
                $roles  = $this->data_repository->getUserRoles();
                $data = [
                    'page_title'=> 'Edit User',
                    'editdata'=>$user,
                    'rolelist'=>$roles,
                    'id'=>$controllerName,
                    'settings'=>[
                        'company_name'=> 'SAFARI BOOKS LIMITED'
                    ],
                ];
                $this->view("dashboard/user/edit", $data);
            }else{
                $this->entryHeaderHandle->sendErrorResponse("Method Not Allowed", 405);
            }
        }else{
            redirect("auth/logout");
        }
    }

    public function settings(){
        if ($this->session->authCheck()) {
            $records =$this->data_repository->get_record();
            $data = [
                'page_title'=> 'Dashboard',
                'records'=> $records,
                'settings'=>[
                    'company_name'=> 'SAFARI BOOKS LIMITED'
                ],
            ];
            $this->view("dashboard/settings", $data);
        }else{
            redirect("auth/logout");
        }
    }
    
}
