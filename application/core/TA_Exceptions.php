<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TA_Exceptions extends CI_Exceptions
{
    public function __construct()
    {
        parent::__construct();
    } 

    public $data = array();

	//TODOS OS ERROS DO FRAMEWORK SÃO RETORNADOS EM FORMATO JSON 
	public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{	
        $config =& get_config();

        if(!isset($_SERVER['HTTP_HOST'])){
            return parent::show_error($heading, $message,$template,$status_code);
        }

        if($_SERVER['HTTP_HOST'] == $config['api_domain'] ){
            header('Content-Type: application/json');
            http_response_code ($status_code);
            
            array_push($this->data, array(
                'type' => 'error',
                'message' => $message
            ));
        }
        else{
            return parent::show_error($heading, $message,$template,$status_code);
        }
        
    }
    
    public function show_php_error($severity, $message, $filepath, $line)
    {
        $config =& get_config();

        
        if(!isset($_SERVER['HTTP_HOST'])){
            return parent::show_php_error($severity, $message, $filepath, $line);
        }


        if($_SERVER['HTTP_HOST'] == $config['api_domain'] ){
            header('Content-Type: application/json');
            http_response_code (500);
            
            array_push($this->data,  array(
                'type' => 'error',
                'severity' => $severity,
                'message' => $message,
                'filepath' => $filepath,
                'line' => $line
            ));
        }
        else{
            return parent::show_php_error($severity, $message, $filepath, $line);
        }
    }

    public function show_exception($exception)
	{
		$config =& get_config();

        if(!isset($_SERVER['HTTP_HOST'])){
            return parent::show_exception($exception);
        }

        if($_SERVER['HTTP_HOST'] == $config['api_domain'] ){
            header('Content-Type: application/json');
            http_response_code (500);
            
            array_push($this->data,   array(
                'type' => 'error',
                'exception' => $exception
            ));
        }
        else{
            return parent::show_exception($exception);
        }
	}
    
    public function retorno(){
        $config =& get_config();

        if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == $config['api_domain'] ){
            echo json_encode($this->data);
        }
    }

    function __destruct() {
       $this->retorno();
    }
}
