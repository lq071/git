<?php


class PointsController extends PlatformController
{
    public function index(){
        //接收数据
        //处理数据
        $pointsModel=new PointsModel();
        //分页
        $page = $_GET['page'] ?? 1;
        $pageSize =5;
        $condition = [];
        //用户信息
        $result = $pointsModel->getAll01($condition,$page,$pageSize);
        list($rows,$total) = $result;

//        dump($groups);die;
        unset($_REQUEST['page']);
        $pageShow = PageModel::showPage($page,$pageSize,$total,http_build_query($_REQUEST));
        //显示页面
        $this->assign('html',$pageShow);
        $this->assign('rows',$rows);
        $this->display('index');
    }
    public function add(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //接收数据
            $data=$_POST;
            if(empty($data['num'])){
                $data['num']=1;
            }
            $image=$_FILES['image'];
            //图片处理
            if ($image['error'] != 4){
                //调用文件上传模板 UploadModel上的upload()方法,传入$file和要保存的目录,将保存后的路径返回
                $upload = new UploadModel();
                $image_path = $upload->upload($image,'./Uploads/Admin/Points');
                $data['image'] = $image_path;
            }
            //处理数据
            $pointsModel=new PointsModel();
            $result=$pointsModel->add($data);
            if($result===false){
                $this->redirect('index.php?p=Admin&c=Points&a=add','添加失败'.$pointsModel->getError(),'3');
            }
            //显示页面
            $this->redirect('index.php?p=Admin&c=Points&a=index');
        }
        //接收数据
        //处理数据
        //显示页面
        $this->display('add');
    }
    public function edit(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
//            dump($_POST);die;
            //接收数据
            $data=$_POST;
            $image=$_FILES['image'];
            //图片处理
            //设置一个默认图片路径在这里
            $data['image'] = $data['oldimage'];
            if ($image['error'] != 4){
                //调用文件上传模板 UploadModel上的upload()方法,传入$file和要保存的目录,将保存后的路径返回
                $upload = new UploadModel();
                $image_path = $upload->upload($image,'./Uploads/Admin/User');
                $data['image'] = $image_path;
            }
            //处理数据
            $pointsModel=new PointsModel();
            $result=$pointsModel->update($data);
            //显示页面
            if($result===false){
                $this->redirect("index.php?p=Admin&c=Points&a=edit& id={$data['id']}",'更新失败'.$pointsModel->getError(),'3');
            }
            //显示页面
            $this->redirect('index.php?p=Admin&c=Points&a=index');
        }
        //接收数据
        $id=$_GET['id'];
        //处理数据
        $pointsModel=new PointsModel();
        $row=$pointsModel->getOne($id);
        //显示页面
        $this->assign('row',$row);
        $this->display('edit');
    }
    public function delete(){
        //接收数据
        $id=$_GET['id'];
        //处理数据
        $pointsModel=new PointsModel();
        $result=$pointsModel->delete($id);
        //显示页面
        if($result===false){
            $this->redirect("index.php?p=Admin&c=Points&a=index",'删除失败'.$pointsModel->getError(),'3');
        }
        //显示页面
        $this->redirect('index.php?p=Admin&c=Points&a=index');
    }
}