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
        $where = " and o.user_id = {$_SESSION['userInfo']['user_id']}";
        //处理数据
        $pointOrderModel = new PointOrderModel();
        $result = $pointOrderModel->getAll($page,$pageSize,$where);
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
            $this->redirect('index.php?p=Home&c=PointOrder&a=index','处理预约状态失败','3');
        }
        //显示页面
        $this->redirect('index.php?p=Home&c=PointOrder&a=index');
    }

    /**
     * 添加订单
     */
    public function addForm(){
       // var_dump($_GET);exit;
//        var_dump($_POST);exit;
            //判断 积分 可否兑换
        $checkUser = new UserModel();
        $userMark =$checkUser->getOne($_SESSION['userInfo']['user_id'])['mark'];//用户 积分
        //商品id
        $point_id = $_GET['id'];
        //根据商品id获取 商品积分
          $pointsModel = new PointsModel();
          $goodsMark = $pointsModel->getOne($point_id)['mark'];
         // var_dump($goodsMark);exit;
//        dump($userMark);
//        dump($goodsMark);
//        var_dump($_SESSION['userInfo']);exit ;
        if($userMark < $goodsMark){
            $this->redirect('index.php?p=Home&c=Index&a=index','积分不足,不能兑换此商品','3');
        }else{
                //显示页面
                $this->display('add');
        }
    }

    /**
     *
     */
    public function add(){
        //接收数据
        $data=$_POST;
        unset($data['id']);
        //var_dump($_POST);exit;
        $data['time'] = time();
        $data['user_id'] = $_SESSION['userInfo']['user_id'];
        $data['point_id'] = $_POST['id'];
        //商品id
        $point_id = $_POST['id'];
        //根据商品id获取 商品积分
        $pointsModel = new PointsModel();
        $goodsMark = $pointsModel->getOne($point_id)['mark'];

        //处理数据
        $pointOrderModel = new PointOrderModel();
//            dump($data);
        $result = $pointOrderModel->add($data);

        if($result===false){
            $this->redirect('index.php?p=Home&c=PointOrder&a=index','添加订单失败','3');
        }
        //用户 剩余 积分,存入数据库
        $checkUser = new UserModel();
        $userMark =$checkUser->getOne($_SESSION['userInfo']['user_id'])['mark'];//用户 积分
        $mark = $userMark - $goodsMark ;
        $data['mark'] = $mark;
//        dump($data);
       // dump($data);
        $data['id'] = $_SESSION['userInfo']['id'];
        $userModel = new UserModel();
        $userModel->update1($data);
        //显示页面
//        die;
        $this->redirect('index.php?p=Home&c=PointOrder&a=index');
    }
}