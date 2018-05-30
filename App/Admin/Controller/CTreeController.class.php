<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Admin\Controller;
use Think\Controller;
use Model\CourseTreeModel;
use Model\StudentChoiceModel;
use Model\ManagerModel;

/**
 * Description of CTreeController
 *
 * @author Administrator
 */
class CTreeController extends Controller{
    var $CTreeM;
    var $stuChoiceM;
    var $managerM;
    function __construct() {
        parent::__construct();
        if(isset($_SESSION['manager'])&&$_SESSION['manager'] != ''){
            $this->CTreeM = new CourseTreeModel();
            $this->stuChoiceM = new StudentChoiceModel();
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
    /**
     * 获取Tree表单，然后输出json数据
     */
    function getTree(){
        $list = $this->CTreeM->field('ctr_NodeId,ctr_ParentId,ctr_Name AS name,ctr_Limit,ctr_Level,ctr_Synopsis')->select();
        for($i = 0;$i<count($list);$i++){
            if($list[$i]['ctr_Level'] == 3){
                $data = $this->CTreeM->field("ctr_NodeId")->where("ctr_ParentId = %d",$list[$i]['ctr_NodeId'])->select();
                if ($data){
                    $data1 = $this->stuChoiceM->field("COUNT(ch_StuNumber) AS count")->where("ch_TrNodeId = %d",$data[0]['ctr_NodeId'])->select();
                    $list[$i]['name'] = $list[$i]['name']." C:".$data1[0]['count'];
                }
            }
        }
        echo json_encode($list);
    }
    function addChild(){
        $map['ctr_ParentId'] = isset($_GET['pId']) ? $_GET['pId'] : NULL;
        $map['ctr_Level'] = isset($_GET['level']) ? $_GET['level']+1 : NULL;
        $map['ctr_Name'] = 'newNode';
        if ($map['ctr_ParentId'] == NULL || $map['ctr_Level'] == NULL){
            echo 0;
        } else {
            echo $this->CTreeM->data($map)->add();
        }
    }
    function removeChild(){
        $map['ctr_NodeId'] = isset($_GET['ctr_NodeId']) ? $_GET['ctr_NodeId'] :NULL;
        if($this->CTreeM->where($map)->delete()){
            //记得删除选了这门课的关系数据
            $this->stuChoiceM->where("ch_TrNodeId = %d",$map['ctr_NodeId'])->delete();
            echo 1;
        }else{
            echo 0;
        }
    }
    function saveInfo(){
        $map['ctr_NodeId'] = isset($_GET['ctr_NodeId'])?$_GET['ctr_NodeId'] : NULL;
        $data['ctr_Name'] = isset($_GET['ctr_Name'])?$_GET['ctr_Name'] : NULL;
        $data['ctr_Limit'] = isset($_GET['ctr_Limit'])?$_GET['ctr_Limit'] : NULL;
        $data['ctr_Synopsis'] = isset($_GET['ctr_Synopsis'])?$_GET['ctr_Synopsis'] : NULL;
        if($this->CTreeM->data($data)->where($map)->save()){
            echo 1;
        }else{
            echo 0;
        }
    }
    function setRule(){
        $map['mg_Rule'] = isset($_GET['ruleGrade'])?$_GET['ruleGrade'] : NULL;
        $data = $this->managerM->data($map)->where('mg_Id=1')->save();
        if ($data){
            echo 1;
        } else {
            echo 0;
        }
    }
    function logout(){
        session(NULL);
        $this->redirect('Login/');
    }
}
