<?php

/**
 * VIP控制器
 */
class VipController extends PlatformController
{
    public function index(){
        //接收数据
        //处理数据
            //调用VipModel上的getAll()方法,无传入,有返回值
        $Vip = new VipModel();
        $rows = $Vip->getAll();
        //显示页面
        $this->assign('rows',$rows);
        $this->display('index');
    }
    public  function update(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//            dump($_POST);die;
            $date = $_POST;
            $Vip = new VipModel();
            $Vip->updateDate($date);
            $this->jump('修改成功','index.php?p=Admin&c=Vip&a=index');
        }
        $id = $_GET['vip_id'];
        $Vip = new VipModel();
        $row = $Vip->geOne($id);
//        dump($row);
        $this->assign('row',$row);
        $this->display('update');
    }
}