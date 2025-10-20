<?php

namespace App;

// This is a test file to demonstrate SonarQube analysis
// It contains various code quality issues on purpose

class TestSonarQube
{
    // Security hotspot: hardcoded credentials
    private $password = "admin123";  // NOSONAR - This is intentional for demo
    
    // Code smell: unused variable
    public function testMethod()
    {
        $unusedVariable = "This variable is never used";
        $result = 0;
        
        // Bug: potential division by zero
        $division = 10 / $result;
        
        // Code smell: magic number
        for ($i = 0; $i < 100; $i++) {
            $result += $i;
        }
        
        return $result;
    }
    
    // Security vulnerability: SQL injection risk
    public function queryDatabase($userInput)
    {
        $sql = "SELECT * FROM users WHERE name = '" . $userInput . "'";
        // This is vulnerable to SQL injection
        return $sql;
    }
    
    // Code smell: too many parameters
    public function tooManyParameters($a, $b, $c, $d, $e, $f, $g, $h)
    {
        return $a + $b + $c + $d + $e + $f + $g + $h;
    }
    
    // Code smell: duplicate code
    public function duplicateMethod1()
    {
        echo "This is duplicate code";
        echo "Line 2";
        echo "Line 3";
        echo "Line 4";
        echo "Line 5";
    }
    
    public function duplicateMethod2()
    {
        echo "This is duplicate code";
        echo "Line 2";
        echo "Line 3";
        echo "Line 4";
        echo "Line 5";
    }
}