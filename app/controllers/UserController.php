<?php 

use Session\AuthSession;
use Exception\RequestException;
use Request\RequestHandler;

final class UserController extends Controller
{
    private $repository;
    private $session;

    public function __construct() {
        $this->session= new AuthSession();
        $this->repository = $this->loadModel('User');
        $this->entryHeaderHandle =new RequestException();
    }

    public function update_password($url){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('PUT')) {
                    $urlParts = explode('/', $url); 
                    $controller = !empty($urlParts[0])? $urlParts[0] : '';
                    $controllerName = $controller;
                    $id= trim(filter_var((int)$controllerName, FILTER_SANITIZE_NUMBER_INT)); 

                    ob_start();
                    $jsonString = file_get_contents("php://input", true);
                    $response = array();
                    $phpObject = json_decode($jsonString);
                    
                    $old =  $phpObject->{'old'}; 
                    $new =  $phpObject->{'new'}; 
                    $confirmpassword =  $phpObject->{'confirmpassword'}; 
                    if ($id == "") {
                        $response['message']="The  field is required.";
                        $response['status1'] = false;
                    }
                    if ($old == "") {
                        $response['message']="The  field is required.";
                        $response['status2'] = false;
                    }
                    if ($new == "") {
                    $response['message']="The  field is required.";
                    $response['status3'] = false;
                    }
                    if ($confirmpassword == "") {
                        $response['message']="The  field is required.";
                        $response['status4'] = false;
                    }elseif (!empty($id) && !empty($old) && !empty($new) && !empty($confirmpassword)) {
                        if ($new !== $confirmpassword){
                            $response['message']="New Password does not match with Comfirm Password..! Please Check";
                            $response['status4'] = false;
                        }else {
                            $hash_password = password_hash($new, PASSWORD_ARGON2ID);
                            $response =$this->repository->update_user_password($id,  $new, $hash_password, $old);
                        }
                    }
                    ob_end_clean();
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

    private function validateUserData($phpObject) {
        $errors = array();
      
        if (isset($phpObject->{'email'})) {
            if (empty($phpObject->{'email'})) {
            $errors['email'] = 'Email is required';
            } elseif (!filter_var($phpObject->{'email'}, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
            }
        }

        if (isset($phpObject->{'role'})) {
            if (empty($phpObject->{'role'})) {
            $errors['role'] = 'Role is required';
            }
        }
      
        if (isset($phpObject->{'telephone'})) {
            if (empty($phpObject->{'telephone'})) {
            $errors['telephone'] = 'Telephone is required';
            } elseif (!preg_match('/^\+?\d{10,20}$/', $phpObject->{'telephone'})) {
            $errors['telephone'] = 'Invalid telephone number';
            }
        }
      
        if (isset($phpObject->{'username'})) {
            if (empty($phpObject->{'username'})) {
            $errors['username'] = 'Username is required';
            } elseif (strlen($phpObject->{'username'}) < 3 || strlen($phpObject->{'username'}) > 20) {
            $errors['username'] = 'Username should be between 3 and 20 characters';
            }
        }

        if (isset($phpObject->{'firstname'})) {
            if (empty($phpObject->{'firstname'})) {
                $errors['firstname'] = 'Firstname is required';
            }
        }

        if (isset($phpObject->{'lastname'})) {
            if (empty($phpObject->{'lastname'})) {
                $errors['lastname'] = 'Lastname is required';
            }
        }

        return $errors;
    }
      
    public function edit_user($url) {
        if ($this->session->authCheck()) {
          if ($this->entryHeaderHandle->CorsHeader()) {
            $requestHandler = new RequestHandler($this->entryHeaderHandle);
            if (RequestHandler::isRequestMethod('PUT')) {
                $urlParts = explode('/', $url);
                $controller = !empty($urlParts[0])? $urlParts[0] : '';
                $controllerName = $controller;
                $id= trim(filter_var((int)$controllerName, FILTER_SANITIZE_NUMBER_INT));
                ob_start();
                $jsonString = file_get_contents("php://input", true);             
                $phpObject = json_decode($jsonString);
        
                $errors = $this->validateUserData($phpObject);
                if (!empty($errors)) {
                    header("Content-Type: application/json");
                    http_response_code(400);
                    echo json_encode(array('errors' => $errors), JSON_PRETTY_PRINT);
                    return;
                }
      
                $email = strip_tags(trim(filter_var($phpObject->{'email'}, FILTER_SANITIZE_EMAIL)));
                $role = strip_tags(trim(filter_var($phpObject->{'role'}, FILTER_SANITIZE_STRING)));
                $telephone = strip_tags(trim(filter_var($phpObject->{'telephone'}, FILTER_SANITIZE_STRING)));
                $username = strip_tags(trim(filter_var($phpObject->{'username'}, FILTER_SANITIZE_STRING)));
                
                $response = $this->repository->update_user_details($id, $role, $email, $telephone, $username);
           
                if ($_SESSION['userId'] == $id) {
                    if ($response['status'] =='success') {
                        $user = $this->repository->findById($id);
                        $this->session->set('userId', $user->id); 
                        $this->session->set('email', $user->email);
                        $this->session->set('name', $user->name);
                        $this->session->set('role_name', $user->role_name);
                    }
                }
                ((($response['status'])=='error' ? http_response_code(400): $response['status']=='not_found') ? http_response_code(404):http_response_code(200));
                ob_end_clean();
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
        
    public function list(){
        if ($this->session->authCheck()) {
            $data = [
                'page_title'=> 'User Management',
                'settings'=>[
                    'company_name'=> 'SAFARI BOOKS LIMITED'
                ],
            ];
            $this->view("dashboard/user/users", $data);
        }else{
            redirect("auth/logout");
        }
    }
    
    public function delete(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                if (RequestHandler::isRequestMethod('DELETE')) {
                    $jsonString = file_get_contents("php://input");
                    $response = array();
                    $phpObject = json_decode($jsonString);
                    $id=$phpObject->{'ids'};
                    if($this->repository->delete_user($id)){
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

    public function add(){
        if ($this->session->authCheck()) {
            $roles = $this->repository->findAllRoles();
            $data = [
                'page_title'=> 'Add User',
                'settings'=>[
                    'company_name'=> 'SAFARI BOOKS LIMITED'
                ],
                'roles'=>$roles,
            ];
            $this->view("dashboard/user/create", $data);
        }else{
            redirect("auth/logout");
        }
    }

    public function create(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
              $requestHandler = new RequestHandler($this->entryHeaderHandle);
              if (RequestHandler::isRequestMethod('POST')) {
                  ob_start();
                  $jsonString = file_get_contents("php://input", true);             
                  $phpObject = json_decode($jsonString);
          
                  $errors = $this->validateUserData($phpObject);
                  if (!empty($errors)) {
                      header("Content-Type: application/json");
                      http_response_code(400);
                      echo json_encode(array('errors' => $errors), JSON_PRETTY_PRINT);
                      return;
                  }
        
                  $email = strip_tags(trim(filter_var($phpObject->{'email'}, FILTER_SANITIZE_EMAIL)));
                  $role = strip_tags(trim(filter_var($phpObject->{'role'}, FILTER_SANITIZE_STRING)));
                  $telephone = strip_tags(trim(filter_var($phpObject->{'telephone'}, FILTER_SANITIZE_STRING)));
                  $firstname = strip_tags(trim(filter_var($phpObject->{'firstname'}, FILTER_SANITIZE_STRING)));
                  $lastname = strip_tags(trim(filter_var($phpObject->{'lastname'}, FILTER_SANITIZE_STRING)));

                  $username = $firstname.' '.$lastname;
                  $password = '123';
                  $hash_password = password_hash($password, PASSWORD_ARGON2ID);
                
                  if($this->repository->findByEmail($email)){
                    $error = array (
                        "email"=>"Email address been used by another user, please provide a different email address."
                    );
                    $response['errors'] =  $error;
                    http_response_code(401);
                  }else{
                    $request = $this->repository->create_new($role, $email, $telephone, $username, $hash_password);
                    if (!$request) {
                        $response['message']= "Fail to save user details.";
                        http_response_code(401);
                    }else{
                        $response['message']= "User account successfully created.";
                        http_response_code(201);
                    }
                  }
                  ob_end_clean();
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
}