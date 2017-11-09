<?php

/**
 * 登录控制器
 */
class LoginController extends Controller
{
    public function index(){
        //接收数据
        //处理数据
        //显示页面
        $this->display('login');
    }
    /**
     * 登录验证
     */
    public function check(){
        //接收数据
        $username=$_POST['username'];
        $password=$_POST['password'];
        $validate=$_POST['validate'];
        //处理数据
        //验证验证码
        $result=ValidateCodeController::checkCode($validate);
        if($result===false){
            $this->redirect("index.php?p=Home&c=Login&a=index","验证码输入错误...","3");
        }
        //验证用户名和密码
        $loginModel=new LoginModel();
        //验证失败,跳回登录页面,,成功,保存用户信息到session
        $result=$loginModel->checkVip($username,$password);
//        dump($result);die;
        if($result===false){
            $this->redirect("index.php?p=Home&c=Login&a=index","用户名或密码输入错误".$loginModel->getError(),"3");
        }

            @session_start();
            $_SESSION['userInfo']=$result;
            //是否自动登录   是:把数据库的id和password 加盐加密保存到cookie中
            if(isset($_POST['remember']) && $_POST['remember']==1){
                setcookie("Uid",$result['id'],time()+7*24*3600,"/");
                setcookie("Upassword",md5($result['password']."linqin"),time()+7*24*3600,"/");
            }
            //验证成功,显示页面
            $this->redirect("index.php?p=Home&c=Index&a=index");
    }
    public function loginOut(){
        setcookie('id',null,-1,'/');
        setcookie('password',null,-1,'/');
        unset($_SESSION['userInfo']);
        $this->redirect('index.php?p=Home&c=Index&a=index');
    }
}