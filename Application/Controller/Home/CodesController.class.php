<?php

class CodesController extends PlatformController
{
    /**
     * 代金券列表
     */
    public function index(){
        //接收数据
        $user_id=$_SESSION['userInfo']['user_id'];
        //处理数据
        $codesModel = new CodesModel();
        $rows=$codesModel->getAllById($user_id);
        //显示页面
        $this->assign('rows',$rows);
        $this->display('index');
    }
    /**
     * 删除代金卷
     */
    public function delete()
    {
        //接收数据
        $id=$_GET['id'];
        //处理数据
        $codesModel = new CodesModel();
        $result=$codesModel->delete($id);
        if($result===false){
            $this->redirect('index.php?p=Home&c=Codes&a=index','删除失败'.$codesModel->getError(),'3');
        }
        //显示页面
        $this->redirect('index.php?p=Home&c=Codes&a=index');
    }
}