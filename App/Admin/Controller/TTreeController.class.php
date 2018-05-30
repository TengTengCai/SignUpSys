<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Admin\Controller;
use Think\Controller;
use Model\TrainingTreeModel;
use Model\StudentTrainingModel;
use Model\ManagerModel;

/**
 * Description of TTreeController
 *
 * @author Administrator
 */
class TTreeController extends Controller {
    var $TTreeM;
    var $stuTrainingM;
    var $managerM;
    function __construct() {
        parent::__construct();
        if(isset($_SESSION['manager'])&&$_SESSION['manager'] != ''){
            $this->TTreeM = new TrainingTreeModel();
            $this->stuTrainingM = new StudentTrainingModel();
            $this->managerM = new ManagerModel();
        }  else {
            $this ->redirect('Login/index');
        }
    }
    function index(){
        $this->assign("manager",$_SESSION['manager']);
        $data = $this->managerM->field("mg_Rule")->where("mg_Name = '%s'",$_SESSION['manager'])->select();
        $this->assign("rule_grade",$data[0]['mg_Rule']);
        $this->display();
    }
    function getTree(){
        $list = $this->TTreeM->field('ttr_NodeId,ttr_ParentId,ttr_Name AS name,ttr_Limit,ttr_Level,ttr_Synopsis')->select();
        for($i = 0;$i<count($list);$i++){
            if($list[$i]['ttr_Level'] == 4){
            $count = $this->stuTrainingM->field("COUNT(tra_StuNumber) AS count")->where('tra_TrNodeId = %d',$list[$i]['ttr_NodeId'])->select();
            $list[$i]['name'] = $list[$i]['name']." C:".$count[0]['count'];
            }
        }
        echo json_encode($list);
    }
    function addChild(){
        $map['ttr_ParentId'] = isset($_GET['pId']) ? $_GET['pId'] : NULL;
        $map['ttr_Level'] = isset($_GET['level']) ? $_GET['level']+1 : NULL;
        $map['ttr_Name'] = 'newNode';
        if ($map['ttr_ParentId'] == NULL || $map['ttr_Level'] == NULL){
            echo 0;
        } else {
            echo $this->TTreeM->data($map)->add();
        }
    }
    function removeChild(){
        $map['ttr_NodeId'] = isset($_GET['ttr_NodeId']) ? $_GET['ttr_NodeId'] :NULL;
        if($this->TTreeM->where($map)->delete()){
            //记得删除选了这门课的关系数据
            $this->stuTrainingM->where("tra_TrNodeId = %d",$map['ttr_NodeId'])->delete();
            echo 1;
        }else{
            echo 0;
        }
    }
    function saveInfo(){
        $map['ttr_NodeId'] = isset($_GET['ttr_NodeId'])?$_GET['ttr_NodeId'] : NULL;
        $data['ttr_Name'] = isset($_GET['ttr_Name'])?$_GET['ttr_Name'] : NULL;
        $data['ttr_Limit'] = isset($_GET['ttr_Limit'])?$_GET['ttr_Limit'] : NULL;
        $data['ttr_Synopsis'] = isset($_GET['ttr_Synopsis'])?$_GET['ttr_Synopsis'] : NULL;
        if($this->TTreeM->data($data)->where($map)->save()){
            echo 1;
        }else{
            echo 0;
        }
    }
    function logout(){
        session(NULL);
        $this->redirect('Login/');
    }
}
