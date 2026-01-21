<?php
namespace App\Helpers;

/**
 * Input Validator Helper
 */
class Validator {
    private $errors = [];
    private $data = [];
    private $labels = [];
    
    /**
     * Validate input data
     * 
     * @param array $data Input data
     * @param array $rules Validation rules
     * @return Validator
     */
    public static function make($data, $rules, $labels = []) {
        $validator = new self();
        $validator->data = $data;
        $validator->labels = $labels;
        
        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            
            foreach ($rulesArray as $rule) {
                $validator->applyRule($field, $rule);
            }
        }
        
        return $validator;
    }
    
    /**
     * Apply a single validation rule
     */
    private function applyRule($field, $rule) {
        $value = $this->data[$field] ?? null;
        
        // Parse rule parameters
        $params = [];
        if (strpos($rule, ':') !== false) {
            list($rule, $paramString) = explode(':', $rule, 2);
            $params = explode(',', $paramString);
        }
        
        $label = $this->labels[$field] ?? $field;
        
        switch ($rule) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, "{$label} alanı zorunludur.");
                }
                break;
                
            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "Geçerli bir e-posta adresi giriniz.");
                }
                break;
                
            case 'min':
                $min = $params[0] ?? 0;
                if ($value && mb_strlen($value) < $min) {
                    $this->addError($field, "{$label} en az {$min} karakter olmalıdır.");
                }
                break;
                
            case 'max':
                $max = $params[0] ?? 255;
                if ($value && mb_strlen($value) > $max) {
                    $this->addError($field, "{$label} en fazla {$max} karakter olmalıdır.");
                }
                break;
                
            case 'numeric':
                if ($value && !is_numeric($value)) {
                    $this->addError($field, "{$label} sayısal olmalıdır.");
                }
                break;
                
            case 'unique':
                if (count($params) >= 2) {
                    list($table, $column) = $params;
                    $this->validateUnique($field, $value, $table, $column);
                }
                break;
                
            case 'same':
                $otherField = $params[0] ?? '';
                $otherValue = $this->data[$otherField] ?? null;
                $otherLabel = $this->labels[$otherField] ?? $otherField;
                if ($value !== $otherValue) {
                    $this->addError($field, "{$label} ile {$otherLabel} eşleşmiyor.");
                }
                break;
        }
    }
    
    /**
     * Validate unique constraint
     */
    private function validateUnique($field, $value, $table, $column) {
        global $baglanti;
        $label = $this->labels[$field] ?? $field;
        
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = ?";
        $stmt = $baglanti->prepare($sql);
        $stmt->execute([$value]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            $this->addError($field, "Bu {$label} zaten kullanılmaktadır.");
        }
    }
    
    /**
     * Add validation error
     */
    private function addError($field, $message) {
        $this->errors[$field][] = $message;
    }
    
    /**
     * Check if validation failed
     */
    public function fails() {
        return !empty($this->errors);
    }
    
    /**
     * Check if validation passed
     */
    public function passes() {
        return empty($this->errors);
    }
    
    /**
     * Get all errors
     */
    public function errors() {
        return $this->errors;
    }
    
    /**
     * Get first error for a field
     */
    public function first($field) {
        return $this->errors[$field][0] ?? null;
    }
}
