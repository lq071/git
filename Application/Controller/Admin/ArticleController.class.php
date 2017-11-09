<?php

/**
 * 活动控制器
 */
class ArticleController extends PlatformController
{
    private $article;   //用来保存创建对象

    public function __construct()
    {
        //调用父类的构造方法
        parent::__construct();
        $this->article = new ArticleModel();
    }

    /**
     * 首页
     */
    public function index(){
        //接收数据
        //分页
        $page = $_GET['page'] ?? 1;
        $pageSize =5;
        $condition = [];
        //用户信息
        $result = $this->article->getAll($condition,$page,$pageSize);
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
     *
     */
    public function insert(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//            dump($_POST);die;
            //接收数据
            $date = $_POST;

//            dump($date);
            //处理数据
                //>>判断输入
            if (empty($date['title'])){
        $this->redirect('index.php?p=Admin&c=Article&a=insert','活动标题不能为空',2);
            }
            if ($date['start'] < date('Y-m-d')){
                $this->redirect('index.php?p=Admin&c=Article&a=insert','活动开始时间不能小于当前时间',2);
            }
            if ($date['end'] < date('Y-m-d')){
                $this->redirect('index.php?p=Admin&c=Article&a=insert','活动结束时间不能小于当前时间',2);
            }
            if ($date['time'] < date('Y-m-d')){
                $this->redirect('index.php?p=Admin&c=Article&a=insert','活动发布时间不能小于当前时间',2);
            }
            if (empty($date['start'])){
                $date['start'] = date('Y-m-d');
            }
            if (empty($date['end'])){
                $date['end'] = date('Y-m-d');
            }
            if (empty($date['time'])){
                $date['time'] = date('Y-m-d');
            }
//            dump($date);
            //>>处理时间,转为时间戳
            $date['start'] =strtotime($date['start']);
            $date['end'] =strtotime($date['end']);
            $date['time'] =strtotime($date['time']);
            $this->article->insertDate($date);
            //显示页面
            $this->jump('添加活动成功','index.php?p=Admin&c=Article&a=index');
        }
        //>>GET
        //接收数据
        //处理数据
        //显示页面
        $this->display('insert');
    }
    public function update(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//            dump($_POST);
            $date['id'] = $_POST['id'];
            //接收数据
            $date = $_POST;
            if (empty($date['title'])){
                $this->redirect("index.php?p=Admin&c=Article&a=update&id={$date['id']}",'活动标题不能为空',2);
            }
            if ($date['start'] < date('Y-m-d')){
                $this->redirect("index.php?p=Admin&c=Article&a=update&id={$date['id']}",'活动开始时间不能小于当前时间',2);
            }
            if ($date['end'] < date('Y-m-d')){
                $this->redirect("index.php?p=Admin&c=Article&a=update&id={$date['id']}",'活动结束时间不能小于当前时间',2);
            }
            if ($date['time'] < date('Y-m-d')){
                $this->redirect("index.php?p=Admin&c=Article&a=update&id={$date['id']}",'活动发布时间不能小于当前时间',2);
            }
            if (empty($date['start'])){
                $date['start'] = date('Y-m-d');
            }
            if (empty($date['end'])){
                $date['end'] = date('Y-m-d');
            }
            if (empty($date['time'])){
                $date['time'] = date('Y-m-d');
            }
            //>>处理时间,转为时间戳
            $date['start'] =strtotime($date['start']);
            $date['end'] =strtotime($date['end']);
            $date['time'] =strtotime($date['time']);
//            dump($date);
            //处理数据
            $this->article->update($date);
            //显示页面
            $this->jump('修改活动成功',"index.php?p=Admin&c=Article&a=index");
        }
        //>>GET
        //接收数据
        $id = $_GET['id'];
        //处理数据
        $row = $this->article->getOne($id);
        //显示页面
        $this->assign('row',$row);
        $this->display('update');
    }
    public function delete(){
        //接收数据
        $id = $_GET['id'];
        //处理数据
        $this->article->delete($id);
        //显示页面
        $this->jump('成功删除','index.php?p=Admin&c=Article&a=index');
    }

    /**
     * 查看
     */
    public function update1(){
        $id = $_GET['id'];
        //处理数据
        $row = $this->article->getOne($id);
        //显示页面
        $this->assign('row',$row);
        $this->display('update1');
    }
}