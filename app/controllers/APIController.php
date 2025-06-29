<?php

use Session\AuthSession;
use App\Services\PdfDocumentSender;
use App\Services\DocxDocumentSender;
use Exception\RequestException;
use Request\RequestHandler;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

final class APIController extends Controller
{
    private $repository;
    private $session;
    private $jwt;

    public function __construct() {
        $this->session= new AuthSession();
        $this->repository = $this->loadModel('DataRepository');
        $this->entryHeaderHandle =new RequestException();
    }

    public function payment_details(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('GET')) {
                    $response=  $this->repository->payment_details();
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    http_response_code(200);
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

    public function users(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('GET')) {
                    $response=  $this->repository->findAll();
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    http_response_code(200);
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
   
    public function invoice_number(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('GET')) {
                    $last_record = $this->repository->getInvoiceLastNumber();
                    if ($last_record === false) {
                        $next_number = '0001';
                    } else {
                        $stored_number = $last_record->invoice_number;
                        if (strpos($stored_number, 'NGSB-') === 0) {
                            $numeric_part = substr($stored_number, ); 
                        } else {
                            $numeric_part = $stored_number; 
                        }
                        $next_numeric = (int)$numeric_part + 1;
                        $next_number = str_pad($next_numeric, 6, '0', STR_PAD_LEFT);
                    }
                    
                    $response = "NGSB-" . $next_number;
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    http_response_code(200);
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

    public function books(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('GET')) {
                    $response=  $this->repository->getAllProducts();
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    http_response_code(200);
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
    
    public function delete_stock(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('DELETE')) {
                    $jsonString = file_get_contents("php://input");
                    $response = array();
                    $phpObject = json_decode($jsonString);
                    $id=$phpObject->{'ids'};
                    if($this->repository->delete_product($id)){
                        $response['message']= 'Successfully deleted.';
                        http_response_code(200);
                    }else {
                        $response['message']= 'Sorry..! Something Happen At The Database Process.';
                        http_response_code(401);
                    };
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    
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

    public function add_product(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('POST')) {
                    $jsonString = file_get_contents("php://input");
                    $response = array();
                    $phpObject = json_decode($jsonString);
                    $product_title=$phpObject->{'product_title'};
                    $product_binding=$phpObject->{'product_binding'};
                    $product_price=$phpObject->{'product_price'};
                    if($this->repository->add_product($product_title, $product_binding, $product_price)){
                        $response['message']= 'Successfully Created.';
                        http_response_code(201);
                    }else {
                        $response['message']= 'Sorry..! Something Happen At The Database Process.';
                        http_response_code(401);
                    };
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    
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
    
    public function edit_product($url){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('PUT')) {
                    $urlParts = explode('/', $url); 
                    $controller = !empty($urlParts[0])? $urlParts[0] : '';
                    $controllerName = $controller;
                    $id= trim(filter_var((int)$controllerName, FILTER_SANITIZE_NUMBER_INT)); 

                    $jsonString = file_get_contents("php://input", true);
                    $response = array();
                    $phpObject = json_decode($jsonString);
                    $product_title=$phpObject->{'product_title'};
                    $product_binding=$phpObject->{'product_binding'};
                    $product_price=$phpObject->{'product_price'};
                    if($this->repository->update_product($id, $product_title, $product_binding, $product_price)){
                        $response['message']= 'Product Successfully Updated.';
                        http_response_code(201);
                    }else {
                        $response['message']= 'Sorry..! Something Happen At The Database Process.';
                        http_response_code(401);
                    };
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
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
    
    public function save_invoice() {
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                
                if (RequestHandler::isRequestMethod('POST')) {
                    $jsonString = file_get_contents("php://input");
                    $response = ['status' => 'error', 'message' => ''];
                    $invoiceData = json_decode($jsonString, true);
                    
                    // Validate the received data
                    if (empty($invoiceData) || !isset($invoiceData['invoice']) || !isset($invoiceData['sections'])) {
                        $response['message'] = 'Invalid invoice data structure';
                        http_response_code(400);
                        echo json_encode($response, JSON_PRETTY_PRINT);
                        return;
                    }
                    
                    // Process the complete invoice through repository
                    $result = $this->repository->processCompleteInvoice($invoiceData);
                    
                    if ($result['status'] === 'success') {
                        $response = [
                            'status' => 'success',
                            'message' => 'Invoice successfully created',
                            'invoice_id' => $result['invoice_id']
                        ];
                        http_response_code(201);
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => $result['message'],
                            'details' => $result['details'] ?? null
                        ];
                        http_response_code(500);
                    }
                    
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    
                } else {
                    $this->entryHeaderHandle->sendErrorResponse("Method Not Allowed", 405);
                }
            } else {
                $this->entryHeaderHandle->sendErrorResponse("Cors misconfigured.", 400);
            }
        } else {
            $this->entryHeaderHandle->sendErrorResponse("Access denied", 401);
        }
    }

    public function invoice_list(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('GET')) {
                    $response=  $this->repository->getAllInvoice();
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    http_response_code(200);
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

    public function delete_invoice(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('DELETE')) {
                    $jsonString = file_get_contents("php://input");
                    $response = array();
                    $phpObject = json_decode($jsonString);
                    $id=$phpObject->{'ids'};
                    if($this->repository->delete_invoice($id)){
                        $response['message']= 'Successfully deleted.';
                        http_response_code(200);
                    }else {
                        $response['message']= 'Sorry..! Something Happen At The Database Process.';
                        http_response_code(401);
                    };
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    
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

    public function invoice($url){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('GET')) {
                    $urlParts = explode('/', $url); 
                    $controller = !empty($urlParts[0])? $urlParts[0] : '';
                    $controllerName = $controller;
                    $id= trim(filter_var((int)$controllerName, FILTER_SANITIZE_NUMBER_INT)); 

                    $jsonString = file_get_contents("php://input");
                    $response = array();
                    $phpObject = json_decode($jsonString);
                    $response  = $this->repository->getInvoiceById($id);
                    http_response_code(200);
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
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

    public function records(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('GET')) {
                    $response=  $this->repository->findCountRecords();
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    http_response_code(200);
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

    public function load_app_settings(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('GET')) {
                    $response=  $this->repository->get_app_settings();
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    http_response_code(200);
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

    public function update_invoice($url) {
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('PUT')) {
                    $urlParts = explode('/', $url); 
                    $controller = !empty($urlParts[0])? $urlParts[0] : '';
                    $controllerName = $controller;
                    $id= trim(filter_var((int)$controllerName, FILTER_SANITIZE_NUMBER_INT)); 
                    if(empty($controllerName) || $controllerName ==null){
                        $response['message'] = 'Invoice ID is require.';
                        http_response_code(400);
                        echo json_encode($response, JSON_PRETTY_PRINT);
                        return;
                    }

                    $jsonString = file_get_contents("php://input");
                    $response = ['status' => 'error', 'message' => ''];
                    $invoiceData = json_decode($jsonString, true);
                    
                    // Validate the received data
                    if (empty($invoiceData) || !isset($invoiceData['invoice']) || !isset($invoiceData['sections'])) {
                        $response['message'] = 'Invalid invoice data structure';
                        http_response_code(400);
                        echo json_encode($response, JSON_PRETTY_PRINT);
                        return;
                    }
                    
                    // Process the complete invoice through repository
                    $result = $this->repository->processUpdateInvoice($invoiceData, $id);
                    
                    if ($result['status'] === 'success') {
                        $response = [
                            'status' => 'success',
                            'message' => 'Invoice successfully updated.',
                        ];
                        http_response_code(200);
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => $result['message'],
                            'details' => $result['details'] ?? null
                        ];
                        http_response_code(500);
                    }
                    
                    header("Content-Type: application/json");
                    echo json_encode($response, JSON_PRETTY_PRINT);
                    
                } else {
                    $this->entryHeaderHandle->sendErrorResponse("Method Not Allowed", 405);
                }
            } else {
                $this->entryHeaderHandle->sendErrorResponse("Cors misconfigured.", 400);
            }
        } else {
            $this->entryHeaderHandle->sendErrorResponse("Access denied", 401);
        }
    }
    
    public function send_document() {
        $data = json_decode(file_get_contents('php://input'), true);
        $settings =  $this->repository->get_app_settings();

        if (isset($data['pdf'])) {
            $sender = new PdfDocumentSender($this->entryHeaderHandle, $this->session);
        } elseif (isset($data['docx'])) {
            $sender = new DocxDocumentSender($this->entryHeaderHandle, $this->session);
        } else {
            return print json_encode(['success' => false, 'error' => 'Unsupported document type']);
        }
        
        return $sender->sendDocument();
    }
    
}
