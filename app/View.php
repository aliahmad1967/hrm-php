<?php
/**
 * View Class
 * Handles view rendering
 */

class View {
    protected $data = [];
    protected $sections = [];
    protected $currentSection = null;
    
    /**
     * Render view
     */
    public function render($view, $data = []) {
        $this->data = $data;
        
        $viewFile = __DIR__ . "/Views/$view.php";
        
        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: $viewFile");
        }
        
        // Extract data variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        include $viewFile;
        
        $content = ob_get_clean();
        
        // If layout is set, render it
        if (isset($this->sections['layout'])) {
            $layout = $this->sections['layout'];
            unset($this->sections['layout']);
            
            $layoutFile = __DIR__ . "/Views/layouts/$layout.php";
            
            if (file_exists($layoutFile)) {
                ob_start();
                include $layoutFile;
                return ob_get_clean();
            }
        }
        
        return $content;
    }
    
    /**
     * Start section
     */
    public function section($name) {
        $this->currentSection = $name;
        ob_start();
    }
    
    /**
     * End section
     */
    public function endSection() {
        if ($this->currentSection) {
            $this->sections[$this->currentSection] = ob_get_clean();
            $this->currentSection = null;
        }
    }
    
    /**
     * Yield section
     */
    public function yield($name, $default = '') {
        return isset($this->sections[$name]) ? $this->sections[$name] : $default;
    }
    
    /**
     * Extend layout
     */
    public function extend($layout) {
        $this->sections['layout'] = $layout;
    }
    
    /**
     * Include partial view
     */
    public function include($partial, $data = []) {
        $partialFile = __DIR__ . "/Views/partials/$partial.php";
        
        if (file_exists($partialFile)) {
            extract(array_merge($this->data, $data));
            include $partialFile;
        }
    }
    
    /**
     * Escape HTML entities
     */
    public static function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}