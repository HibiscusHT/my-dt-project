<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Erp extends CI_Controller 
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
            $source = $this->path . '/views/erp/login.html';
            $data = file_get_contents($source);
            $ptn = ['/{token}/','/{url}/'];
            $repl = ['',base_url()];
            $return = preg_replace($ptn,$repl,$data);
            echo $return;
            break;
            default: 
            $token = $this->input->get('token');
            $source = $this->path . '/views/erp/' . $any . '.html';
            $header = $this->path . '/views/erp/header.html';
            $footer = $this->path . '/views/erp/footer.html';
            $data = file_get_contents($source);
            $p = ['/{token}/','/{url}/'];
            $r = [$token,base_url()];
            $hdr = preg_replace($p,$r,file_get_contents($header));
            $ftr = preg_replace($p,$r,file_get_contents($footer));
            $ptn = ['/{token}/','/{url}/','/{header}/','/{footer}/'];
            $repl = [$token,base_url(),$hdr,$ftr];
            $return = preg_replace($ptn,$repl,$data);
            echo $return;
            break;
        }
    }
}