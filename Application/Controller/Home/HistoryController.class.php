<?php
class HistoryController extends PlatformController
{
    public function index(){
        //接收数据
        //处理数据
        //充值排行榜

        $historyModel=new HistoryModel();
       //
        $page = $_GET['page'] ?? 1;
        $pageSize =10;
        $condition = [];
        $condition[] = " m.member_id = h.member_id ";
        $condition[] = " h.user_id='{$_SESSION['userInfo']['user_id']}' ";
        //用户信息
        $result = $historyModel->getOne($condition,$page,$pageSize);

        list($rows,$total) = $result;

//        dump($groups);die;
        unset($_REQUEST['page']);
        $pageShow = PageModel::showPage($page,$pageSize,$total,http_build_query($_REQUEST));
        //显示页面
        $this->assign('html',$pageShow);
        $this->assign('rows',$rows);

//        dump($rows);die;
        $this->display('top');
    }
}