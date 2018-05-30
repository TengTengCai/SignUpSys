<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Home\Controller;
use Think\Controller;
use Model\TrainingTreeModel;
use Model\StudentModel;
use Model\StudentTrainingModel;
use Model\ManagerModel;
/**
 * Description of PracticeController
 *
 * @author Administrator
 */
class PracticeController extends Controller{
    var $TTreeM;
    var $studentM;
    var $stuTrainingM;
    var $managerM;
    var $info;
    var $treeList = null;
    var $j = 0;
    //put your code here
    function __construct() {
        parent::__construct();
        if(isset($_SESSION['stu_Number'])&&$_SESSION['stu_Number'] != ''){
            $this->TTreeM = new TrainingTreeModel();
            $this->studentM = new StudentModel();
            $this->stuTrainingM = new StudentTrainingModel();
            $this->managerM = new ManagerModel();
            $this->info['stu_Number'] = $_SESSION['stu_Number'];
            $data = $this->studentM->field("stu_Name,stu_Grade,stu_Profession,stu_Class")
                    ->where("stu_Number = %d",  $this->info['stu_Number'])
                    ->select();
            $this->info['stu_Profession'] = $data[0]['stu_Profession'];
            $this->info['stu_Grade'] = $data[0]['stu_Grade'];
            $this->info['stu_Name'] = $data[0]['stu_Name']; 
            $this->info['stu_Class'] = $data[0]['stu_Class'];
        }else {
            $this ->redirect('Login/index');
        }
    }
    function index(){
        $list = $this->stuTrainingM->field("tra_TrNodeId AS ttr_NodeId")->where("tra_StuNumber = %d", $this->info['stu_Number'])->select();
        if ($list){
            $list['_logic'] = 'OR';
            $data = $this->TTreeM->field("ttr_NodeId,ttr_ParentId,ttr_Name,ttr_Limit,ttr_Synopsis")->where($list)->select();
            for($i = 0;$i<count($data);$i++){
                $data2 = $this->stuTrainingM->field("COUNT(tra_StuNumber) AS count")->where('tra_TrNodeId = %d',$data[$i]['ttr_NodeId'])->select();
                $table = $table."<tr><td>".$data[$i]['ttr_Name']."</td><td>".$data2[0]['count']."</td><td>".$data[$i]['ttr_Limit']."</td><td>".$data[$i]['ttr_Synopsis']."</td></tr>";
            }
        }
        $data3 = $this->managerM->field("mg_Rule")->where("mg_Name = '%s'",'admin')->select();
        $this->assign("table",  $table);
        $this->assign("rule_grade",$data3[0]['mg_Rule']);
        $this->assign("grade", $this->info['stu_Grade']);
        $this->assign("name",$this->info['stu_Name']);
        $this->assign("profession",$this->info['stu_Grade'].''.$this->info['stu_Profession'].''.$this->info['stu_Class'].'班');
        $this->display();
    }
    function getTTree() {
        $tree = $this->TTreeM;
        $list = $tree->field("ttr_NodeId,ttr_ParentId,ttr_Name AS name,ttr_Level AS level,ttr_Limit,ttr_Synopsis")
                ->where("ttr_Name = '%s'", $this->info['stu_Profession'])
                ->select();
        $list[0]['ttr_ParentId'] = 0;
        $this->treeList[$this->j++] = $list[0];
        $list = $tree->field("ttr_NodeId,ttr_ParentId,ttr_Name AS name,ttr_Level AS level,ttr_Limit,ttr_Synopsis")
                ->where("ttr_Name = '%s' AND ttr_ParentId = '%s' ", $this->info['stu_Grade'],$list[0]['ttr_NodeId'])
                ->select();
        $this->treeList[$this->j++] = $list[0];
        $data = $this->stuTrainingM->field('tra_TrNodeId AS ttr_NodeId')->where('tra_StuNumber = %d', $this->info['stu_Number'])->select();
        if ($data){
            $data['_logic'] = 'OR';
            $data = $tree->field('ttr_ParentId AS ttr_NodeId')->where($data)->select();
            $data = array_column($data, 'ttr_NodeId');
            $map['ttr_NodeId'] = array('not in',$data);
            $map['ttr_ParentId'] = $list[0]['ttr_NodeId'];
            $list = $tree->field("ttr_NodeId,ttr_ParentId,ttr_Name AS name,ttr_Level AS level,ttr_Limit,ttr_Synopsis")
                    ->where($map)
                    ->select();
            if($list){
                for ($i=0;$i<count($list);$i++){
                    $this->treeList[$this->j++] = $list[$i];
                    $this->queryTree($list[$i]['ttr_NodeId']);
                }
            }
        } else {
            $this->queryTree($list[0]['ttr_NodeId']);
        }
        
        echo json_encode($this->treeList);
    }
    function queryTree($id){
        $tree=$this->TTreeM;
        $list = $tree->field("ttr_NodeId,ttr_ParentId,ttr_Name AS name,ttr_Level AS level,ttr_Limit,ttr_Synopsis")
                ->where("ttr_ParentId = %d",$id)
                ->select();
        if($list){
            for ($i = 0;$i<count($list);$i++){
                $this->queryTree($list[$i]['ttr_NodeId']);
                $this->treeList[$this->j] = $list[$i];
                $this->j++;
            }
        }  else {
            return;
        }
    }
    function submitResult(){
        $isOK = FALSE;
        $ids = isset($_GET['ids']) ? $_GET['ids'] : NULL;
        $ids = explode(",",$ids);
        $ids = array_filter($ids);
//        dump($ids);
        for ($i = 0;$i<count($ids);$i++){
            $data = $this->stuTrainingM->field("COUNT(tra_StuNumber) AS count")->where("tra_TrNodeId = %d",$ids[$i])->select();
            $data1 = $this->TTreeM->field("ttr_Limit")->where("ttr_NodeId = %d",$ids[$i])->select();
            if ($data[0]['count']<$data1[0]['ttr_Limit']){
                if($ids[$i]){
                    $map['tra_TrNodeId'] = $ids[$i];
                    $map['tra_StuNumber'] = $_SESSION['stu_Number'];
                    $this->stuTrainingM->data($map)->add();
                    $isOK = TRUE;
                }else {
                    $isOK = FALSE;
                    break;
                }
            } else {
                $isOK = FALSE;
                break;
            }
            
        }
        if ($isOK){
            echo '1';
        }else {
            echo '0';
        }
        
    }
    function getCount(){
        $nodeId = isset($_GET['nodeId']) ? $_GET['nodeId'] : NULL;
        $data = $this->stuTrainingM->field('COUNT(tra_StuNumber) AS count')->where("tra_TrNodeId = %d",$nodeId)->select();
        echo $data[0]['count'];
    }
    function logout(){
        session(NULL);
        $this->redirect('Login/');
    }
}
