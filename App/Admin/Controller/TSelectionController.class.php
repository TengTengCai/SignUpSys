<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Admin\Controller;
use Think\Controller;
use Model\StudentTrainingModel;
use Model\StudentModel;
use Model\TrainingTreeModel;
/**
 * Description of TSelectionController
 *
 * @author Administrator
 */
class TSelectionController extends Controller {
    var $TTreeM;
    var $stuTrainingM;
    var $studentM;
    function __construct() {
        parent::__construct();
        if(isset($_SESSION['manager'])&&$_SESSION['manager'] != ''){
            $this->TTreeM = new TrainingTreeModel();
            $this->stuTrainingM = new StudentTrainingModel();
            $this->studentM = new StudentModel();
        }  else {
            $this ->redirect('Login/index');
        }
    }
    function index(){
        $data = $this->TTreeM->field('ttr_Name')->where('ttr_Level = 1')->select();
        for ($i = 0;$i<count($data);$i++){
            $profession = $profession."<option>".$data[$i]['ttr_Name']."</option>";
        }
        $this->assign("profession",$profession);
        $this->assign("manager",$_SESSION['manager']);
        $this->display();
    }
    function getGrade(){
        $profession = isset($_GET['profession']) ? $_GET['profession'] : NULL;
        $data = $this->TTreeM->field("ttr_NodeId")->where("ttr_Name = '%s'",$profession)->select();
        $data1 = $this->TTreeM->field("ttr_Name")->where("ttr_ParentId = %d",$data[0]['ttr_NodeId'])->select();
        for($i = 0;$i<count($data1);$i++){
            $str = $str."<option>".$data1[$i]["ttr_Name"]."</option>";
        }
        echo $str;
    }
    function getCourse(){
        $profession = isset($_GET['profession']) ? $_GET['profession'] : NULL;
        $grade = isset($_GET['grade']) ? $_GET['grade'] : NULL;
        $time = isset($_GET['time']) ? $_GET['time'] : NULL;
        $data = $this->TTreeM->field("ttr_NodeId")->where("ttr_Name = '%s'",$profession)->select();
        $data1 = $this->TTreeM->field("ttr_NodeId")->where("ttr_ParentId = '%d' AND ttr_Name = '%s'",$data[0]['ttr_NodeId'],$grade)->select();
        $data2 = $this->TTreeM->field("ttr_NodeId")->where("ttr_ParentId = '%d' AND ttr_Name = '%s'",$data1[0]['ttr_NodeId'],$time)->select();
        $data3 = $this->TTreeM->field("ttr_NodeId,ttr_Name")->where("ttr_ParentId = '%d'",$data2[0]['ttr_NodeId'])->select();
        for($i = 0;$i<count($data3);$i++){
            $str = $str."<option id=\"".$data3[$i]['ttr_NodeId']."\">".$data3[$i]['ttr_Name']."</option>";
        }
        echo $str;
    }
    function getTime(){
        $profession = isset($_GET['profession']) ? $_GET['profession'] : NULL;
        $grade = isset($_GET['grade']) ? $_GET['grade'] : NULL;
        $data = $this->TTreeM->field("ttr_NodeId")->where("ttr_Name = '%s'",$profession)->select();
        $data1 = $this->TTreeM->field("ttr_NodeId")->where("ttr_ParentId = '%d' AND ttr_Name = '%s'",$data[0]['ttr_NodeId'],$grade)->select();
        $data2 = $this->TTreeM->field("ttr_Name")->where("ttr_ParentId = '%d'",$data1[0]['ttr_NodeId'])->select();
        for($i = 0;$i<count($data2);$i++){
            $str = $str."<option>".$data2[$i]['ttr_Name']."</option>";
        }
        echo $str;
    }
    function getInfo(){
        $map = isset($_GET['nodeId']) ? $_GET['nodeId'] : NULL;
        $data1 = $this->stuTrainingM->field("tra_StuNumber AS stu_Number")->where("tra_TrNodeId = %d",$map)->select();
        if ($data1){
            $data1['_logic'] = 'OR';
            $data2 = $this->studentM->field("stu_Number,stu_Sex,stu_Name,stu_Grade,stu_Profession,stu_Class")->where($data1)->select();
            F("data",$data2);
            echo json_encode($data2);
        } else {
            echo '';
        }
        
    }
    /**
     * 获取查询的数据表格
     */
    function getExcel(){
        $Data = F("data");
        if(!$Data){
            $this->error('请查询后再导出数据');
        }
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer");
        $fileName = date("YmdHis");//文件名
        $phpExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($phpExcel);
        $phpExcel->setActiveSheetIndex(0);
        $phpExcel->getActiveSheet()->setTitle('sheet1');
        $phpExcel->getActiveSheet()->setCellValue('A1', '学号');
        $phpExcel->getActiveSheet()->setCellValue('B1', '姓名');
        $phpExcel->getActiveSheet()->setCellValue('C1', '性别');
        $phpExcel->getActiveSheet()->setCellValue('D1', '年级');
        $phpExcel->getActiveSheet()->setCellValue('E1', '专业');
        $phpExcel->getActiveSheet()->setCellValue('F1', '班级');
        $k=2;
        for ($i=0;$i<count($Data);$i++){
            $phpExcel->getActiveSheet()->setCellValue('A'.$k, $Data[$i]['stu_Number']);
            $phpExcel->getActiveSheet()->setCellValue('B'.$k, $Data[$i]['stu_Name']);
            $phpExcel->getActiveSheet()->setCellValue('C'.$k, $Data[$i]['stu_Sex']);
            $phpExcel->getActiveSheet()->setCellValue('D'.$k, $Data[$i]['stu_Grade']);
            $phpExcel->getActiveSheet()->setCellValue('E'.$k, $Data[$i]['stu_Profession']);
            $phpExcel->getActiveSheet()->setCellValue('F'.$k, $Data[$i]['stu_Class']);
            $k++;
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$fileName.'.xls');
        header("Content-Transfer-Encoding:binary");
       // $objWriter->save('//APP//Admin//Public//temp'.$fileName.'.xls');
//        $objWriter->save('php://APP//Admin//Public//temp//output');
//        $objWriter->save($fileName.'.xls');
        $objWriter->save('php://output');
    }
    function logout(){
        session(NULL);
        $this->redirect('Login/');
    }
}
