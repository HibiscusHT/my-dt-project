<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Intranet extends CI_Controller 
{

    private $path;

    public function __construct()
    {
        parent::__construct();
        $this->path = dirname(__FILE__,3);
    }

    public function process($any)
    {
        switch($any){
            case 'login': 
            $source = $this->path . '/views/intranet/login.html';
            $data = file_get_contents($source);
            $ptn = ['/{token}/','/{url}/'];
            $repl = ['',base_url()];
            $return = preg_replace($ptn,$repl,$data);
            echo $return;
            break;
            default: 
            $token = $this->input->get('token');
            $source = $this->path . '/views/intranet/' . $any . '.html';
            $data = file_get_contents($source);
            $ptn = ['/{token}/','/{url}/'];
            $repl = [$token,base_url()];
            $return = preg_replace($ptn,$repl,$data);
            echo $return;
            break;
        }
    }
}