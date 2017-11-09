<?php

//统一平台验证登录
class PlatformController extends Controller
{
    public function __construct()
    {
        $result=$this->checkLogin();
        if($result===false){
            //显示页面
            $this->redirect("index.php?p=Home&c=Login&a=index");
        }
    }

    /**
     * 登录验证
     * @return bool
     */
    private function checkLogin(){
        //判断 session 中有无用户信息
        @session_start();
        if(!isset($_SESSION['userInfo'])){
        //判断 cookie 中有无id password
            if(isset($_COOKIE['Uid']) && isset($_COOKIE['Upassword'])){
                //有 就取出验证
                $id=$_COOKIE['Uid'];
                $password=$_COOKIE['Upassword'];
                //验证 id 和password
                $loginModel=new LoginModel();
                $userinfo=$loginModel->checkIdPwd($id,$password);
                if($userinfo===false){ //失败
                    return false;
                }else{ //成功 返回用户信息
                    $_SESSION['userInfo']=$userinfo;
                    return true;
                }
            }
            return false;
        }
    }
}