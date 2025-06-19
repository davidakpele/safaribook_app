<?php 

use Request\RequestHandler;
use Exception\RequestException;
use Session\AuthSession;
use Custom\Mailer;
use JwtToken\JwtService;
use Api\api;
use \SecurityFilterChainBlock\UrlFilterChain;

final class AuthController extends Controller
{
    private $repository;
    private $session;

    public function __construct() {
        $this->session= new AuthSession();
        $this->repository = $this->loadModel('User');
    }

    public function login() {
        $requestException = new RequestException();
        if ($requestException->CorsHeader()) {
            $requestHandler = new RequestHandler($requestException);
            if ($requestHandler->isRequestMethod('POST')) {
                $postRequest = $requestHandler->handleRequest('POST');
                $response = array();
                
                if (isset($postRequest['error'])) {
                    echo json_encode(['error' => $postRequest['error']]);
                } else {
                    $jsonString = file_get_contents("php://input");
                    $phpObject = json_decode($jsonString);
                    $email= $phpObject->{'email'};
                    $password = $phpObject->{'password'};
                    if (empty($email)) {
                        $response= array('status'=>'error','message' =>"Email address is require.");
                        http_response_code(400);
                    }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $response = array('status' => 'error', 'message' => "Invalid email address.");
                        http_response_code(400);
                    }
                    if (!empty($response['status']) && $response['status'] == 'error') {
                        echo json_encode($response);
                        die();
                    }
                    if (empty($password)) {
                        $response= array('status'=>'error','message' =>"Password is require.");
                        http_response_code(400);
                    }
                    if (!empty($response['status']) && $response['status'] == 'error') {
                        echo json_encode($response);
                        die();
                    }
                    $user = $this->repository->login($email, $password);
                    if ($user == false) {
                        $response= array('status'=>'error','message' =>"Invalid credentials provided..!");
                        http_response_code(400);
                    }else{
                        $authClass= new JwtService();
                        $this->session->set('userId', $user->id); 
                        $this->session->set('email', $user->email);
                        $this->session->set('name', $user->name);
                        $this->session->set('role_name', $user->role_name);

                        $id = $user->id;
                        $email = $user->email;
                        $fullname =$user->name;
                        $role = $user->role_name;
                        
                        $jwt = $authClass::createTokenByUserDetails($id, $email, $role);
                        
                        $redirectUrl = '';

                        switch (strtoupper($role)) {
                            case 'CEO':
                                $redirectUrl = 'ceo';
                                $this->session->set('role', "ceo");
                                break;
                            case 'MANAGER':
                                $redirectUrl = 'manager';
                                $this->session->set('role', "manager");
                                break;
                            case 'ACCOUNTANT':
                                $redirectUrl = 'accountant';
                                $this->session->set('role', "accountant");
                                break;
                            case 'HEAD - BUSINESS DEVELOPMENT':
                                $redirectUrl = 'business_dev';
                                $this->session->set('role', "business-dev");
                                break;
                            case 'HUMAN RESOURCE - HR':
                                $redirectUrl = 'hr';
                                $this->session->set('role', "hr");
                                break;
                            case 'EDITORIAN MANAGER - ED':
                                $redirectUrl = 'editor';
                                $this->session->set('role', "editors");
                                break;
                            case 'MARKETING MANAGER':
                                $redirectUrl = 'marketing';
                                $this->session->set('role', "marketing");
                                break;
                            case 'DRIVER':
                                $redirectUrl = 'driver';
                                $this->session->set('role', "driver");
                                break;
                            default:
                                $redirectUrl = 'general';
                        }

                        $response = array(
                            'status' => 'success',
                            'token' => json_decode($jwt)->token,
                            'id' => $user->id,
                            'email' => $user->email,
                            'name' => $user->name,
                            'role' => $role,
                            'redirect' => $redirectUrl
                        );
                        
                        http_response_code(200);
                    }
                }
                echo json_encode($response);
            }elseif ($requestHandler->isRequestMethod('GET')) {
                $data = array(
                    "page_title"=> "Login Account",
                );
                $this->view("auth/login", $data);
            }else{
               $this->errorHandler->sendErrorResponse("Method Not Allowed", 405);
            }
        }
    }

    public function logout(){
        $requestException = new RequestException();
        if ($requestException->CorsHeader()) {
            if ($this->session->destroy()) {
                $response = array("status"=>"success", "message"=>"Logout User Successful");
                echo json_encode($response);
                http_response_code(200);
                redirect("auth/login");
            }
        }
    }


}
