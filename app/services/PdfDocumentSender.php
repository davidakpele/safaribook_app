<?php 
namespace App\Services;

use App\Services\DocumentSender;

class PdfDocumentSender extends DocumentSender {
    protected function extractFileContent($data) {
        return [
            'content' => $data['pdf'] ?? null,
            'extension' => 'pdf'
        ];
    }

    protected function compressFile($filePath, $fileExtension) {
        if ($fileExtension !== 'pdf') return false;
        
        // Try Ghostscript first
        if ($this->isGhostscriptAvailable()) {
            $compressedPath = $filePath . '_compressed.pdf';
            $quality = filesize($filePath) > 5 * 1024 * 1024 ? '/ebook' : '/prepress';
            
            exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 " .
                 "-dPDFSETTINGS=$quality -dNOPAUSE -dQUIET -dBATCH " .
                 "-sOutputFile=$compressedPath $filePath");
            
            if (file_exists($compressedPath)) {
                return $compressedPath;
            }
        }
        
        // Fallback to gzip
        return $this->compressWithGzip($filePath);
    }
    
    private function isGhostscriptAvailable() {
        exec('gs --version', $output, $return);
        return $return === 0;
    }
    
    private function compressWithGzip($filePath) {
        $compressedPath = $filePath . '.gz';
        $data = file_get_contents($filePath);
        $compressedData = gzencode($data, 9);
        
        return file_put_contents($compressedPath, $compressedData) ? $compressedPath : false;
    }
}