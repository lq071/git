<?php


class LoginController extends Controller
{
    /**
     * 登录表单
     */
    public function index(){
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
            $this->redirect("index.php?p=Admin&c=Login&a=index","验证码输入错误...","3");
        }
        //验证用户名和密码
        $loginModel=new LoginModel();
        //验证失败,跳回登录页面,,成功,保存用户信息到session
        $result=$loginModel->checkUser($username,$password);
        if($result===false){
            $this->redirect("index.php?p=Admin&c=Login&a=index","用户名或密码输入错误".$loginModel->getError(),"3");
        }
        if($result['is_admin']!=0){
            @session_start();
            $_SESSION['userinfo']=$result;
            //是否自动登录   是:把数据库的id和password 加盐加密保存到cookie中
            if(isset($_POST['remember']) && $_POST['remember']==1){
                setcookie("member_id",$result['member_id'],time()+7*24*3600,"/");
                setcookie("password",md5($result['password']."linqin"),time()+7*24*3600,"/");
            }
            //验证成功,显示页面
            $this->redirect("index.php?p=Admin&c=Index&a=index");
        }else{
            $this->redirect("index.php?p=Admin&c=Login&a=index","不是管理员,没有权限".$loginModel->getError(),"3");
        }
    }

    /**
     * 退出
     */
    public function loginOut(){
        setcookie('member_id',null,-1,'/');
        setcookie('password',null,-1,'/');
        unset($_SESSION['userinfo']);
        $this->redirect('index.php?p=Admin&c=Login&a=index');
    }
}