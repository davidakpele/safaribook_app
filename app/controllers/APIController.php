<?php

use Session\AuthSession;
use Exception\RequestException;
use Request\RequestHandler;

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
                    $response=  $this->repository->getAllUsers();
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
                        $stored_number = $last_record->number;
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
    

}
