<?php 
namespace App\Services;

use Session\AuthSession;
use App\Services\PdfDocumentSender;
use App\Services\DocxDocumentSender;
use App\Services\EmailTemplateRenderer;
use Exception\RequestException;
use Request\RequestHandler;
use PHPMailer\PHPMailer\PHPMailer;


abstract class DocumentSender {
    protected $entryHeaderHandle;
    protected $session;
    protected $settings;
    protected $templateRenderer;

    public function __construct($entryHeaderHandle, $session, $settings = []) {
        $this->entryHeaderHandle = $entryHeaderHandle;
        $this->session = $session;
        $this->settings = $settings;
        $this->templateRenderer = new EmailTemplateRenderer($this->settings);
    }

    // Template Method - defines the algorithm skeleton
    final public function sendDocument() {
        header('Content-Type: application/json');
    
        // Authentication and validation
        if (!$this->session->authCheck()) {
            return $this->sendErrorResponse("Access denied", 401);
        }
    
        if (!$this->entryHeaderHandle->CorsHeader()) {
            return $this->sendErrorResponse("CORS misconfigured.", 400);
        }
    
        if (!RequestHandler::isRequestMethod('POST')) {
            return $this->sendErrorResponse("Method Not Allowed", 405);
        }
    
        $data = $this->getInputData();
    
        if (!$data) {
            error_log("Empty or invalid JSON payload");
            return $this->sendJsonResponse(['success' => false, 'error' => 'Invalid JSON input']);
        }
    
        // Extract common data
        $to = $data['to'] ?? [];
        $cc = $data['cc'] ?? [];
        $bcc = $data['bcc'] ?? [];
        $subject = $data['subject'] ?? 'Invoice';
        $message = $data['message'] ?? '';
        $invoiceId = $data['invoice_id'] ?? time();
    
        // Get file content - implemented by subclasses
        $fileInfo = $this->extractFileContent($data);
        if (empty($to) || empty($fileInfo['content'])) {
            return $this->sendJsonResponse(['success' => false, 'error' => 'Missing recipient or file content']);
        }
    
        // Save file
        $savedFile = $this->saveFile($fileInfo['content'], $fileInfo['extension'], $invoiceId);
        if (!$savedFile) {
            return $this->sendJsonResponse(['success' => false, 'error' => "Failed to write file"]);
        }
    
        // Compress file (hook method)
        $compressedFile = $this->compressFile($savedFile['path'], $fileInfo['extension']);
        $attachmentPath = $compressedFile ?: $savedFile['path'];
        $attachmentName = $compressedFile ? basename($compressedFile) : $savedFile['name'];
    
        // Send email
        $result = $this->sendEmail(
            $to, $cc, $bcc, $subject, $message, $attachmentPath, $attachmentName
        );
    
        // Cleanup
        $this->cleanupFiles($savedFile['path'], $compressedFile);
    
        return $this->sendJsonResponse($result);
    }

    // Primitive operations to be implemented by subclasses
    abstract protected function extractFileContent($data);
    abstract protected function compressFile($filePath, $fileExtension);

    // Hook methods (can be overridden)
    protected function getInputData() {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    protected function saveFile($content, $extension, $invoiceId) {
        $uploadFolder = 'uploads/invoices/';
        if (!file_exists($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }
    
        $fileName = 'invoice_' . $invoiceId . '_' . time() . '.' . $extension;
        $filePath = $uploadFolder . $fileName;
    
        if (file_put_contents($filePath, base64_decode($content))) {
            return ['path' => $filePath, 'name' => $fileName];
        }
        
        return false;
    }
    
    protected function sendEmail($to, $cc, $bcc, $subject, $message, $attachmentPath, $attachmentName) {
        $mail = new PHPMailer(true);
    
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'akpeledavidprogress@gmail.com';
            $mail->Password = 'exmmfqzmigjrkdpd';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            $mail->setFrom('safarinigeria@gmail.com', 'SAFARI BOOKS LTD');
    
            foreach ($to as $email) {
                $mail->addAddress($email);
            }
            foreach ($cc as $email) {
                $mail->addCC($email);
            }
            foreach ($bcc as $email) {
                $mail->addBCC($email);
            }
           

            // Render template
            $renderer = new EmailTemplateRenderer();
            $html = $renderer->render('email', [
                'message' => $message,
                'subject' => $subject,
                'attachment_name' => $attachmentName,
            ]);

            // Email content
            $mail->Subject = $subject;
            $mail->Body = $html;
            $mail->isHTML(true);
            $mail->AltBody = strip_tags($message);

            $maxFileSize = 10 * 1024 * 1024;
            if (file_exists($attachmentPath) && filesize($attachmentPath) <= $maxFileSize) {
                $mail->addAttachment($attachmentPath, $attachmentName);
            } else {
                return ['success' => false, 'error' => 'Attachment is missing or exceeds 10MB size limit'];
            }
    
            $mail->send();
            return ['success' => true, 'message' => 'Document sent successfully'];
        } catch (Exception $e) {
            error_log("PHPMailer error: " . $mail->ErrorInfo);
            return ['success' => false, 'error' => $mail->ErrorInfo];
        }
    }
    
    protected function cleanupFiles($originalPath, $compressedPath = null) {
        if (file_exists($originalPath)) unlink($originalPath);
        if ($compressedPath && file_exists($compressedPath)) unlink($compressedPath);
    }
    
    protected function sendErrorResponse($message, $code) {
        return $this->entryHeaderHandle->sendErrorResponse($message, $code);
    }
    
    protected function sendJsonResponse($data) {
        return print json_encode($data);
    }
}