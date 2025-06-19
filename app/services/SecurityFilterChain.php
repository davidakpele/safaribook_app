<?php
namespace SecurityFilterChainBlock;

use JwtToken\JwtService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use  Session\UserSessionManager;
use Exception\RequestException;
use App\Helpers\Model\Repository\Injection\Repository;

/**
 *
 * @return boolean 
 * 
 */


final class SecurityFilterChain 
{
    private $param;
    private $msg_block;
    private $responses;
    private $jwtKey;
    private $UserRepository;

    public function __construct() {
        @$this->jwtKey = PRIVATE_KEY;
        $this->UserRepository = Repository::loadModel('User');

        if (!$this->UserRepository) {
            // Handle error if model could not be loaded
            throw new \Exception("Repository class 'User Repository' could not be loaded.");
        }
    }   

    public function protectedChainblock(){
        // check if user is authenticated
        if (!$this->isAuthenticated()) {
            header('HTTP/1.1 401 Unauthorized');
            http_response_code(401);
            $responses = [];
            $responses['messsage']='Unauthorized Access.';
        }else{
            return $this->isValidToken();
        }
    }

    public function isAuthenticated(){
        try {
           $isLog = new UserSessionManager();
            if ($isLog->authCheck()) {
                return true;
            }else{
                $error = new RequestException();
                $error ->error_log_auth();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function isValidToken() {
        // Get all request headers
        $headers = apache_request_headers();
        
        // Check for Authorization header in $_SERVER or headers
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? ($headers['Authorization'] ?? null);
        
        // Validate Bearer token
        if (!empty($authHeader)) {
            if (strpos($authHeader, 'Bearer ') == 0) {
                $token = str_replace('Bearer ', '', $authHeader);
                return $this->verifyToken($token);
            }
        }
     
        // Return error if no token is found
        header('HTTP/1.1 403 Unauthorized');
        $error_response = array(
            "status" => http_response_code(403),
            "title" => "Authentication Error",
            "details" => "Forbidden Access: Missing or invalid Bearer token.",
        );
        echo json_encode($error_response, JSON_PRETTY_PRINT);
        exit();

    }

    public function isAdminValidToken() {
        // Get all request headers
        $headers = apache_request_headers();
        
        // Check for Authorization header in $_SERVER or headers
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? ($headers['Authorization'] ?? null);
        
        // Validate Bearer token
        if (!empty($authHeader)) {
            if (strpos($authHeader, 'Bearer ') == 0) {
                $token = str_replace('Bearer ', '', $authHeader);
                return $this->verifyAdminToken($token);
            }
        }
     
        // Return error if no token is found
        header('HTTP/1.1 403 Unauthorized');
        $error_response = array(
            "status" => http_response_code(403),
            "title" => "Authentication Error",
            "details" => "Forbidden Access: Missing or invalid Bearer token.",
        );
        echo json_encode($error_response, JSON_PRETTY_PRINT);
        exit();

    }
    
    public function verifyToken($token){
        try {
            $tokenClass= new JwtService();
            $jwt_decoded_token = $tokenClass::decodeToken($token);
            if (isset($jwt_decoded_token['data']['decoded']['message']) || array_key_exists('message', $jwt_decoded_token['data']['decoded'])) {
                header('HTTP/1.1 401 Unauthorized');
                $error_response = array(
                    "status" => http_response_code(401),
                    "title" => "Authentication Error",
                    "details" => "Invalid Token: " . $jwt_decoded_token['data']['decoded']['message'],
                );
                echo json_encode($error_response, JSON_PRETTY_PRINT);
                exit();
            }else{
               
                $user_encoded_email = $jwt_decoded_token['data']['decoded']['sub'];
                $user_encoded_id = $jwt_decoded_token['data']['decoded']['group']->id;
                
                $verifyUser = $this->UserRepository->verifyUserAccount($user_encoded_email, $user_encoded_id);
                if ($verifyUser) {
                    return $verifyUser; // Token is valid, user is verified
                } else {
                    header('HTTP/1.1 401 Unauthorized');
                    $error_response = array(
                        "status" => http_response_code(401),
                        "title" => "Authentication Error",
                        "details" => "Unauthorized",
                    );
                    echo json_encode($error_response, JSON_PRETTY_PRINT);
                    exit();
                }
            }
        } catch (Exception $e) {
            // If any error occurs (e.g., JWT signature verification fails), return a 401 error
            header('HTTP/1.1 401 Unauthorized');
            $error_response = array(
                "status" => http_response_code(401),
                "title" => "Authentication Error",
                "details" => "Invalid Token: " . $e->getMessage(),
            );
            echo json_encode($error_response, JSON_PRETTY_PRINT);
            exit();
        }
    }
    
    public function verifyAdminToken($token){
        try {
            $tokenClass= new JwtService();
            $jwt_decoded_token = $tokenClass::decodeToken($token);
            if (isset($jwt_decoded_token['data']['decoded']['message']) || array_key_exists('message', $jwt_decoded_token['data']['decoded'])) {
                header('HTTP/1.1 401 Unauthorized');
                $error_response = array(
                    "status" => http_response_code(401),
                    "title" => "Authentication Error",
                    "details" => "Invalid Token: " . $jwt_decoded_token['data']['decoded']['message'],
                );
                echo json_encode($error_response, JSON_PRETTY_PRINT);
                exit();
            }else{
               
                $adminSub = $jwt_decoded_token['data']['decoded']['sub'];
                if (!empty($adminSub)) {
                    return true; // Token is valid, user is verified
                } else {
                    header('HTTP/1.1 401 Unauthorized');
                    $error_response = array(
                        "status" => http_response_code(401),
                        "title" => "Authentication Error",
                        "details" => "Unauthorized",
                    );
                    echo json_encode($error_response, JSON_PRETTY_PRINT);
                    exit();
                }
            }
        } catch (Exception $e) {
            // If any error occurs (e.g., JWT signature verification fails), return a 401 error
            header('HTTP/1.1 401 Unauthorized');
            $error_response = array(
                "status" => http_response_code(401),
                "title" => "Authentication Error",
                "details" => "Invalid Token: " . $e->getMessage(),
            );
            echo json_encode($error_response, JSON_PRETTY_PRINT);
            exit();
        }
    }
   
   
}
