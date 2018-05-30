<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
use Model\StudentChoiceModel;
use Model\StudentModel;
/**
 * Description of LoginController
 *
 * @author Administrator
 */
class LoginController extends Controller{
    //put your code here
    var $stuChoiceM;
    var $studentM;
    function __construct() {
        parent::__construct();
        $this->stuChoiceM = new StudentChoiceModel();
        $this->studentM = new StudentModel();
        
    }
    /**
     * 验证码的生成
     */
    function verifyImg() {
        //$cfg = array(
        //    'fontSize'  =>  20,                 // 验证码字体大小(px)
        //    'imageH'    =>  100,                  // 验证码图片高度
        //    'imageW'    =>  200,                  // 验证码图片宽度 
        //    'length'    =>  4,                  // 验证码位数
        //   'useNoise'  =>  FALSE,              //是否添加杂点 默认为true
        //    'useCurve'  =>  FALSE,              //是否使用混淆曲线 默认为true
        //    'fontttf'   =>  '4.ttf',            // 验证码字体，不设置随机获取
        //);
        //$very = new Verify($cfg);
		ob_clean();
		$very = new \Think\Verify(); 
		$very -> fontSize = 20;
		$very -> length = 4;
        $very -> entry();
    }
    function index(){
        $this->display();
    }
    function doLogin(){
        $username = isset($_POST['username']) ? $_POST['username'] : NULL;
        $password = isset($_POST['password']) ? $_POST['password'] : NULL;
        $captcha = isset($_POST['captcha']) ? $_POST['captcha'] : NULL;
        $student = $this->studentM;
        $vry = new Verify();
        if($vry ->check($captcha)){
            $list = $student->field("*")->where("stu_Number=%d",$username)->select();
//            dump($list);
            if($list){
                if($password === $list[0]['stu_Password']){
                    $list[0]['stu_Password'] = "";
                    session('stu_Number',$list[0]['stu_Number']);
                    $isChange = $list[0]['stu_IsChange'];
                    if($isChange==0){
//                        $data['code'] = 5;
                        echo 5;
                    }  else {
                        if($list[0]['stu_Grade'] == '2015'){
                            echo 1;
                        }else{
                            echo 6;
                        }
//                        if($this->stuChoiceM->field('*')->where("ch_StuNumber = %d",$username)->select()){
////                            $data['code'] = 6;
//                            echo 6;
//                        }  else {
////                            $data['code'] = 1;
//                            echo 1;
//                        }
                    }
                }  else {
//                    $data['code'] = 4;
                    echo 4;
                }
            }  else {
//                $data['code'] = 3;
                echo 3;
            }
        } else {
//            $data['code'] = 2;
            echo 2;
        }
    }
    function setPassword(){
        if(isset($_SESSION['stu_Number'])&&$_SESSION['stu_Number'] != ''){
            $SNumber = $_SESSION['stu_Number'];
            $student = $this->studentM;
            $list = $student->field("stu_Name,stu_IsChange")->where("stu_Number = %d",$SNumber)->select();
            if($list[0]['stu_IsChange'] == 0){
                $this->assign("SNumber", $SNumber);
                $this->assign("name",$list[0]['stu_Name']);
                $this->display();
            }  else {
                $this->redirect('Login/index');
                session(null);
            }
        }  else {
            $this ->redirect('Login/index');
        }
    }
    /**
     * 重设密码的行为
     */
    function doChange(){
        $oldPsaaword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        if(isset($_SESSION['stu_Number'])&&$_SESSION['stu_Number'] != ''){
            $SNumber = $_SESSION['stu_Number'];
            $student = $this->studentM;
            $list = $student ->field("*") ->where("stu_Number = %d AND stu_Password = '%s' ",$SNumber,$oldPsaaword)->select();
            if($list){
                $data['stu_Password'] = $newPassword;
                $data['stu_IsChange'] = 1;
                $student->data($data)->where("stu_Number = %d",$SNumber)->save();
//                $msg['code'] = 1;
                echo 1;
            }  else {
//                $msg['code'] = 3;
                echo 3;
            }
        }  else {
//            $msg['code'] = 2;
            echo 2;
        }
    }
}
