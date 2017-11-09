<?php


class PointOrderController extends PlatformController
{
    /**
     * 订单列表
     */
    public function index(){
        //接收数据
        $page = $_GET['page'] ?? 1;
        $pageSize =100;
        //处理数据
        $pointOrderModel = new PointOrderModel();
        $result = $pointOrderModel->getAll($page,$pageSize);
        list($rows,$total) = $result;

//        dump($groups);die;
        unset($_REQUEST['page']);
        $pageShow = PageModel::showPage($page,$pageSize,$total,http_build_query($_REQUEST));
        //显示页面
        $this->assign('html',$pageShow);
        $this->assign('rows',$rows);
        $this->display('index');
    }

    /**
     * 更新状态
     */
    public function editStatus(){
        //接收数据
        $data['status']=$_GET['status'];
        $data['id']=$_GET['id'];
        //处理数据
        $pointOrderModel = new PointOrderModel();
        $result = $pointOrderModel->updateStatus($data);
        if($result===false){
            $this->redirect('index.php?p=Admin&c=PointOrder&a=index','处理预约状态失败','3');
        }
        //显示页面
        $this->redirect('index.php?p=Admin&c=PointOrder&a=index');
    }
}