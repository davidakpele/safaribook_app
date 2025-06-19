<?php
namespace Request;

class RequestHandler 
{
  protected $requestException;

  public function __construct($requestException) {
    $this->requestException = $requestException;
  }

  public static function isRequestMethod($method){
    return $_SERVER['REQUEST_METHOD']=== strtoupper($method);
  } 


  /**
   * Handles incoming request with CORS and validation
  */
    public function handleRequest($method, $cors = true) {
        if (self::isRequestMethod($method)) {
          if ($cors && !$this->requestException->CorsHeader()) {
              return ['error' => 'CORS headers failed'];
          }
          
          if (!$this->requestException->validata_api_request_header()) {
              return ['error' => $this->requestException->error_log_auth()];
          }
          
          return $this->getJsonInput();
        }
          
        return ['error' => 'Invalid request method'];
      }

      /**
      * Get and decode the JSON input from the request
      */
    private function getJsonInput() {
        $jsonString = file_get_contents("php://input");
        return json_decode($jsonString, true);
    }

  /**
   * Sanitize a specific field from the request
  */
   public function sanitizeField($field, $filter = FILTER_SANITIZE_STRING) {
    return strip_tags($field, $filter);
  }
  
  
    public function getClientIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
    
        // Handle multiple IPs in HTTP_X_FORWARDED_FOR
        if (strpos($ipaddress, ',') !== false) {
            $ips = explode(',', $ipaddress);
            $ipaddress = trim($ips[0]); // Get the first IP in the list
        }
    
        return $ipaddress;
    }
    
    function getUserLanguage() {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2); 
            return strtoupper($lang); // Convert to uppercase
        }
        return 'Unknown';
    }
}
