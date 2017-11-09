<?php


class ArticleController extends PlatformController
{
    public function index(){
        //接收数据
        $time=time();
        $condition[]="end > $time";
        //处理数据
        $articleModel=new ArticleModel();
        $articles=$articleModel->getAll($condition)[0];
        //显示页面
        $this->assign('articles',$articles);
        $this->display('index');
    }
}