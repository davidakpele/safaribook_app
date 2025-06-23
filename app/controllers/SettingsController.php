<?php 

use Session\AuthSession;
use Exception\RequestException;
use Request\RequestHandler;

final class SettingsController extends Controller
{
    private $repository;
    private $session;

    public function __construct() {
        $this->session= new AuthSession();
        $this->repository = $this->loadModel('DataRepository');
        $this->entryHeaderHandle =new RequestException();
    }

    public function create(){
        if ($this->session->authCheck()) {
            if ($this->entryHeaderHandle->CorsHeader()) {
                $requestHandler = new RequestHandler($this->entryHeaderHandle);
                
                if (RequestHandler::isRequestMethod('POST')) {
                    $response = array();
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                  
                    if (isset($_FILES['company-main-logo']['name']) && isset($_FILES['company-icon-logo']['name']) && isset($_POST['company-name']) && isset($_POST['company-tagline']) && isset($_POST['company-rc-number']) && isset($_POST['company-email']) && isset($_POST['company-address']) && isset($_POST['company-url']) && isset($_POST['company-country']) && isset($_POST['company-city']) && isset($_POST['company-telephone'])) {
                        $company_name = $_POST['company-name'];
                        $company_tag = $_POST['company-tagline'];
                        $company_rc_number = $_POST['company-rc-number'];
                        $company_email = $_POST['company-email'];
                        $company_address = $_POST['company-address'];
                        $company_url = $_POST['company-url'];
                        $company_country = $_POST['company-country'];
                        $company_city = $_POST['company-city'];
                        $company_telephone = $_POST['company-telephone'];

                        // Validate and upload company main logo
                        $mainLogo = $_FILES['company-main-logo'];
                        $mainLogoName = $mainLogo['name'];
                        $mainLogoNameArray = explode('.', $mainLogoName);
                        $mainLogoFileName = $mainLogoNameArray[0];
                        $mainLogoFileExt = $mainLogoNameArray[1];
                        $mainLogoMime = explode('/', $mainLogo['type']);
                        $mainLogoMimeType = $mainLogoMime[0];
                        $mainLogoMimeExt = $mainLogoMime[1];
                        $mainLogoTmpLoc = $mainLogo['tmp_name'];
                        $mainLogoFileSize = $mainLogo['size'];
                        $mainLogoUploadName = md5(microtime()).'.'.$mainLogoFileExt;
                        $mainLogoUploadPath = 'company_settings/main_logo/'.$mainLogoUploadName;
                        $mainLogoDbPath = 'company_settings/main_logo/'.$mainLogoUploadName;
                        $mainLogoFolder = 'company_settings/main_logo/';
                  
                        // Validate and upload company icon logo
                        $iconLogo = $_FILES['company-icon-logo'];
                        $iconLogoName = $iconLogo['name'];
                        $iconLogoNameArray = explode('.', $iconLogoName);
                        $iconLogoFileName = $iconLogoNameArray[0];
                        $iconLogoFileExt = $iconLogoNameArray[1];
                        $iconLogoMime = explode('/', $iconLogo['type']);
                        $iconLogoMimeType = $iconLogoMime[0];
                        $iconLogoMimeExt = $iconLogoMime[1];
                        $iconLogoTmpLoc = $iconLogo['tmp_name'];
                        $iconLogoFileSize = $iconLogo['size'];
                        $iconLogoUploadName = md5(microtime().rand()).'.'.$iconLogoFileExt;
                        $iconLogoUploadPath = 'company_settings/icon_logo/'.$iconLogoUploadName;
                        $iconLogoDbPath = 'company_settings/icon_logo/'.$iconLogoUploadName;
                        $iconLogoFolder = 'company_settings/icon_logo/';
                  
                        if ($mainLogoFileSize > 500000 || $iconLogoFileSize > 500000) {
                            $response['status'] = 300;
                            $response['error'] = '<b>ERROR:</b> File size exceeds the limit.';
                        } else {
                            if (!file_exists($mainLogoFolder)) {
                                mkdir($mainLogoFolder, 0777, true);
                            }

                            if (!file_exists($iconLogoFolder)) {
                                mkdir($iconLogoFolder, 0777, true);
                            }
                  
                            // Delete old files
                            foreach (glob($mainLogoFolder . '/*') as $file) {
                                unlink($file);
                            }

                            foreach (glob($iconLogoFolder . '/*') as $file) {
                                unlink($file);
                            }
                  
                            // Upload files
                            move_uploaded_file($mainLogoTmpLoc, $mainLogoUploadPath);
                            move_uploaded_file($iconLogoTmpLoc, $iconLogoUploadPath);
                    
                            if($this->repository->save_settings($company_name, $company_tag, $company_rc_number, 
                            $company_email, $company_address, $company_url, $company_country, $company_city, 
                            $company_telephone, $mainLogoDbPath, $iconLogoDbPath)){
                                $response = [
                                    'status' => 'success',
                                    'message' => 'Company Settings successfully created',
                                ];
                                http_response_code(201);
                            }else{
                                $response = [
                                    'status' => 'error',
                                    'message' => 'Something went to wrong.',
                                ];
                                http_response_code(400);
                            }
                        }
                        header("Content-Type: application/json");
                        echo json_encode($response, JSON_PRETTY_PRINT);    
                    }else {
                      $response['error'] = '<b>ERROR:</b> Please fill out all required fields.';
                    }
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