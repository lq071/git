<?php

/**
 * 前台显示页面
 */
class IndexController extends Controller
{
    /**
     * 前台显示
     */
    public function index(){
        //接收数据
        $time=time();
        $condition[]="end > $time";
        //处理数据
        $articleModel=new ArticleModel();
        $articles=$articleModel->getAll($condition)[0];
        //显示页面

        //充值排行榜
        $historyModel=new HistoryModel();
        $recharges=$historyModel->TypeTop($type=1);
        //var_dump($rows);exit;

        //消费排行榜
        $historyModel=new HistoryModel();
        $consumes=$historyModel->TypeTop($type=0);
        //var_dump($consumes);exit;

        //服务之星
        $historyModel=new HistoryModel();
        $members=$historyModel->memberTop();
        //积分商城
        $where="where status = 1";
        $pointsModel=new PointsModel();
        $rows=$pointsModel->getAll($where);
        //显示页面
        $this->assign('recharges',$recharges);
        $this->assign('consumes',$consumes);
        $this->assign('members',$members);
        $this->assign('articles',$articles);
        $this->assign('rows',$rows);
        $this->display('index');
    }

    /**
     *  前台管理
     */
    public function index1(){
    }
}