<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'process/Tokenizer.php';

use App\Controllers\Process\Tokenizer;

class Sse extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dtmodel');
    }

    private function getUserToken()
    {
        return $this->input->get('auth');
    }

    private function setReturn($code,$message,$data)
    {
        header('HTTP/1.0 ' . $code);
        header('Content-Type: application/json');
        $return = [
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        ];
        echo json_encode($return);
    }

    public function attendances()
    {
        ignore_user_abort(true);
        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        header("Access-Control-Allow-Origin: *");

        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {

            $count = 0;
            while(true){
    
                if(connection_aborted()){
                    exit();
                }
    
                else {
    
                     $Permit = $this->dtmodel->schema('permit');
                     $where = ['where'=>['UserId'=>$res[0]['Id'],'PermitType'=>1,'Start'=>date("Y-m-d")]];
                     $data = $Permit->findOne($where);
    
                    if(!$data){
                        echo "data: no data available please try again\n\n";  
                        exit();
                    } else {
                        if($data[0]->Status == 1){
                            echo "data:". '{"status":true}' ."\n\n"; 
                            exit();
                        } else if($data[0]->Status == 2){
                            echo "data:". '{"status":false}' ."\n\n"; 
                            exit();
                        } else {
                            if($count < 3){
                                echo "data: awaiting for approval please standby\n\n";
                                $count++;
                            } else {
                                echo "data: no data available please try again\n\n";
                                exit();
                            }
                             
                        }
                        
                    }
     
                    
                    ob_flush();
                    flush();
                    
    
                }
        
                // 5 second sleep then carry on
                sleep(5);
    
            }
            
        }

        
       
    }

}