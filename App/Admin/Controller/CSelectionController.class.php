<?php

namespace Admin\Controller;
use Think\Controller;
use Model\CourseTreeModel;
use Model\StudentChoiceModel;
use Model\StudentModel;
use Model\ManagerModel;
/**
 * Description of CSelectionController
 * 
 * @author Administrator
 */
class CSelectionController extends Controller{
    var $CTreeM;
    var $stuChoiceM;
    var $studentM;
    var $managerM;
    function __construct() {
        parent::__construct();
        if(isset($_SESSION['manager'])&&$_SESSION['manager'] != ''){
            $this->CTreeM = new CourseTreeModel();
            $this->stuChoiceM = new StudentChoiceModel();
            $this->studentM = new StudentModel();
            $this->managerM = new ManagerModel();
        }  else {
            $this ->redirect('Login/index');
        }
    }
    function index(){
        $data = $this->CTreeM->field('DISTINCT ctr_Name')->where('ctr_Level = 1')->select();
        for ($i = 0;$i<count($data);$i++){
            $major = $major."<option>".$data[$i]['ctr_Name']."</option>";
        }
//        $data1 = $this->studentM->distinct(true)->field('stu_Grade')->select();
//        for ($i = 0;$i<count($data);$i++){
//            $grades = $grades."<option>".$data1[$i]['stu_Grade']."</option>";
//        }
        $grade = $this->managerM->field("mg_Rule")->where("mg_Name='%s'",$_SESSION['manager'])->select();
        $this->assign("grades","<option>".$grade[0]['mg_Rule']."</option>");
        $this->assign("major",$major);
        $this->display();
    }
//    function getClassName(){
//        $pName = isset($_GET['direction']) ? $_GET['direction'] : NULL;
//        $grade = isset($_GET['grade']) ? $_GET['grade'] : NULL;
//        $G = $this->managerM->field("mg_Rule")->where("mg_Name='%s'",$_SESSION['manager'])->select();
////        dump($G);
//        if($G[0]['mg_Rule'] == $grade){
//            $data = $this->CTreeM->field('ctr_NodeId AS ctr_ParentId')->where("ctr_Name = '%s'",$pName)->select();
//            $data['_logic'] = 'OR';
//        } else {
//            $data = $this->stuChoiceM->field("DISTINCT ch_TrNodeId AS ctr_NodeId")->where("ch_strGrade = '%s'",$grade)->select();
//            $data['_logic'] = 'OR';
//        }
//        $data2 = $this->CTreeM->field('DISTINCT ctr_Name')->where($data)->select();
//        for($i=0;$i<count($data2);$i++){
//            $str = $str."<option>".$data2[$i]['ctr_Name']."</option>";
//        }
//        echo $str;
//    }
    function getDirection(){
        $major = isset($_GET['major']) ? $_GET['major'] : NULL;
        $data1 = $this->CTreeM->field('ctr_NodeId')->where("ctr_Name = '%s'",$major)->select();
        $p_Id = $data1[0]['ctr_NodeId'];
        $p_ids = $this->CTreeM->field('ctr_NodeId')->where('ctr_ParentId = %d',$p_Id)->select();
        for($i=0;$i<count($p_ids);$i++){
            $data2 = $this->CTreeM->field('ctr_Name,ctr_NodeId')->where('ctr_ParentId = %d',$p_ids[$i]['ctr_NodeId'])->select();
            for($j=0;$j<count($data2);$j++){
                $str = $str."<option id=".$data2[$j]['ctr_NodeId'].">".$data2[$j]['ctr_Name']."</option>";
            }
        } 
        echo $str;
    }
    function getInfo(){
        $nodeID = isset($_GET['nodeID']) ? $_GET['nodeID'] : NULL;
        $grade = isset($_GET['grade']) ? $_GET['grade'] : NULL;
//        echo $map['ctr_Name'] .$grade;
        $arry = $this->CTreeM->field("ctr_NodeId")->where("ctr_ParentId = %d",$nodeID)->select();
//        dump($arry);
        $arry['_logic'] = 'OR';
        if($arry){
            $data = $this->CTreeM->field("ctr_NodeId AS ch_TrNodeId")->where($arry)->select();
        }
//        dump($data);
        $data['_logic'] = 'OR';
        $data1 = $this->stuChoiceM->field("ch_StuNumber AS stu_Number")->where($data)->select();
//        dump($data1);
        if ($data1){
            $data1['_logic'] = 'OR';
            $data2['stu_Grade'] = $grade;
            $where_main['_complex'] = array(
                    $data1,
                    $data2,
                    '_logic' => 'and'
                );
            $data3 = $this->studentM->field("stu_Number,stu_Sex,stu_Name,stu_Grade,stu_Profession,stu_Class")->where($where_main)->select();
            F("data",$data3);
            echo json_encode($data3);
        } else {
            echo NULL;
        }
    }
    function getWithout(){
        $grade = $this->managerM->field("mg_Rule")->where("mg_Name='%s'",$_SESSION['manager'])->select();
        $map['stu_Grade']=$grade[0]['mg_Rule'];
        $sql  = "SELECT `stu_Number`,`stu_Name`,`stu_Sex`,`stu_Grade`,`stu_Profession`,`stu_Class` FROM `su_student` WHERE su_student.stu_Number NOT IN (SELECT ch_StuNumber FROM `su_student_choice` WHERE ch_StuGrade = ".$map['stu_Grade'].") AND `stu_Grade` = ".$map['stu_Grade']."";
        $data1 = $this->studentM->query($sql);
        F("data",$data1);
        echo json_encode($data1);
    }
    /**
     * 获取查询的数据表格
     */
	
    function test(){
        $Data = F("data");
        if(!$Data){
            $this->error('请查询后再导出数据');
        }
		//dump($Data);
		//vendor("PHPExcel.PHPExcel");
        //import ('Org.Util.PHPExcel');
		//import ('Org.Util.PHPExcel.Reader.Excel5');
		import("Org.Util.PHPExcel");
		import("Org.Util.PHPExcel.Writer.Excel5");
		import("Org.Util.PHPExcel.IOFactory.php");
        $fileName = date("YmdHis");//文件名
        $phpExcel = new \PHPExcel();
        
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
            $phpExcel->getActiveSheet()->setCellValue('A'.$k, " ".$Data[$i]['stu_Number']);
            $phpExcel->getActiveSheet()->setCellValue('B'.$k, $Data[$i]['stu_Name']);
            $phpExcel->getActiveSheet()->setCellValue('C'.$k, $Data[$i]['stu_Sex']);
            $phpExcel->getActiveSheet()->setCellValue('D'.$k, $Data[$i]['stu_Grade']);
            $phpExcel->getActiveSheet()->setCellValue('E'.$k, $Data[$i]['stu_Profession']);
            $phpExcel->getActiveSheet()->setCellValue('F'.$k, $Data[$i]['stu_Class']);
            $k++;
        }
		ob_end_clean();
		//$fileName = iconv("utf-8", "gb2312", $fileName);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename='.$fileName.'.xls');
        header("Content-Transfer-Encoding:binary");
		header('content-Type:application/vnd.ms-excel;charset=utf-8');
       // $objWriter->save('//APP//Admin//Public//temp'.$fileName.'.xls');
//        $objWriter->save('php://APP//Admin//Public//temp//output');
//        $objWriter->save($fileName.'.xls');
		//$objWriter = \PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
		$objWriter = \PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $objWriter->save('php://output');
    }
	function getExcel(){
		$Data = F("data");
        if(!$Data){
            $this->error('请查询后再导出数据');
        }
		
		//dump($Data);
		//vendor("PHPExcel.PHPExcel");
        //import ('Org.Util.PHPExcel');
		//import ('Org.Util.PHPExcel.Reader.Excel5');
		import("Org.Util.PHPExcel");
		import("Org.Util.PHPExcel.Writer.Excel5");
		import("Org.Util.PHPExcel.IOFactory.php");
		$phpExcel = new \PHPExcel();
		$date = date("YmdHis",time());//文件名
		$fileName .= "{$date}".".xls";
		if ($phpExcel){
			//echo 1;
		}else {
			//echo 0;
		}
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
            $phpExcel->getActiveSheet()->setCellValue('A'.$k, " ".$Data[$i]['stu_Number']);
            $phpExcel->getActiveSheet()->setCellValue('B'.$k, $Data[$i]['stu_Name']);
            $phpExcel->getActiveSheet()->setCellValue('C'.$k, $Data[$i]['stu_Sex']);
            $phpExcel->getActiveSheet()->setCellValue('D'.$k, $Data[$i]['stu_Grade']);
            $phpExcel->getActiveSheet()->setCellValue('E'.$k, $Data[$i]['stu_Profession']);
            $phpExcel->getActiveSheet()->setCellValue('F'.$k, $Data[$i]['stu_Class']);
            $k++;
			//echo $Data[$i]['stu_Sex'];
        }
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;filename=\"$fileName\"");
		header('Cache-Control: max-age=0');
		
		$objWriter = \PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
		$objWriter->save('php://output'); //文件通过浏览器下载
	}
	
    function logout(){
        session(NULL);
        $this->redirect('Login/');
		$phpExcel = new \PHPExcel();
		
    }
}