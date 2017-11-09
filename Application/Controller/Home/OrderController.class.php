<?php


class OrderController extends PlatformController
{
    /**
     * 预约列表
     */
    public function index(){
        //接收数据
        $user_id = $_SESSION['userInfo']['user_id'];
        //处理数据
        $orderModel=new OrderModel();
        $rows=$orderModel->getAllById($user_id);
        //显示页面
        $this->assign('rows',$rows);
        $this->display('index');
    }

    /**
     * 添加预约
     */
    public function add(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //接收数据
            $data=$_POST;
            //var_dump($data);exit;
//            dump($data);
            if (empty($data['date'])){
                $this->redirect('index.php?p=Home&c=Order&a=add','未选择预约时间','3');
            }
            if ($data['date'] < date('Y-m-d')){
                $this->redirect('index.php?p=Home&c=Order&a=add','预约时间不能使用过期时间','3');
            }

            $data['date'] = strtotime($data['date']);
//            dump($data);die;

            $data['user_id'] = $_SESSION['userInfo']['user_id'];
            //处理数据
            $orderModel=new OrderModel();
            $result=$orderModel->add($data);
            if($result===false){
                $this->redirect('index.php?p=Home&c=Order&a=add','添加失败','3');
            }
            //显示页面
            $this->redirect('index.php?p=Home&c=Order&a=index');
        }
        //接收数据
        //处理数据
        //美发师
        $memberModel=new MemberModel();
        $members=$memberModel->getMember();
        //套餐
        $plansModel=new PlansModel();
        $plans=$plansModel->getAll('1');
        //显示页面
        $this->assign('members',$members);
        $this->assign('plans',$plans);
        $this->display('add');
    }

    /**
     * 删除预约
     */
    public function delete(){
        //接收数据
        $id=$_GET['id'];
        //处理数据
        $orderModel=new OrderModel();
        $result=$orderModel->delete($id);
        if($result===false){
            $this->redirect('index.php?p=Home&c=Order&a=index','取消失败','3');
        }
        //显示页面
        $this->redirect('index.php?p=Home&c=Order&a=index');
    }
}