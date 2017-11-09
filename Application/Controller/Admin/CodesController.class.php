<?php

/**
 * 代金券
 */
class CodesController extends PlatformController
{
    /**
     * 代金券列表
     */
    public function index(){
        //接收数据
        //处理数据
        $codesModel = new CodesModel();
        $rows=$codesModel->getAll();
        //显示页面
        $this->assign('rows',$rows);
        $this->display('index');
    }

    /**
     * 代金券添加
     */
    public function add(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
//            dump($_POST);die;
            //接收数据
            $data=$_POST;
            //var_dump($data);exit;
            $data['status']=0;
            if (empty($data['money'])){
                $this->redirect('index.php?p=Admin&c=Codes&a=add','请输入代金券金额','3');
            }
            //处理数据
            $codesModel = new CodesModel();
            $result=$codesModel->add($data);
            if($result===false){
                $this->redirect('index.php?p=Admin&c=Codes&a=add','添加失败'.$codesModel->getError(),'3');
            }
            //显示页面
            $this->redirect('index.php?p=Admin&c=Codes&a=index');
        }
        //接收数据
        $code="RS".uniqid();
        //处理数据
        $userModel=new UserModel();
        $users=$userModel->getAll()[0];
        //显示页面
        $this->assign('code',$code);
        $this->assign('users',$users);
        $this->display('add');
    }

    /**
     * 代金券更新
     */
    public function edit(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //接收数据
            $data=$_POST;
            dump($data);
            //处理数据
            if (empty($data['money'])){
                $data['status'] =1;
                $data['money']= 0.0;
            }
            $codesModel = new CodesModel();
            $result=$codesModel->update($data);
//            dump($result);die;
            if($result===false){
                $this->redirect('index.php?p=Admin&c=Codes&a=edit','更新失败'.$codesModel->getError(),'3');
            }
            //显示页面
            $this->redirect('index.php?p=Admin&c=Codes&a=index');
        }
        //接收数据
        $id=$_GET['id'];
        //处理数据
        $codesModel = new CodesModel();
        $row=$codesModel->getOne($id);
        //显示页面
        $this->assign('row',$row);
        $this->display('edit');
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
            $this->redirect('index.php?p=Admin&c=Codes&a=index','删除失败'.$codesModel->getError(),'3');
        }
        //显示页面
        $this->redirect('index.php?p=Admin&c=Codes&a=index');
    }
}