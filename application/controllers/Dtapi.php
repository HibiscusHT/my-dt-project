<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'process/Tokenizer.php';
require 'process/Geolocation.php';

use App\Controllers\Process\Tokenizer;
use App\Controllers\Process\Geolocation;

class Dtapi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dtmodel');
    }

    private function getUserToken()
    {
        $header = getallheaders();
        $hdr = isset($header['Authorization']) ? $header['Authorization'] : $header['authorization'];
        return $hdr;
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

    public function addmember()
    {
        $Users = $this->dtmodel->schema('users');
        $post = $this->input->post();
        $where = ['where'=>['Email'=>$post['Email']]];
        foreach($post as $key => $row){
            if($key == "Password"){
                $post[$key] = Tokenizer::generatePasswordHash($row);
            }
        }
        if(!$Users->findOneOrNew($where)){
            $this->setReturn(400,'data exist',null);
        } else {
            if($_FILES['UserPhoto']['error'] == UPLOAD_ERR_NO_FILE){} 
            else {
                //$post["UserPhoto"] = $this->uploader("UserPhoto");
            }
            $Users->create($post);
            $this->setReturn(200,'successfully add member',null);
        }
    }

    public function loginmember()
    {
        $Users = $this->dtmodel->schema('users');
        $post = $this->input->post();
        $where = ['where'=>['Email'=>$post['Email']]];
        $res = $Users->findOne($where);
        if(!$res){
            $this->setReturn(400,'data not found',null);
        } else {
            if(Tokenizer::validatePasswordHash($post['Password'],$res[0]->Password)){
                $token = Tokenizer::generate($res);
                /* user role */
                    $auth = Tokenizer::validate($token);
                    $where = ['join'=>[
                        [
                        'key'=>'user_type',
                        'on'=>'user_type.Id = users.UserType'
                        ]
                        ],'where'=>[
                            'users.Id'=>$auth[0]['Id']
                    ]];
                    $Users = $this->dtmodel->schema('users');
                    $dt = $Users->findOne($where);
                /* user role */
                $this->setReturn(200,'success',['Token'=>$token,'UserRole'=>$dt[0]->UserType]);
            } else {
                $this->setReturn(412,'whoops, wrong password',null);
            }
           
        }
    }

   /* public function login()
    {
        $Users = $this->dtmodel->schema('users');
        $data = [
            'Username'=>'loremipsum3'
        ];
        $where = ['where'=>$data];
        $res = $Users->findOne($where);
        if(!$res){
            $this->setReturn(400,'data not found',null);
        } else {
            if(Tokenizer::validatePasswordHash('abcde',$res[0]->Password)){
                $token = Tokenizer::generate($res);
                $this->setReturn(200,'success',['Token'=>$token]);
            } else {
                $this->setReturn(412,'whoops, wrong password',null);
            }
           
        }
    } */

    public function getprofile()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $this->setReturn(200,'success',$res);
        }
        
    }

    public function setroleuser()
    {
        $post = $this->input->post();
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
             $Role = $this->dtmodel->schema('user_role');
             $where = ['where'=>['UserId'=>$post['UserId'],'ParentUserId'=>$post['ParentUserId']]];
             if(!$Role->findOneOrNew($where)){
                $this->setReturn(400,'data exist',null);
            } else { 
                $Role->create($post);
                $this->setReturn(200,'successfully set user role',null);
            }
        }
    }

    public function getapprovalofficer()
    {
        $post = $this->input->post();
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
             $Role = $this->dtmodel->schema('user_role');
             $where = ['where'=>['UserId'=>$res[0]['Id']]];
             $roles = $Role->findOne($where);
             if(!$roles){
               $this->setReturn(400,'data not found',null);
             } else {
               $Users = $this->dtmodel->schema('users');
               $rest = $Users->findOne(['attribute'=>'Id,RealName','where'=>['Id'=>$roles[0]->ParentUserId]]);
               $this->setReturn(200,'success',$rest);
             }
        }
    }

    public function getallusers()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Users = $this->dtmodel->schema('users');
            $name = $this->input->get('q');
            $where = ['like'=>['RealName'=>$name],'where'=>['UserType'=>3]];
            $res = $Users->findAll($where);
            if(!$res){
                $this->setReturn(400,'data not found',[]);
            } else {
                $this->setReturn(200,'success',$res);
            }
            
        }
    }

    public function dailyreportsend()
    {
        $post = $this->input->post();
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else { 
             if($_FILES['ActivityPhoto']['error'] == UPLOAD_ERR_NO_FILE){} else {
                //upload file
             }
             $post['UserId'] = $res[0]['Id'];
             $Report = $this->dtmodel->schema('daily_report');
             $Report->create($post);
             $this->setReturn(200,'successfully send report',[]);
        } 
    }

    public function dailyreporthist()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Report = $this->dtmodel->schema('daily_report');
            $res = $Report->findAll(['where'=>['UserId'=>$res[0]['Id']]]);
            if(!$res){
                $this->setReturn(400,'data not found',[]);
            }  else {
                $this->setReturn(200,'success',$res);
            }
           
         }
    }

    public function getdailyreport()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else { 
            $id = $this->input->get("id");
            $Report = $this->dtmodel->schema('daily_report');
            $res = $Report->findOne(['where'=>['Id'=>$id]]);
            if(!$res){
                $this->setReturn(400,'data not found',[]);
            } else {
                $this->setReturn(200,'success',$res);
            }
            
        }
    }

    public function dailyreportupdate()
    {
        $post = $this->input->post();
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else { 
             if($_FILES['ActivityPhoto']['error'] == UPLOAD_ERR_NO_FILE){} else {
                //upload file
             }
             $id = $this->input->get("id");
             $post['UserId'] = $res[0]['Id'];
             $Report = $this->dtmodel->schema('daily_report');
             $where = ['where'=>['Id'=>$id]];
             $res = $Report->findOne($where);
             if(!$res){
                 $this->setReturn(400,'data not found',null);
             } else {
                 $Report->update($post);
                 $this->setReturn(200,'success',[]);
            } 
        } 
    }

    public function getdailyreportapplied()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Report = $this->dtmodel->schema('daily_report');
            $where = ['where'=>['ApprovalUserId'=>$res[0]['Id'],'ReportStatus'=>1]];
            $res = $Report->findAll($where);
            if(!$res){
                $this->setReturn(400,'data not found',[]);
            } else {
                $this->setReturn(200,'success',$res);
            }
            
         }
    }

    public function approvedailyreportapplied()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else { 
            $id = $this->input->get("id");
            $Report = $this->dtmodel->schema('daily_report');
            $where = ['where'=>['Id'=>$id]];
             $res = $Report->findOne($where);
             if(!$res){
                 $this->setReturn(400,'data not found',null);
             } else {
                 $Report->update(['ReportStatus'=>2]);
                 $this->setReturn(200,'success',[]);
            } 
        }
    }

    public function memberattendance()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $post = $this->input->post();
            $post['UserId'] = $res[0]['Id'];
            $post['AttendanceDate'] = date('Y-m-d');
            $Absent = $this->dtmodel->schema('daily_attendance');
            $Absent->create($post); 
            $this->setReturn(200,'success',[]);
         }
    }

    public function attendancehist()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Absent = $this->dtmodel->schema('daily_attendance');
            $start = $this->input->get('start_date');
            $end = $this->input->get('end_date');
            $where = ['where'=>['UserId'=>$res[0]['Id']],'group'=>'AttendanceDate'];
            if($start != '' && $end != ''){
                $where = ['where'=>['UserId'=>$res[0]['Id'],'AttendanceDate <='=>$end,'AttendanceDate >='=>$start],'group'=>'AttendanceDate'];
            }
            
            $res = $Absent->findAll($where);
            if(!$res){
                $this->setReturn(400,'data not found',[]);
            } else {
                $datum = []; 
                 foreach($res as $row){  
                        $in = [];
                        $out = [];
                        $data = [];
                        $where = ['where'=>['AttendanceDate'=>$row->AttendanceDate]];
                        $rest = $Absent->findAll($where);
                        foreach($rest as $rows){
                            if($rows->AttendanceType == 0){
                                $in = [
                                    'WorkingType'=>$rows->WorkingType,
                                    'AttendanceTime'=>$rows->AttendanceTime
                                ];
                                $data['OfficeIn'] = $in; 
                            } else {
                                $out = [
                                    'WorkingType'=>$rows->WorkingType,
                                    'AttendanceTime'=>$rows->AttendanceTime
                                ];
                                $data['OfficeOut'] = $out; 
                            }
                        }
                        $data['AttendanceDate'] = $row->AttendanceDate;
                        $data['Latitude'] = $row->Latitude;
                        $data['Longitude'] = $row->Longitude;
                        array_push($datum,$data);
                }
                  
                $this->setReturn(200,'success',$datum);
            }
            
         }
    }

    public function announce()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $action = $this->input->get('action');
            $Announce = $this->dtmodel->schema('announcements');
            $post = $this->input->post();
            switch($action){
                case 'add': 
                    if($_FILES['Attachment']['error'] == UPLOAD_ERR_NO_FILE){}
                    else {
                        //upload file
                    }
                    $post['UserId'] = $res[0]['Id'];
                    $Announce->create($post);
                    $this->setReturn(200,'success',[]);
                    break; 
                case 'update': break;
                case 'delete': break;
            }
        }
    }

    public function getallannounce()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Announce = $this->dtmodel->schema('announcements');
            $res = $Announce->findAll([]);
            if(!$res){
                $this->setReturn(400,'data not found',[]);
            } else {
                $this->setReturn(200,'success',$res);
            }
            
         }
    }

    public function getannounce()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $id = $this->input->get("id");
            $Announce = $this->dtmodel->schema('announcements');
            $where = ['where'=>['Id'=>$id]];
            $res = $Announce->findOne($where);
            if(!$res){
                $this->setReturn(400,'data not found',null);
            } else {
                 $this->setReturn(200,'success',$res);
           } 
         }
    }

    public function setdonation()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Donation = $this->dtmodel->schema('donations');
            $post = $this->input->post(); 
            $post['UserId'] = $res[0]['Id'];
            $post['TrxId'] = 'DT-Donasi-' . date('Ymdhis');
                $Donation->create($post);
                $this->setReturn(200,'successfully add donation',null); 
         }
    }

    public function donationhist()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Donator = $this->dtmodel->schema('donations'); 
            $where = ['where'=>['UserId'=>$res[0]['Id']]];
            $res = $Donator->findAll($where); 
            if(!$res){
                $this->setReturn(400,'data not found',[]); 
            } else {
                $this->setReturn(200,'success',$res); 
            }
            
         }
    }

    public function adddonator()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Donator = $this->dtmodel->schema('donator');
            $post = $this->input->post();
            $Donator->create($post);
            $this->setReturn(200,'success',[]);
        }
    }

    public function getalldonator()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Donator = $this->dtmodel->schema('donator'); 
            $res = $Donator->findAll([]); 
            if(!$res){
                $this->setReturn(400,'data not found',[]); 
            } else {
                foreach($res as $row){
                    $tag = explode(',',$row->Tag);
                    $details = array_map(function($el){
                        return ['Title'=>$el,'Link'=>'https://www.dt-erp.com/' . $el];
                    },$tag);
                    $row->Tag = $details;
                }
                $this->setReturn(200,'success',$res); 
            }
            
         }
    }

    public function getdonator()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Donator = $this->dtmodel->schema('donator');
            $id = $this->input->get("id");
            $where = ['attribute'=>'Id,DonatorName,Phone','where'=>['Id'=>$id]];
            $res = $Donator->findOne($where);
            if(!$res){
                $this->setReturn(400,'data not found',null);
            } else {
                 $this->setReturn(200,'success',$res);
           } 
         }
    }

    public function addmaterial()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Material = $this->dtmodel->schema('material_group');
            $post = $this->input->post();
            $where = ['where'=>['GroupName'=>$post['GroupName']]];
            if(!$Material->findOneOrNew($where)){
                $this->setReturn(400,'data exist',null);
            } else {
                $Material->create($post);
                $this->setReturn(200,'success',[]);
            }
         }
    }

    public function addsubmaterial()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Material = $this->dtmodel->schema('sub_material_group');
            $post = $this->input->post();
            $where = ['where'=>['SubName'=>$post['SubName']]];
            if(!$Material->findOneOrNew($where)){
                $this->setReturn(400,'data exist',null);
            } else {
                $Material->create($post);
                $this->setReturn(200,'success',[]);
            }
        }
    }

    public function getallmaterial()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Material = $this->dtmodel->schema('material_group'); 
            $term = $this->input->get('q');
            $where = ['like'=>['GroupName'=>$term]];
            $rest = $Material->findAll($where);
            if(!$rest){
                $this->setReturn(400,'data not found',[]);
            } else {
                $this->setReturn(200,'success',$rest);
            }
            
        }
    }

    public function getsubmaterial()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Material = $this->dtmodel->schema('sub_material_group'); 
            $id = $this->input->get('group');
            $term = $this->input->get('q');
            $where = ['where'=>['MaterialGroupId'=>$id],'like'=>['SubName'=>$term]];
            $rest = $Material->findOne($where);
            if(!$rest){
                $this->setReturn(400,'data not found',[]);
            } else {
                $this->setReturn(200,'success',$rest);
            }
           
        }
    }

    public function memberleave()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('leaves');  
            $post = $this->input->post();
            $post['UserId'] = $res[0]['Id'];
            $Leave->create($post);
            $this->setReturn(200,'success',[]);
        }
    }

    public function memberleavehist()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('leaves'); 
            $where = ['where'=>['UserId'=>$res[0]['Id']]];  
            $rest = $Leave->findAll($where);
            if(!$rest){
                $this->setReturn(400,'data not found',[]);
            } else {
                foreach($rest as $row){
                    $status = ['Diajukan','Disetujui','Ditolak'];
                    $row->Status = $status[$row->Status];
                }
                $this->setReturn(200,'success',$rest);
            }
            
        }
    }

    public function getmemberleave()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('leaves');   
            $rest = $Leave->findAll([]);
            if(!$rest){
                $this->setReturn(400,'data not found',[]);
            } else {
                foreach($rest as $row){
                    $status = ['Diajukan','Disetujui','Ditolak'];
                    $row->Status = $status[$row->Status];
                }
                $this->setReturn(200,'success',$rest);
            }
           
        }
    }

    public function processmemberleave()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('leaves');   
            $id = $this->input->get('id');
            $status = $this->input->get('status');
            $where = ['where'=>['Id'=>$id]];
            $rest = $Leave->findOne($where);
            if(!$rest){
                $this->setReturn(400,'data not found',null);
            } else {
                 $data = ['Status'=>$status];
                 $Leave->update($data);
                 $this->setReturn(200,'success',[]);
           } 
            
        }
    }

    public function memberpermit()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('permit');  
            $post = $this->input->post();
            if(!isset($post['DoctorNote'])){
            } else {
                if($_FILES['DoctorNote']['error'] == UPLOAD_ERR_NO_FILE){}
                else {
                    //upload keterangan dokter
                }
            }
            
            $post['UserId'] = $res[0]['Id']; 
            $Leave->create($post);
            $this->setReturn(200,'success',[]);
        }
    }    

     public function memberpermithist()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('permit'); 
            $where = ['where'=>['UserId'=>$res[0]['Id']]];  
            $rest = $Leave->findAll($where);
            if(!$rest){
                $this->setReturn(400,'data not found',[]);
            } else {
                foreach($rest as $row){
                    $status = ['Diajukan','Disetujui','Ditolak'];
                    $row->Status = $status[$row->Status];
                }
                $this->setReturn(200,'success',$rest);
            }
            
        }
    }

    public function getmemberpermit()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('permit');   
            $rest = $Leave->findAll([]);
            if(!$rest){
                $this->setReturn(400,'data not found',[]);
            } else {
                foreach($rest as $row){
                    $status = ['Diajukan','Disetujui','Ditolak'];
                    $row->Status = $status[$row->Status];
                }
                $this->setReturn(200,'success',$rest);
            }            
        }
    }

    public function processmemberpermit()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('permit');   
            $id = $this->input->get('id');
            $status = $this->input->get('status');
            $where = ['where'=>['Id'=>$id]];
            $rest = $Leave->findOne($where);
            if(!$rest){
                $this->setReturn(400,'data not found',null);
            } else {
                 $data = ['Status'=>$status];
                 $Leave->update($data);
                 $this->setReturn(200,'success',[]);
           } 
            
        }
    }

    public function memberovertime()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('overtime');  
            $post = $this->input->post();
            if($_FILES['Clockify']['error'] == UPLOAD_ERR_NO_FILE){}
            else {
                //upload keterangan dokter
            }
            $post['UserId'] = $res[0]['Id'];
            $Leave->create($post);
            $this->setReturn(200,'success',[]);
        }
    }    

     public function memberovertimehist()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('overtime'); 
            $where = ['where'=>['UserId'=>$res[0]['Id']]];  
            $rest = $Leave->findAll($where);
            if(!$rest){
                $this->setReturn(400,'no data found',[]);
            } else {
                foreach($rest as $row){
                    $status = ['Diajukan','Disetujui','Ditolak'];
                    $row->Status = $status[$row->Status];
                }
                $this->setReturn(200,'no data found',$rest);
            }
        }
    }

    public function getmemberovertime()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('overtime');   
            $rest = $Leave->findAll([]);
            if(!$rest){
                $this->setReturn(400,'data not found',[]);
            } else {
                foreach($rest as $row){
                    $status = ['Diajukan','Disetujui','Ditolak'];
                    $row->Status = $status[$row->Status];
                }
                $this->setReturn(200,'success',$rest);
            }
           
        }
    }

    public function processmemberovertime()
    {
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Leave = $this->dtmodel->schema('overtime');   
            $id = $this->input->get('id');
            $status = $this->input->get('status');
            $where = ['where'=>['Id'=>$id]];
            $rest = $Leave->findOne($where);
            if(!$rest){
                $this->setReturn(400,'data not found',null);
            } else {
                 $data = ['Status'=>$status];
                 $Leave->update($data);
                 $this->setReturn(200,'success',[]);
           } 
            
        }
    }
 
    public function getworship()
    {
          $worship_val = ['shalat_subuh_berjamaah'=>false,'shalat_dzuhur_berjamaah'=>false,'shalat_ashar_berjamaah'=>false,'shalat_maghrib_berjamaah'=>false,'shalat_isya_berjamaah'=>false,'rawatib'=>0,'qiyamullail'=>0,'dhuha'=>0,'tilawah_1_juz_Sehari'=>false,'juz'=>0,'baca_terjemah'=>false,'tasmi'=>false,'murajaah'=>false,'shadaqah_mal'=>false,'shaum_senin_kamis'=>false,'shaum_daud_shaum_sunnah_lainnya'=>false,'dzikir_pagi'=>false,'dzikir_petang'=>false,'istighfar'=>0,'shalawat'=>0,'menyimak_mq_pagi'=>false,'menyimak_kajian_alhikam'=>false,'menyimak_kajian_kamis_malam'=>false,'menyimak_talim_kajian_keislaman'=>false];
        $worship_cat = ['shalat'=>['shalat_subuh_berjamaah','shalat_dzuhur_berjamaah','shalat_ashar_berjamaah','shalat_maghrib_berjamaah','shalat_isya_berjamaah','rawatib','qiyamullail','dhuha'],'al_quran'=>['tilawah_1_juz_Sehari','juz','baca_terjemah','tasmi','murajaah'],'shadaqah_mal'=>['shadaqah_mal'],'shaum'=>['shaum_senin_kamis','shaum_daud_shaum_sunnah_lainnya'],'dzikir'=>['dzikir_pagi','dzikir_petang','istighfar','shalawat'],'ilmu'=>['menyimak_mq_pagi','menyimak_kajian_alhikam','menyimak_kajian_kamis_malam','menyimak_talim_kajian_keislaman']];
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $Worship = $this->dtmodel->schema('daily_worship');
            $tgl = $this->input->get('tgl') == ''  || is_null($this->input->get('tgl'))? date('Y-m-d') : $this->input->get('tgl');
           
            $data = [];
            $title = ['shaum_daud_shaum_sunnah_lainnya','menyimak_talim_kajian_keislaman','menyimak_mq_pagi','menyimak_kajian_alhikam'];
            $titles = ['shaum_daud_shaum_sunnah_lainnya'=>'Shaum Daud/Shaum Sunnah Lainnya','menyimak_talim_kajian_keislaman'=>'Menyimak Ta\'lim/Kajian Keislaman','menyimak_mq_pagi'=>'Menyimak MQ Pagi','menyimak_kajian_alhikam'=>'Menyimak Kajian Al-Hikam'];
            foreach($worship_cat as $val => $k){
                $datas = [];
                foreach($k as $m){
                    $datum = [
                        'Title'=>(in_array($m,$title) ? $titles[$m] : ucwords(str_replace('_',' ',$m))),
                        'WorshipCategory'=>$val,
                        'WorshipType'=>$m,
                        'WorshipTypeValue'=>$worship_val[$m]
                    ];
                    $where = ['where'=>['UserId'=>$res[0]['Id'],'Created'=>$tgl,'WorshipCategory'=>$val,'WorshipType'=>$m]];
                    $rest = $Worship->findOne($where); 
                     if($rest){
                       foreach($rest as $row){ 
                            if(isset($row->WorshipCategory) && $row->WorshipCategory == $val){
                                $datum = [
                                    'Title'=>(in_array($m,$title) ? $titles[$m] : ucwords(str_replace('_',' ',$m))),
                                    'WorshipCategory'=>$val,
                                    'WorshipType'=>$m,
                                    'WorshipTypeValue'=>$row->WorshipTypeValue
                                ];
                                break;
                            }
                             
                        } 
                    }
                    
                     $datas = $datum;
                     array_push($data,$datas);
                }
            }

            $this->setReturn(200,'success',$data);

         }
    }

    public function setworship()
    {
         
        $auth = $this->getUserToken();
        $res = Tokenizer::validate($auth);
        if($res == 0){
            $this->setReturn(403,'whoops, your token has expired',[]);
        } else if($res == 1){
            $this->setReturn(400,'whoops, invalid request',[]);
        } else {
            $post = $this->input->post();
            $post['UserId'] = $res[0]['Id'];
            $Worship = $this->dtmodel->schema('daily_worship');
            $where = ['where'=>[
                'Created'=>date("Y-m-d"),
                'WorshipCategory'=>$post['WorshipCategory'],
                'WorshipType'=>$post['WorshipType'],
                'UserId'=>$post['UserId'] 
                ]
            ];
            $dt = $Worship->findOne($where);
            if($dt){
                $Worship->findOne(['where'=>['Id'=>$dt[0]->Id]]);
                $Worship->update(['WorshipTypeValue'=>$post['WorshipTypeValue']]);
            } else {
                $Worship->create($post);
            }
            $this->setReturn(200,'success',[]);
         }
    }

    public function getmodules()
    {
        $parent = $this->input->get('parent');
        $Modules = $this->dtmodel->schema('modules');
        $where = ['where'=>['Parent'=>$parent,'AppType'=>2]];
        $res = $Modules->findAll($where);
        if(!$res){
            $this->setReturn(200,'success',[]);
        } else {
            $this->setReturn(200,'success',$res);
        }
        
    }

    public function loadtrans()
    {
        $Donation = $this->dtmodel->schema('donations');
        $res = $Donation->findAll([]);
        if(!$res){
            $this->setReturn(400,'no data',[]);
        } else {
            $this->setReturn(200,'success',$res);
        }
    }

    public function loadcust()
    {
        $Donation = $this->dtmodel->schema('donator');
        $res = $Donation->findAll([]);
        if(!$res){
            $this->setReturn(400,'no data',[]);
        } else {
            $this->setReturn(200,'success',$res);
        }
    }

    public function addprog()
    {
        $Prog = $this->dtmodel->schema('program');
        $post = $this->input->post();
        $Prog->create($post);
        $this->setReturn(200,'success',[]);
    }

    public function loadprog()
    {
        $Prog = $this->dtmodel->schema('program');
        $res = $Prog->findAll([]);
        if(!$res){
            $this->setReturn(400,'no data',[]);
        } else {
            $this->setReturn(200,'success',$res);
        }
    }

    public function getprog()
    {
        $Prog = $this->dtmodel->schema('program');
        $q = $this->input->get('q');
        $pilar = $this->input->get('pilar');
        $where = ['where'=>[],'like'=>['ProgramName'=>$q]];
        $res = $Prog->findAll([]);
        if(!$res){
            $this->setReturn(400,'no data',[]);
        } else {
            $this->setReturn(200,'success',$res);
        }
    }

    public function addsubprog()
    {
        $Prog = $this->dtmodel->schema('sub_program');
        $post = $this->input->post();
        $Prog->create($post);
        $this->setReturn(200,'success',[]);
    }

    public function loadsubprog()
    {
        $Prog = $this->dtmodel->schema('sub_program');
        $res = $Prog->findAll([]);
        if(!$res){
            $this->setReturn(400,'no data',[]);
        } else {
            $this->setReturn(200,'success',$res);
        }
    }



 /*   public function add()
    {
        $Users = $this->dtmodel->schema('users');

        $data = [
            'Username'=>'loremipsum3',
            'Password'=>Tokenizer::generatePasswordHash('abcde')
        ];
        $where = ['where'=>['Username'=>'loremipsum3']];
        if(!$Users->findOneOrNew($where)){
            $this->setReturn(400,'data exist',null);
        } else {
            $Users->create($data);
            $this->setReturn(200,'success',$data);
        }
        
    }

    public function getdata()
    {
        $Users = $this->dtmodel->schema('users');
        $where = ['attribute'=>'Username,Created','where'=>['Username'=>'loremipsum3']];
        $res = $Users->findOne($where);
        if(!$res){
            $this->setReturn(400,'data not found',null);
        } else {
            $this->setReturn(200,'success',$res);
        }
    }

    public function getalldata()
    {
        $Users = $this->dtmodel->schema('users');
        $where = ['attribute'=>'Username,Created','order'=>'Created DESC'];
        $res = $Users->findAll($where);
        if(!$res){
            $this->setReturn(400,'data not found',null);
        } else {
            $this->setReturn(200,'success',$res);
        }
    }

    public function updatedata()
    {
        $Users = $this->dtmodel->schema('users');
        $where = ['where'=>['Username'=>'loremipsum2','Id'=>2]];
        $res = $Users->findOne($where);
        if(!$res){
            $this->setReturn(400,'data not found',null);
        } else {
            $Users->update(['RealName'=>'Astomo Oke']);
            $this->setReturn(200,'success',[]);
        }
    }

    public function removedata()
    {
        $Users = $this->dtmodel->schema('users');
        $where = ['where'=>['Username'=>'loremipsum2','Id'=>2]];
        $res = $Users->findOne($where);
        if(!$res){
            $this->setReturn(400,'data not found',null);
        } else {
            $Users->remove();
            $this->setReturn(200,'success',[]);
        }
    } 
    */
}