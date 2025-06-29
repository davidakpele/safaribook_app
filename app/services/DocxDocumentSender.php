<?php
use App\Services\DocumentSender;

class DocxDocumentSender extends DocumentSender {
    protected function extractFileContent($data) {
        return [
            'content' => $data['docx'] ?? null,
            'extension' => 'docx'
        ];
    }

    protected function compressFile($filePath, $fileExtension) {
        if ($fileExtension !== 'docx') return false;
        
        // Try ZipArchive if available
        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            $compressedPath = $filePath . '.zip';
            
            if ($zip->open($compressedPath, ZipArchive::CREATE)) {
                $zip->addFile($filePath, basename($filePath));
                $zip->setCompressionIndex(0, ZipArchive::CM_DEFLATE);
                $zip->close();
                
                if (file_exists($compressedPath)) {
                    return $compressedPath;
                }
            }
        }
        
        // Fallback to gzip
        return $this->compressWithGzip($filePath);
    }
    
    private function compressWithGzip($filePath) {
        $compressedPath = $filePath . '.gz';
        $data = file_get_contents($filePath);
        $compressedData = gzencode($data, 9);
        
        return file_put_contents($compressedPath, $compressedData) ? $compressedPath : false;
    }
}