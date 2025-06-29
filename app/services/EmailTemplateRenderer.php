<?php
namespace App\Services;

class EmailTemplateRenderer {
    private $templateDir;
    private $defaultVars = [];
    
    public function __construct($templateDir = null) {
        $this->templateDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $this->setDefaultVars([
            'year' => date('Y'),
            'company_name' => 'SAFARI BOOKS LTD',
            'support_email' => 'support@safaribooks.com'
        ]);
    }
    
    /**
     * Set default variables for all templates
     */
    public function setDefaultVars(array $vars) {
        $this->defaultVars = array_merge($this->defaultVars, $vars);
    }
    
    /**
     * Render a template with provided data
     */
    public function render($templateName, array $data = []) {
        $templateFile = $this->templateDir . $templateName . '.html';
    
        if (!file_exists($templateFile)) {
            throw new RuntimeException("Email template not found: {$templateName}");
        }
        
        $template = file_get_contents($templateFile);
        $data = array_merge($this->defaultVars, $data);
        
        // Process template with HTML content preserved
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
            
            // Simple replacement - NO HTML ESCAPING
            $template = str_replace(
                ["{{{$key}}}", "{{ $key }}"], 
                (string)$value, 
                $template
            );
        }
        
        return $template;
    }
    
    /**
     * Process template with data
     */
    protected function processTemplate($template, $data) {
        // Process loops first
        $template = $this->processLoops($template, $data);
        
        // Then conditionals
        $template = $this->processConditionals($template, $data);
        
        // Finally simple variables
        return $this->replaceVariables($template, $data);
    }
    
    /**
     * Replace variable placeholders: {{var}} or {{ var }}
     */
    protected function replaceVariables($template, $data) {
        return preg_replace_callback('/\{\{\s*(\w+)\s*\}\}/', 
            function($matches) use ($data) {
                $var = $matches[1];
                return isset($data[$var]) ? htmlspecialchars($data[$var]) : '';
            },
            $template
        );
    }
    
    /**
     * Process conditional blocks: {{#condition}}...{{/condition}}
     */
    protected function processConditionals($template, $data) {
        return preg_replace_callback(
            '/\{\{#(\w+)\}\}(.*?)\{\{\/\1\}\}/s',
            function($matches) use ($data) {
                $condition = $matches[1];
                $content = $matches[2];
                
                if (!isset($data[$condition])) return '';
                
                if (is_array($data[$condition])) {
                    $result = '';
                    foreach ($data[$condition] as $item) {
                        $result .= $this->replaceVariables($content, $item);
                    }
                    return $result;
                }
                
                return $data[$condition] ? $content : '';
            },
            $template
        );
    }
    
    /**
     * Process loops: {{@each items}}...{{/each}}
     */
    protected function processLoops($template, $data) {
        return preg_replace_callback(
            '/\{\{@each (\w+)\}\}(.*?)\{\{\/each\}\}/s',
            function($matches) use ($data) {
                $items = $matches[1];
                $content = $matches[2];
                
                if (!isset($data[$items]) || !is_array($data[$items])) return '';
                
                $result = '';
                foreach ($data[$items] as $item) {
                    $result .= $this->replaceVariables($content, $item);
                }
                return $result;
            },
            $template
        );
    }
}