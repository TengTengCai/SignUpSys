<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Admin\Controller;
use Think\Controller;
use Model\StudentModel;
use Model\StudentChoiceModel;
use Model\StudentTrainingModel;
use Model\CourseTreeModel;
use Model\TrainingTreeModel;
/**
 * Description of StudentController
 *
 * @author Administrator
 */
class StudentController extends Controller {
    var $studentM;
    var $stuChoiceM;
    var $stuTrainingM;
    var $CTreeM;
    var $TTreeM;
    function __construct() {
        parent::__construct();
        if(isset($_SESSION['manager'])&&$_SESSION['manager'] != ''){
            $this->studentM = new StudentModel();
            $this->stuChoiceM = new StudentChoiceModel();
            $this->stuTrainingM = new StudentTrainingModel();
            $this->CTreeM = new CourseTreeModel();
            $this->TTreeM = new TrainingTreeModel();
        }  else {
            $this ->redirect('Login/index');
        }
    }
    function index(){
        $data = $this->studentM->field("DISTINCT stu_Grade")->select();
        for($i = 0; $i<count($data);$i++){
            $str = $str.'<option>'.$data[$i]['stu_Grade'].'</option> ';
        }
        $this->assign("grade", $str);
        $this->assign("manager",$_SESSION['manager']);
        $this->display();
    }
    function getAllStudentInfo(){
        $data = $this->studentM->field("stu_Number,stu_Name,stu_Sex,stu_Grade,stu_Profession,stu_Class")->select();
        echo json_encode($data);
    }
    function getProfessionList(){
        $grade = isset($_GET['grade'])? $_GET['grade'] : NULL;
        if ($grade){
            $data = $this->studentM->field("DISTINCT stu_Profession")->where("stu_Grade = %d",$grade)->select();
            for($i = 0; $i<count($data);$i++){
                $str = $str.'<option>'.$data[$i]['stu_Profession'].'</option> ';
            }
            echo $str;
        }
    }
    function getClassList(){
        $profession = isset($_GET['profession'])? $_GET['profession'] : NULL;
        $grade = isset($_GET['grade'])? $_GET['grade'] : NULL;
        if ($grade != NULL && $profession != NULL){
            $data = $this->studentM->field("DISTINCT stu_Class")
                    ->where("stu_Grade = %d AND stu_Profession = '%s'",$grade,$profession)->order('stu_Class desc')->select();
            for($i = 0; $i<count($data);$i++){
                $str = $str.'<option>'.$data[$i]['stu_Class'].'</option> ';
            }
            echo $str;
        }
    }
    function conditionQuery(){
        //判断是否有number，有就写入map中
        $number = isset($_GET['number']) ? $_GET['number'] : NULL;
        if($number){
            $map['stu_Number'] = $number;
        }
        //判断是否有name，有就写入map中
        $name = isset($_GET['name']) ? $_GET['name'] : NULL;
        if($name){
            $map['stu_Name'] = $name;
        }
        //判断是否有grade，有就写入map中
        $grade = isset($_GET['grade']) ? $_GET['grade'] : NULL;
        if($grade){
            $map['stu_Grade'] = $grade;
        }
        //判断是否有prefession，有就写入map中
        $profession = isset($_GET['profession']) ? $_GET['profession'] : NULL;
        if($profession){
            $map['stu_Profession'] = $profession;
        }
        //判断是否有classs，有就写入map中
        $classs = isset($_GET['classs']) ? $_GET['classs'] : NULL;
        if($classs){
            $map['stu_Class'] = $classs;
        }
        //将map作为条件进行查询
        if(!$map){
            $map = 1;
        }else{
            $map['_logic'] = 'AND';
        }
//        dump($map);
        $studentM = $this->studentM;
        $list = $studentM->field('stu_Number,stu_Name,stu_Sex,stu_Grade,stu_Profession,stu_Class')
                ->where($map)->select();
//dump($list);
        if(count($list)== 0){
            //$this->error("");
            echo null;
        } else {
            //将查询的数据存于缓存中，便于导出时获取
            F('data',$list);
            //将数组转换成json数据输出
            echo json_encode($list);
        }
    }
    /*
     * 获取excel表格文件，用户进行下载
     */
    function getExcel(){
        //读取缓存
        $Data = F('data');
        //判断是否有数据
        if(!$Data){
            $this->error('请查询后再导出数据');
        }
        //引入必要的文件
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
        $objWriter->save('php://output');//输出文件
    }
    function detail(){
        $map['stu_Number'] = isset($_GET['stu_Number'])?$_GET['stu_Number']:NULL;
        $data = $this->studentM->field("stu_Name,stu_Sex,stu_Grade,stu_Profession,stu_Class,stu_IsChange")->where($map)->select();
        $message = "学号:".$map['stu_Number']."&nbsp;&nbsp;&nbsp;&nbsp;姓名："
                .$data[0]['stu_Name']."&nbsp;&nbsp;&nbsp;&nbsp;性别："
                .$data[0]['stu_Sex']."&nbsp;&nbsp;&nbsp;&nbsp;班级："
                .$data[0]['stu_Grade']."级".$data[0]['stu_Profession'].$data[0]['stu_Class']."班";
        $this->assign("name", $map['stu_Number']);
        $this->assign("table", $this->getCourse($map['stu_Number']));
        $this->assign("mytable", $this->getTraining($map['stu_Number']));
        $this->assign("message",$message);
        $this->display();
    }
    function getCourse($stu_Number){
        $data = $this->stuChoiceM->field('ch_TrNodeId AS ctr_NodeId')->where("ch_StuNumber = %d",$stu_Number)->select();
        if (!$data){
            return;
        }
        $data['_logic'] = 'OR';
        $data1 = $this->CTreeM->field("ctr_NodeId,ctr_ParentId,ctr_Name")->where($data)->select();
        for($i = 0;$i<count($data1);$i++){
            $count = $this->stuChoiceM->field('COUNT(ch_StuNumber) AS count')->where('ch_TrNodeId = %d',$data1[$i]['ctr_NodeId'])->select();
            $limit = $this->CTreeM->field('ctr_Limit')->where('ctr_NodeId = %d',$data1[$i]['ctr_ParentId'])->select();
            $str = $str."<tr><td>".$data1[$i]['ctr_Name']."</td><td>".$count[0]['count']."</td><td>".$limit[0]['ctr_Limit']."</td></tr>";
        }
        return $str;
    }
    function getTraining($stu_Number){
        $data = $this->stuTrainingM->field('tra_TrNodeId AS ttr_NodeId')->where("tra_StuNumber = %d",$stu_Number)->select();
        if (!$data){
            return;
        }
        $data['_logic'] = 'OR';
        $data1 = $this->TTreeM->field("ttr_NodeId,ttr_Name,ttr_Limit")->where($data)->select();
        for($i = 0;$i<count($data1);$i++){
            $count = $this->stuTrainingM->field('COUNT(tra_StuNumber) AS count')->where('tra_TrNodeId = %d',$data1[$i]['ttr_NodeId'])->select();
            $str = $str."<tr><td>".$data1[$i]['ttr_Name']."</td><td>".$count[0]['count']."</td><td>".$data1[$i]['ttr_Limit']."</td></tr>";
        }
        return $str;
    }
    function reSetPassword(){
        $stu_Number = isset($_GET['stu_Number']) ? $_GET['stu_Number'] : NULL;
        if ($stu_Number){
            $map['stu_Password'] = 'e10adc3949ba59abbe56e057f20f883e';
            $map['stu_IsChange'] = 0;
            $this->studentM->data($map)->where("stu_Number = %d",$stu_Number)->save();
            echo '成功';
        } else {
            $this->error();
        }
    }
    function clearChoice(){
        $stu_Number = isset($_GET['stu_Number']) ? $_GET['stu_Number'] : NULL;
        if ($stu_Number){
            $this->stuChoiceM->where("ch_StuNumber = %d",$stu_Number)->delete();
            echo '成功';
        } else {
            $this->error();
        }
    }
    function clearTraining(){
        $stu_Number = isset($_GET['stu_Number']) ? $_GET['stu_Number'] : NULL;
        if ($stu_Number){
            $this->stuTrainingM->where("tra_StuNumber = %d",$stu_Number)->delete();
            echo '成功';
        } else {
            $this->error();
        }
    }
    function logout(){
        session(NULL);
        $this->redirect('Login/');
    }
}
