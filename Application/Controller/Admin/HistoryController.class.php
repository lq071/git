<?php

/**
 * 消费记录控制器
 */
class HistoryController extends PlatformController
{
    private $history;

    public function  __construct()
    {
        parent::__construct();
        $this->history = new HistoryModel();
    }

    /**
     *
     */
    public function index(){
        //分页
        $page = $_GET['page'] ?? 1;
        $pageSize =10;
        $condition = [];
        //搜索条件
        if (!empty($_REQUEST['type'])){
            $condition[] = "`type`='{$_REQUEST['type']}'-1";
        }
        if (!empty($_REQUEST['keyword'])){
            $condition[]="`user_id`='{$_REQUEST['keyword']}' OR `member_id` ='{$_REQUEST['keyword']}' OR `content` LIKE '{$_REQUEST['keyword']}'";
        }

            //消费信息
        $result = $this->history->getAll($condition,$page,$pageSize);
        list($rows,$total) = $result;

//        dump($groups);die;
        unset($_REQUEST['page']);
        $pageShow = PageModel::showPage($page,$pageSize,$total,http_build_query($_REQUEST));

        //会员名 根据$rows['user_id']查会员名
        $users = new UserModel();
        $Resusers = $users->getAll()[0];

        //服务员名
        $members = new MemberModel();
        $ResMembers = $members->getAll()[0];

        //查询充值金额最多的人
        $rechargeTop = $this->history->recharge($Resusers,1);
        //调用排序方法
        $rechargeTop= $this->arr_sort($rechargeTop,'sumMoney','desc');
//        dump($rechargeTop);die;

        //查询消费金额最多的人
        $spendTop = $this->history->recharge($Resusers,0);
        //调用排序方法
        $spendTop= $this->arr_sort($spendTop,'sumMoney','desc');

        //查询服务之星
        $serviceTop = $this->history->serviceStar($ResMembers);
//        dump($serviceTop);die;
        //调用排序方法
        $serviceTop= $this->arr_sort($serviceTop,'sumService','desc');

        //显示页面
        $this->assign('html',$pageShow);
        $this->assign('rows',$rows);
        $this->assign('Resusers',$Resusers);
        $this->assign('ResMembers',$ResMembers);
        $this->assign('rechargeTop',$rechargeTop);
        $this->assign('spendTop',$spendTop);
        $this->assign('serviceTop',$serviceTop);
        $this->display('index');
    }


    /**
     * 根据数组中的键值进行排序
     * @param $array
     * @param $key
     * @param string $order
     */
    function arr_sort($array, $key, $order="asc",$deep=3){ //asc是升序 desc是降序
        $i=0;
        $arr_nums=$arr=array();
        foreach($array as $k=>$v){
            $arr_nums[$k]=$v[$key];
        }
        if($order=='asc'){
            asort($arr_nums);
        }else{
            arsort($arr_nums);
        }
        foreach($arr_nums as $k=>$v){
            if($i == $deep){
                break;
            }
            $i++;
            $arr[$k]=$array[$k];
        }
        return $arr;
    }
}