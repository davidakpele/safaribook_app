<?php

namespace Exception;

final class RequestException {
    
    public function validata_api_request_header(){
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: *");
        header("Content-Type: application/json");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        if ($_SERVER['REQUEST_METHOD']=='OPTIONS') {
            header('HTTP/1.1 401 Unauthorized');
            error_log_auth();
        }else{
            return true;
        }
    }

    function sendErrorResponse(string $message, int $statusCode = 400): void {
        http_response_code($statusCode);
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ], JSON_PRETTY_PRINT);
        exit();
    }

   
    public function error_log_auth(){
        header('HTTP/1.1 401 Unauthorized');
        header("Content-Type: application/json");
        $response=
        [ 
            "status"=> http_response_code(401),
            "title"=> "Authentication Error",
            "details"=> "Something went wrong with authentication.",
            "code"=> "generic_authentication_error"
        ];
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }


    public function CorsHeader() {
        $allowedOrigins = [
            "*"
        ];

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Validate the Origin before allowing access
            if (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            } else {
                header("Access-Control-Allow-Origin: *"); // Default fallback
            }
        }

        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400'); // Cache for 1 day

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");         
            header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
            exit(0);
        }

        return true;
    }

    
}
