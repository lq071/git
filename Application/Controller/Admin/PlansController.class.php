<?php
class PlansController extends PlatformController
{
    /**
     * 套餐首页
     */
    public function index(){
        //接收数据
        //处理数据
        $plansModel=new PlansModel();
        $rows=$plansModel->getAll(2);
//        dump($rows);die;
        //显示页面
        $this->assign('rows',$rows);
        $this->display("index");
    }

    /**
     * 添加套餐
     */
    public function add(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            //接收数据
            $data=$_POST;
            //处理数据
            if (empty($data['name'])){
                $this->redirect('index.php?p=Admin&c=Plans&a=add','未填写套餐名称','2');}
            if (empty($data['money'])){
                $this->redirect('index.php?p=Admin&c=Plans&a=add','未填写套餐金额','2');
            }
                //>>对商品状态做和并 用或远算
            $condition= 0;
            foreach ($data['condition'] as $status){
                $condition = $condition |  $status;
            }
            $data['condition']=$condition;
//            dump($data);die;
            $plansModel=new PlansModel();
            $result=$plansModel->add($data);
            if($result===false){
                $this->redirect('index.php?p=Admin&c=Plans&a=add','添加失败','3');
            }
            //显示页面
            $this->redirect('index.php?p=Admin&c=Plans&a=index');
        }
        //接收数据
        //处理数据
        //显示页面
        $this->display("add");
    }
    public function edit(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            //接收数据
            $data=$_POST;
            $id = $data['plan_id'];
            //处理数据
            //处理数据
            if (empty($data['name'])){
                $this->redirect("index.php?p=Admin&c=Plans&a=edit&id={$id}",'未填写套餐名称','2');}
            if (empty($data['money'])){
                $this->redirect("index.php?p=Admin&c=Plans&a=edit&id={$id}",'未填写套餐金额','2');
            }
            //>>对商品状态做和并 用或远算
            $condition= 0;
            if (!empty($data['condition'])){
                foreach ($data['condition'] as $status){
                    $condition = $condition |  $status;
                }
            }
            $data['condition']=$condition;

            $plansModel=new PlansModel();
            $result=$plansModel->update($data);
            if($result===false){
                $this->redirect("index.php?p=Admin&c=Plans&a=edit&id={$id}",'更新失败','3');
            }
            //显示页面
            $this->redirect('index.php?p=Admin&c=Plans&a=index');
        }
        //接收数据
       // var_dump($_GET['id']);exit;
        $plan_id=$_GET['id'];
        //处理数据
        $plansModel=new PlansModel();
        $row=$plansModel->getOne($plan_id);
        //显示页面
        $this->assign("row",$row);
        $this->display('edit');
    }
    public function delete(){
        //接收数据
        $plan_id=$_GET['id'];
        //处理数据
        $plansModel=new PlansModel();
        $result=$plansModel->delete($plan_id);
        if($result===false){
            $this->redirect('index.php?p=Admin&c=Plans&a=index','删除失败','3');
        }
        //显示页面
        $this->redirect('index.php?p=Admin&c=Plans&a=index');
    }
}