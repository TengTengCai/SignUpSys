<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Admin\Controller;
use Think\Controller;
use Model\ManagerModel;
/**
 * Description of LoginController
 *
 * @author Administrator
 */
class LoginController extends Controller {
    var $ManagerM;
    public function __construct() {
        parent::__construct();
        $this->ManagerM = new ManagerModel();
    }
    function doLogin(){
        $manager = $this->ManagerM;
        $username = $_POST['username'];
        $password = $_POST['password'];
        $where['mg_Name'] = $username;
        $where['mg_Password'] = $password;
        $arr = $manager->field('mg_Id') ->where($where)->find();
        if($arr){
            $_SESSION['manager'] = $username;
            $this->success('用户登录成功', U('Student/index'));
        }else{
            $this->error('用户名或密码错误');
        }
    }
    
}
