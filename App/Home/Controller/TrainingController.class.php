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
/**
 * Description of TrainingController
 *
 * @author Administrator
 */
class TrainingController extends Controller {
    var $TTreeM;
    var $studentM;
    var $stuTrainingM;
    var $stu_Name;
    var $stu_Number;
    var $stu_Grade;
    var $stu_Profession;
    var $stu_Class;
    var $isChoice;
    var $treeList = null;
    var $j = 0;
    function __construct() {
        parent::__construct();
        if(isset($_SESSION['stu_Number'])&&$_SESSION['stu_Number'] != ''){
            $this->TTreeM = new TrainingTreeModel();
            $this->studentM = new StudentModel();
            $this->stuTrainingM = new StudentTrainingModel();
            $this->stu_Number = $_SESSION['stu_Number'];
            $data = $this->studentM->field("stu_Name,stu_Grade,stu_Profession,stu_Class")
                    ->where("stu_Number = %d",  $this->stu_Number)
                    ->select();
            $this->stu_Profession = $data[0]['stu_Profession'];
            $this->stu_Grade = $data[0]['stu_Grade'];
            $this->stu_Name = $data[0]['stu_Name']; 
            $this->stu_Class = $data[0]['stu_Class'];
            if ($this->stuTrainingM->field("*")->where("tra_StuNumber = %d",  $this->stu_Number)->select()){
                $this->isChoice = TRUE;
            }  else {
                $this->isChoice = FALSE;
            }
        }  else {
            $this ->redirect('Login/index');
        }
    }
    /**
     * 选择界面
     * @return null
     */
    function choice(){
        if($this->isChoice == TRUE){
            $this ->redirect('Training/choiced');
            return;
        }
        $student = $this->studentM;
        $data = $student->field("stu_Name,stu_Grade,stu_Profession,stu_Class")->where("stu_Number = %d",$this->stu_Number)->select();
        $this->assign("grade", $this->stu_Grade);
        $this->assign("name",$data[0]['stu_Name']);
        $this->assign("profession",$data[0]['stu_Grade'].''.$data[0]['stu_Profession'].''.$data[0]['stu_Class'].'班');
        $this->display();
    }
    
    function getTTree(){
        $tree = $this->TTreeM;
        $list = $tree->field("ttr_NodeId,ttr_ParentId,ttr_Name AS name,ttr_Level AS level,ttr_Limit,ttr_Synopsis")
                ->where("ttr_Name = '%s'", $this->stu_Profession)
                ->select();
        $list[0]['ttr_ParentId'] = 0;
        $this->treeList[$this->j++] = $list[0];
//        dump($list);
        $list1 =$tree->field("ttr_NodeId,ttr_ParentId,ttr_Name AS name,ttr_Level AS level,ttr_Limit,ttr_Synopsis")
                ->where("ttr_Name = '%s' AND ttr_ParentId = '%s' ", $this->stu_Grade,$list[0]['ttr_NodeId'])
                ->select();
//        dump($list1);
        $this->treeList[$this->j++] = $list1[0];
        if ($list1){
            $this->queryTree($list1[0]['ttr_NodeId']);
        } 
        echo json_encode($this->treeList);
        
//        $this->treeList[$this->j++] = $list1[0];
//        $this->queryTree($list1[0]['ttr_NodeId']);
//        echo json_encode($this->treeList);
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
        if($this->isChoice == TRUE){
            $this ->redirect('Training/choiced');
            return;
        }
        $id = isset($_GET['id']) ? $_GET['id'] : NULL;
        $data = $this->stuTrainingM->field("COUNT(tra_StuNumber) AS count")->where("tra_TrNodeId = %d",$id)->select();
        $data1 = $this->TTreeM->field("ttr_Limit")->where("ttr_NodeId = %d",$id)->select();
        if ($data[0]['count']<$data1[0]['ttr_Limit']){
            if ($id){
                $map['tra_TrNodeId'] = $id;
                $map['tra_StuNumber'] = $_SESSION['stu_Number'];
                $this->stuTrainingM->data($map)->add();
                echo 1;
            }else{
                echo 0;
            }
        } else {
            echo 0;
        }
    }
    function choiced(){
        if($this->isChoice == FALSE){
            $this->redirect('Training/choice');
            return;
        }
        $SNumber = $_SESSION['stu_Number'];
        $list = $this->studentM->field("stu_Name,stu_Grade,stu_Profession,stu_Class")->where("stu_Number = %d",$SNumber)->select();
        $list2 = $this->stuTrainingM->field("tra_TrNodeId AS ttr_NodeId")->where("tra_StuNumber = %d",$SNumber)->select();
        $list2['_logic'] = 'OR';
        $data = $this->TTreeM->field("ttr_NodeId,ttr_ParentId,ttr_Name,ttr_Limit,ttr_Synopsis")->where($list2)->select();
//        dump($data);
        for($i = 0;$i<count($data);$i++){
            $data3 = $this->stuTrainingM->field("COUNT(tra_StuNumber) AS count")->where('tra_TrNodeId = %d',$data[$i]['ttr_NodeId'])->select();
            $table = $table."<tr><td>".$data[$i]['ttr_Name']."</td><td>".$data3[0]['count']."</td><td>".$data[$i]['ttr_Limit']."</td><td>".$data[$i]['ttr_Synopsis']."</td></tr>";
        }
        $this->assign("table",  $table);
        $this->assign("name",$list[0]['stu_Name']);
        $this->assign("grade", $this->stu_Grade);
        $this->assign("profession",$list[0]['stu_Grade'].''.$list[0]['stu_Profession'].''.$list[0]['stu_Class'].'班');
        $this->display();
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
