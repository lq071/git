<?php


class GroupController extends PlatformController
{
    /**
     * 显示部门 页面
     */
    public function index(){
        //接收数据
        //处理数据
        $groupModel=new GroupModel();
        $groups=$groupModel->getAll();
       // 显示页面
        $this->assign("groups",$groups);
        $this->display('index');
    }
    /**
     * 单个部门显示员工
     */
    public function indexlist(){
        $groupModel=new GroupModel();
        $groupmembers = $groupModel->getList($_GET['id']);
//        dump($groupmembers);die;
        $this->assign('rows',$groupmembers);
        $this->display('indexlist');
    }
    /**
     * 添加部门
     */
    public function add(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            //接收数据
            $data=$_POST;
            //处理数据
            $groupModel=new GroupModel();
            $result=$groupModel->add($data);
            if($result===false){
                $this->redirect("index.php?p=Admin&c=Group&a=add",'添加失败'.$groupModel->getError(),'3');
            }
            //显示页面
            $this->redirect("index.php?p=Admin&c=Group&a=index");
        }
        //显示页面
        $this->display("add");
    }

    /**
     * 回显更新部门
     */
    public function edit(){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            //接收数据
            $data=$_POST;
            $id= $data['group_id'];
            //处理数据
            $groupModel=new GroupModel();
            $result=$groupModel->update($data,$id);
            if($result===false){
                $this->redirect("index.php?p=Admin&c=Group&a=edit",'更新失败'.$groupModel->getError(),'3');
            }
            //显示页面
            $this->redirect("index.php?p=Admin&c=Group&a=index");
        }
        //接收数据
        $id=$_GET['id'];
        //处理数据
        $groupModel=new GroupModel();
        $row=$groupModel->getOne($id);
        //显示页面
        $this->assign('row',$row);
        $this->display("edit");
    }
    /**
     * 删除部门
     */
    public function delete(){
        //接收数据
        $id=$_GET['id'];
        //处理数据
        $groupModel=new GroupModel();
        $result=$groupModel->delete($id);
        if($result===false){
            $this->redirect("index.php?p=Admin&c=Group&a=index",'删除失败'.$groupModel->getError(),'3');
        }
        //显示页面
        $this->redirect("index.php?p=Admin&c=Group&a=index");
    }
}