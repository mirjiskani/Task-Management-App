<?php

namespace App\Service;

class PasswordValidator
{
    public static function validate(string $password): array
    {
        $errors = [];
        
        // Length validation
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }
        
        // Complexity validation
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&])/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character';
        }
        
        // Common weak passwords
        $weakPasswords = ['password', '123456', 'qwerty', 'abc123', 'password123'];
        if (in_array(strtolower($password), $weakPasswords)) {
            $errors[] = 'Password is too common. Please choose a stronger password.';
        }
        
        return $errors;
    }
    
    public static function getStrength(string $password): string
    {
        $score = 0;
        
        // Length scoring
        if (strlen($password) >= 8) $score += 1;
        if (strlen($password) >= 12) $score += 1;
        
        // Complexity scoring
        if (preg_match('/[a-z]/', $password)) $score += 1;
        if (preg_match('/[A-Z]/', $password)) $score += 1;
        if (preg_match('/[0-9]/', $password)) $score += 1;
        if (preg_match('/[^a-zA-Z0-9]/', $password)) $score += 1;
        
        if ($score <= 2) return 'Weak';
        if ($score <= 4) return 'Fair';
        if ($score <= 6) return 'Good';
        return 'Strong';
    }
}
