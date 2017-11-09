<?php


class OrderController extends PlatformController
{
    /**
     * 预约列表
     */
    public function index(){
        //接收数据
        //处理数据
        $orderModel=new OrderModel();
        $rows=$orderModel->getAll();
        //显示页面
        $this->assign('rows',$rows);
        $this->display('index');
    }
    /**
     *回复
     */
    public function edit(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //接收数据
            $data=$_POST;
//            dump($data);die;
            //处理数据
            $orderModel = new OrderModel();
            $result = $orderModel->update($data);
            if($result===false){
                $this->redirect('index.php?p=Admin&c=Order&a=edit','回复预约失败','3');
            }
            //显示页面
            $this->redirect('index.php?p=Admin&c=Order&a=index');
        }
        //接收数据
        $id=$_GET['id'];
        //处理数据
        $orderModel = new OrderModel();
        $row = $orderModel->getOne($id);
        //美发师
        $memberModel=new MemberModel();
        $members=$memberModel->getMember();
        //套餐
        $plansModel=new PlansModel();
        $plans=$plansModel->getAll(2);
        //显示页面
        $this->assign('members',$members);
        $this->assign('plans',$plans);
        $this->assign('row',$row);
//        dump($row);die;
        $this->display('edit');
    }

    /**
     * 修改状态
     */
    public function editStatus(){
        //接收数据
        $data['status']=$_GET['status'];
        $data['id']=$_GET['id'];
        //处理数据
        $orderModel = new OrderModel();
        $result = $orderModel->update($data);
        if($result===false){
            $this->redirect('index.php?p=Admin&c=Order&a=index','处理预约状态失败','3');
        }
        //显示页面
        $this->redirect('index.php?p=Admin&c=Order&a=index');
    }

}