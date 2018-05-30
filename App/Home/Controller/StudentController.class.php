<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Home\Controller;
use Think\Controller;
use Model\CourseTreeModel;
use Model\StudentModel;
use Model\StudentChoiceModel;
use Model\ManagerModel;

/**
 * Description of StudentController
 *
 * @author Administrator
 */
class StudentController extends Controller {
    var $CTreeM;
    var $studentM;
    var $stuChoiceM;
    var $managerM;
    var $stu_Name;
    var $stu_Number;
    var $stu_Profession;
    var $stu_Grade;
    var $stu_Class;
    var $isChoice;
    var $treeList = null;
    var $j = 0;
    var $choice;
    
    function __construct() {
        parent::__construct();
        if(isset($_SESSION['stu_Number'])&&$_SESSION['stu_Number'] != ''){
            $this->CTreeM = new CourseTreeModel(); 
            $this->studentM = new StudentModel();
            $this->stuChoiceM = new StudentChoiceModel();
            $this->managerM = new ManagerModel();
            $this->stu_Number = $_SESSION['stu_Number'];
            $data = $this->studentM->field("stu_Name,stu_Grade,stu_Profession,stu_Class")
                    ->where("stu_Number = %d",  $this->stu_Number)
                    ->select();
            $this->stu_Profession = $data[0]['stu_Profession'];
            $this->stu_Grade = $data[0]['stu_Grade'];
            $this->stu_Name = $data[0]['stu_Name'];
            $this->stu_Class = $data[0]['stu_Class'];
            if ($this->stuChoiceM->field("*")->where("ch_StuNumber = %d",  $this->stu_Number)->select()){
                $this->isChoice = TRUE;
            }  else {
                $this->isChoice = FALSE;
            }
            $data1 = $this->managerM->field("mg_Rule")->where("mg_Name = '%s'",'admin')->select();
            if ($this->stu_Grade!= $data1[0]['mg_Rule']){
                $this ->redirect('Practice/index');
            }
        }else {
            $this ->redirect('Login/index');
        }
    }
     /**
     * 选择界面
     * @return null
     */
    function choice(){
        if($this->isChoice == TRUE){
            $this ->redirect('Student/choiced');
            return;
        }
        $student = $this->studentM;
        $data = $student->field("stu_Name,stu_Grade,stu_Profession,stu_Class")->where("stu_Number = %d",$this->stu_Number)->select();
        $this->assign("grade", $this->stu_Grade);
        $this->assign("name",$data[0]['stu_Name']);
        $this->assign("profession",$data[0]['stu_Grade'].''.$data[0]['stu_Profession'].''.$data[0]['stu_Class'].'班');
        $this->display();
    }
    function queryTree($id){
        $tree=$this->CTreeM;
        $list = $tree->field("ctr_NodeId,ctr_ParentId,ctr_Name AS name,ctr_Level AS level,ctr_Limit,ctr_Synopsis")
                ->where("ctr_ParentId = %d",$id)
                ->select();
        if($list){
            for ($i = 0;$i<count($list);$i++){
                $this->queryTree($list[$i]['ctr_NodeId']);
                $this->treeList[$this->j] = $list[$i];
                $this->j++;
            }
        }  else {
            return;
        }
    }
    
    function getCTree(){
        $tree = $this->CTreeM;
        $list = $tree->field("ctr_NodeId,ctr_ParentId,ctr_Name AS name,ctr_Level AS level,ctr_Limit,ctr_Synopsis")
                ->where("ctr_Name = '%s'", $this->stu_Profession)
                ->select();
        $list[0]['ctr_ParentId'] = 0;
        
        $this->treeList[$this->j++] = $list[0];
        $this->queryTree($list[0]['ctr_NodeId']);
        echo json_encode($this->treeList);
    }
    
    function getCount(){
        $nodeId = isset($_GET['nodeId']) ? $_GET['nodeId'] : NULL;
        $data = $this->stuChoiceM->field('COUNT(ch_StuNumber) AS count')->where("ch_TrNodeId = %d",$nodeId)->select();
        echo $data[0]['count'];
    }
    
    function result(){
        if($this->isChoice == TRUE){
            $this ->redirect('Student/choiced');
            return;
        }
        $stuData = $this->stu_Name." ". $this->stu_Number." ". $this->stu_Profession."".$this->stu_Class."班";
        $pId = isset($_GET['pId']) ? $_GET['pId'] : NULL;
        $id = isset($_GET['id']) ? $_GET['id'] : NULL;
        $pIds = explode(',', $pId);
        $ids = explode(',', $id);
        for ($i = 0;$i<count($pIds);$i++){$map1[$i]['ctr_NodeId'] = $pIds[$i];}
        for($i = 0;$i<count($ids);$i++){$map2[$i]['ctr_NodeId'] = $ids[$i];}
        $map1['_logic'] ='OR';
        $map2['_logic'] ='OR';
        $data1 = $this->CTreeM->field("ctr_Name,ctr_Limit,ctr_Synopsis")->where($map1)->select();
        for($i = 0 ;$i <count($data1);$i++){
            $str = $str."<dt>名称：</dt><dd>".$data1[$i]['ctr_Name']."</dd><dt>人数限制：</dt><dd>".$data1[$i]['ctr_Limit']."</dd><dt>描述：</dt><dd>".$data1[$i]['ctr_Synopsis']."</dd><br/>";
		}
        $data2 = $this->CTreeM->field("ctr_NodeId,ctr_ParentId,ctr_Name,ctr_Synopsis")->where($map2)->select();
        for($i = 0;$i<count($data2);$i++){
            $data3 = $this->stuChoiceM->field("COUNT(ch_StuNumber) AS count")->where('ch_TrNodeId = %d',$data2[$i]['ctr_NodeId'])->select();
            $data4 = $this->CTreeM->field("ctr_Limit")->where("ctr_NodeId = %d",$data2[$i]['ctr_ParentId'])->select();
            $table = $table."<tr><td>".$data2[$i]['ctr_Name']."</td><td>".$data3[0]['count']."</td><td>".$data4[0]['ctr_Limit']."</td><td>".$data2[$i]['ctr_Synopsis']."</td></tr>";
            if($data3[0]['count'] == $data4[0]['ctr_Limit'] || $data3[0]['count'] > $data4[0]['ctr_Limit']){
                $this->assign("error",TRUE);
            }
        }
        $this->assign("stuData",$stuData);
        $this->assign("msg",$str);
        $this->assign("table",$table);
        $this->assign("ids",$id);
        $this->display();
    }
    function submitResult(){
        if($this->isChoice == TRUE){
            $this ->redirect('Student/choiced');
            return;
        }
        $ids = isset($_GET['ids']) ? $_GET['ids'] : NULL;
        if ($ids){
            $ary = explode(',', $ids);
            for($i = 0;$i<count($ary);$i++){
                $map[$i]['ch_TrNodeId'] = $ary[$i];
                $map[$i]['ch_StuNumber'] = $_SESSION['stu_Number'];
                $map[$i]['ch_stuGrade'] = '2016';
            }
			dump($map);
            for($i = 0;$i<count($map);$i++){
                $this->stuChoiceM->data($map[$i])->add();
            }
            echo 1;
        }else{
            echo 0;
        }
    }
	function test(){
		echo $this->stu_Grade;
	}
    function choiced(){
        if($this->isChoice == FALSE){
            $this->redirect('Student/choice');
            return;
        }
        $SNumber = $_SESSION['stu_Number'];
        $list = $this->studentM->field("stu_Name,stu_Grade,stu_Profession,stu_Class")->where("stu_Number = %d",$SNumber)->select();
        $list2 = $this->stuChoiceM->field("ch_TrNodeId AS ctr_NodeId")->where("ch_StuNumber = %d",$SNumber)->select();
        $list2['_logic'] = 'OR';
        $data = $this->CTreeM->field("ctr_NodeId,ctr_ParentId,ctr_Name,ctr_Synopsis")->where($list2)->select();
        for($i = 0;$i<count($data);$i++){
            $data3 = $this->stuChoiceM->field("COUNT(ch_StuNumber) AS count")->where('ch_TrNodeId = %d',$data[$i]['ctr_NodeId'])->select();
            $data4 = $this->CTreeM->field("ctr_Limit")->where("ctr_NodeId = %d",$data[$i]['ctr_ParentId'])->select();
            $table = $table."<tr><td>".$data[$i]['ctr_Name']."</td><td>".$data3[0]['count']."</td><td>".$data4[0]['ctr_Limit']."</td><td>".$data[$i]['ctr_Synopsis']."</td></tr>";
        }
        $this->assign("table",  $table);
        $this->assign("name",$list[0]['stu_Name']);
        $this->assign("profession",$list[0]['stu_Grade'].''.$list[0]['stu_Profession'].''.$list[0]['stu_Class'].'班');
        $this->display();
    }
    function logout(){
        session(NULL);
        $this->redirect('Login/');
    }
}