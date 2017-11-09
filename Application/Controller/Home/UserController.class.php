<?php


class UserController extends Controller
{
    public function index(){
        //接收数据
        $user_id = $_SESSION['userInfo']['user_id'];
        //处理数据
        $userModel = new UserModel();
        $row = $userModel->getOne($user_id);
       // var_dump($row);exit;
        //显示页面
        $this->assign('row',$row);
        $this->display('index');
    }
    public function update(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $date['photo'] = $_POST['oldphoto'];
            $date['id']=$_POST['id'];
            $file =$_FILES['photo'];
            if ($_FILES['photo']['error'] != 4){
                //调用文件上传模板 UploadModel上的upload()方法,传入$file和要保存的目录,将保存后的路径返回
                $upload = new UploadModel();
                $logo = $upload->upload($file,'./Uploads/Admin/User');
                $date['photo'] = $logo;
            }
            $userModel = new UserModel();
            $userModel->updateDate($date);
            //显示页面
            $this->jump('修改成功','index.php?p=Home&c=User&a=index');
        }
        $user_id = $_SESSION['userInfo']['user_id'];
        $userModel = new UserModel();
        $row = $userModel->getOne($user_id);
        $this->assign('row',$row);
        $this->display('update');
    }
}