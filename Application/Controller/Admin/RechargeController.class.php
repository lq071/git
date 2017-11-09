<?php

/**
 * 充值活动控制器
 */
class RechargeController extends PlatformController
{
    private $recharge;  //保存对象

    /**
     * RechargeController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->recharge = new RechargeModel();  //自动创建对象
    }

    /**
     * 首页列表功能
     */
    public function index(){
        //接收数据
        //处理数据
        $result = $this->recharge->getAll();
            //
        //显示页面
        list($rows) = $result;
        $this->assign('rows',$rows);
        $this->display('index');
    }

    /**
     * 新增
     */
    public function insert(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//            dump($_POST);
            //接收数据
            $date=$_POST;
            //处理数据
            if (empty($date['money'])){
                $this->redirect('index.php?p=Admin&c=Recharge&a=insert','未填写充值金额',2);
            }
            if (empty($date['donation'])){
                $this->redirect('index.php?p=Admin&c=Recharge&a=insert','未填写赠送金额',2);
            }
            if (empty($date['name'])){
                $this->redirect('index.php?p=Admin&c=Recharge&a=insert','未填写充值活动名称',2);
            }
            $this->recharge->insertDate($date);
            //显示页面
            $this->jump('添加充值活动成功','index.php?p=Admin&c=Recharge&a=index');
        }
        //接收数据
        //处理数据
        //显示页面
        $this->display('insert');
    }
    /**
     * 修改
     */
    public function update(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//            dump($_POST);die;
            //接收数据
            $date=$_POST;
            //处理数据
            if (empty($date['money'])){
                $this->redirect("index.php?p=Admin&c=Recharge&a=update&recharge_id={$_POST['id']}",'未填写充值金额',2);
            }
            if (empty($date['donation'])){
                $this->redirect("index.php?p=Admin&c=Recharge&a=update&recharge_id={$_POST['id']}",'未填写赠送金额',2);
            }
            if (empty($date['name'])){
                $this->redirect("index.php?p=Admin&c=Recharge&a=update&recharge_id={$_POST['id']}",'未填写充值活动名称',2);
            }
            $this->recharge->updateDate($date);
            //显示页面
            $this->jump('修改充值活动成功','index.php?p=Admin&c=Recharge&a=index');
        }
        //接收数据
        $id = $_GET['recharge_id'];
        //处理数据
        $row = $this->recharge->getOne($id);
        //显示页面
        $this->assign('row',$row);
        $this->display('update');
    }
    public function delete(){
        //接收数据
        $id = $_GET['recharge_id'];
        //处理数据
        $this->recharge->deleteDate($id);
        $this->jump('删除充值活动成功','index.php?p=Admin&c=Recharge&a=index');
    }
}