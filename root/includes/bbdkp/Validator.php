<?php

namespace bbdkp;

/**
 * Validator
 *
 * Data validation class
 *
 * @author      Arvind Gupta <contact [ AT ] arvindgupta [ DOT ] co [ DOT ] in>
 * @copyright   Arvind Gupta (c) 2011
 * @link        http://www.arvindgupta.co.in
 * @license     You're free to do whatever with this as long as this notice
 *              remains intact.
 */
class Validator
{
 
    protected $_rules = array();
    protected $_data = array();
    protected $_messages = array();
    protected $_errors = array();
 
    public function __construct()
    {
        $this->setDefaultMessages();
    }
 
    /**
     * Add a rule
     *
     * @param string $field Field name (index of data to be validated)
     * @param array  $rules Array of rule(s)
     */
    public function addRule($field, array $rules)
    {
        $this->_rules[$field] = $rules;
    }
 
    /**
     * Set data to be validated
     *
     * @param array $data   Data to be validated
     */
    public function setData(array $data)
    {
        $this->_data = $data;
    }
 
    /**
     * Set error message for rule
     *
     * @param string $rule  Rule name
     * @param string $message   New message
     */
    public function setMessage($rule, $message)
    {
        $this->_messages[$rule] = $message;
    }
 
    /**
     * Validates current data with current rules
     *
     * @return boolean
     */
    public function isValid()
    {
        $valid = true;
        foreach ($this->_rules as $field => $rules)
        {
            $value = isset($this->_data[$field]) ? $this->_data[$field] : '';
 
            foreach ($rules as $rule => $parameter)
            {
                // If rule does not require parameter
                if (is_int($rule))
                {
                    $rule = $parameter;
                    $parameter = null;
                }
 
                if (!$this->check($value, $rule, $parameter))
                {
                    $valid = false;
 
                    if (stripos($this->_messages[$rule], '%s') !== false)
                    {
                        $this->_errors[$field][] = sprintf($this->_messages[$rule], $parameter);
                    }
                    else
                    {
                        $this->_errors[$field][] = $this->_messages[$rule];
                    }
                }
            }
        }
 
        return $valid;
    }
 
    /**
     * Get error messages if validation fails
     *
     * @return array    Error messages
     */
    public function getErrors()
    {
        return $this->_errors;
    }
 
    protected function check($value, $rule, $parameter)
    {
        switch ($rule)
        {
            case 'require' :
                return!(trim($value) == '');
 
            case 'maxlength' :
                return (strlen($value) <= $parameter);
 
            case 'minlength' :
                return (strlen($value) >= $parameter);
 
            case 'numeric' :
                return is_numeric($value);
 
            case 'int' :
                return is_int($value);
 
            case 'min' :
                return $value > $parameter ? true : false;
 
            case 'max' :
                return $value < $parameter ? true : false;
 
            case 'url':
                // Regex taken from symfony
                return preg_match('~^
                  (https?)://                               # protocol
                  (
                ([a-z0-9-]+\.)+[a-z]{2,6}               # a domain name
                  |                                     #  or
                \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}      # a IP address
                  )
                  (:[0-9]+)?                                # a port (optional)
                  (/?|/\S+)                                 # a /, nothing or a / with something
                $~ix', $value);
 
            case 'email':
                return preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i', $value);
 
            case 'regex':
                return preg_match($parameter, $value);
 
            case 'pass':
                return true;
 
            default :
                return false;
        }
    }
 
    protected function setDefaultMessages()
    {
        $this->_messages = array(
                'require' => 'Field is required.',
                'maxlength' => 'Too long (%s characters max).',
                'minlength' => 'Too short (%s characters min).',
                'numeric' => 'Value must be numeric.',
                'int' => 'Value must be an integer.',
                'max' => 'Value must be at most %s',
                'min' => 'Value must be at least %s',
                'url' => 'Value must be a valid URL.',
                'email' => 'Value must be a valid email.',
                'regex' => 'Invalid value.',
        );
    }
}


?>