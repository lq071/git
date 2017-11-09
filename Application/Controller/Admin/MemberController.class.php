<?php

/**
 * 员工控制器
 */
class MemberController extends PlatformController
{
    private $member;   //保存对象

    public function __construct()
    {
        parent::__construct();
        $this->member = new MemberModel();
    }

    /**
     *
     */
    public function index(){
//        dump($_REQUEST['keyword']);
        //接收数据
            //分页
        $page = $_GET['page'] ?? 1;
        $pageSize =10;

            //搜索
        $condition = [];
        //处理数据
        if (!empty($_REQUEST['sex'])){
            $condition[] = "`sex`='{$_REQUEST['sex']}'";
        }
        if (!empty($_REQUEST['is_admin'])){
            $condition[]= "`is_admin`='{$_REQUEST['is_admin']}'-1";
        }
        if (!empty($_REQUEST['keyword'])){
            $condition[]= "`username` LIKE '%{$_REQUEST['keyword']}%' OR `realname` LIKE '%{$_REQUEST['keyword']}%' OR `telephone` LIKE '%{$_REQUEST['keyword']}%'";
        }
            //用户信息
        $result = $this->member->getAll($condition,$page,$pageSize);
        list($rows,$total) = $result;
            //组名信息
        $group = new GroupModel();
        $groups = $group->getAll();

//        dump($groups);die;
        unset($_REQUEST['page']);
        $pageShow = PageModel::showPage($page,$pageSize,$total,http_build_query($_REQUEST));
        //显示页面
        $this->assign('html',$pageShow);
        $this->assign('groups',$groups);
        $this->assign('rows',$rows);
        $this->display('index');

    }

    /**
     *
     */
    public function insert(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//            dump($_POST);
//            dump($_FILES);
            //接收数据
            $date = $_POST;
            $file = $_FILES['photo'];
            //处理数据
                //>>用户名不能为空
            if (empty($date['username'])){
                $this->redirect("index.php?p=Admin&c=Member&a=insert",'用户名不能为空',2);
            }
                //>>新旧密码对比
            if (empty($date['password']) || empty($date['repassword'])){
                $this->redirect("index.php?p=Admin&c=Member&a=insert",'密码不能为空',2);
            }
                //>>第一次密码和第二次保持一致
            if ($date['password'] != $date['repassword']){
                $this->redirect("index.php?p=Admin&c=Member&a=insert",'两次输入密码不能一致',2);
            }
                //>>名字不能为空
            if (empty($date['realname'])){
                $this->redirect("index.php?p=Admin&c=Member&a=insert",'姓名不能为空',2);
            }
                //>>电话不能为空
            if (empty($date['telephone'])){
                $this->redirect("index.php?p=Admin&c=Member&a=insert",'电话不能为空',2);
            }
                //>>判断部门是否存在
            if (empty($date['group_id'])){
                $this->redirect("index.php?p=Admin&c=Member&a=insert",'未选择所在部门',2);
            }
                //>>图片处理
                    //设置一个默认图片路径在这里
            $date['photo'] = "./Uploads/Admin/Member/5f5c89a082.jpg";
            if ($file['error'] != 4){
                //调用文件上传模板 UploadModel上的upload()方法,传入$file和要保存的目录,将保存后的路径返回
                $upload = new UploadModel();
                $logo = $upload->upload($file,'./Uploads/Admin/Member');
                $date['photo'] = $logo;
            }
            //>>调用memberModel上的insert()方法,传入$date
            $this->member->insertDate($date);
            //显示页面
            $this->jump('添加成功','index.php?p=Admin&c=Member&a=index');
        }
        //接收数据
        //处理数据
            //组名信息
        $group = new GroupModel();
        $groups = $group->getAll();
//        dump($groups);
            //显示页面
        $this->assign('groups',$groups);
        $this->display('insert');
    }
    public function update(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//            dump($_POST);
            $member_id = $_POST['id'];
//            dump($_FILES);
//            die;
            //接收数据
            $date = $_POST;
            $member_id = $_POST['id'];
            $file = $_FILES['photo'];
            //处理数据
            //>>用户名不能为空
            if (empty($date['username'])){
                $this->redirect("index.php?p=Admin&c=Member&a=update&member_id={$member_id}",'用户名不能为空',2);
            }

            //>>旧密码不为空,判断新密码和确认密码
            if (!empty($date['oldpassword'])){
                //>>判断旧密码是否正确
                if (md5($date['oldpassword'].'abcd123')!= $date['oldpwd']){
                    $this->redirect("index.php?p=Admin&c=Member&a=update&member_id={$member_id}",'旧密码输入不正确',2);
                }
                //>>新旧密码对比
                if (empty($date['password']) || empty($date['repassword'])){
                    $this->redirect("index.php?p=Admin&c=Member&a=update&member_id={$member_id}",'密码不能为空',2);
                }
                //>>第一次密码和第二次保持一致
                if ($date['password'] != $date['repassword']){
                    $this->redirect("index.php?p=Admin&c=Member&a=update&member_id={$member_id}",'两次输入密码不一致',2);
                }
            }else{
                unset($date['repassword']);
                unset($date['password']);
            }

            //>>名字不能为空
            if (empty($date['realname'])){
                $this->redirect("index.php?p=Admin&c=Member&a=update&member_id={$member_id}",'姓名不能为空',2);
            }
            //>>电话不能为空
            if (empty($date['telephone'])){
                $this->redirect("index.php?p=Admin&c=member&a=update&member_id={$member_id}",'电话不能为空',2);
            }
            //>>判断部门是否存在
            if (empty($date['group_id'])){
                $this->redirect("index.php?p=Admin&c=member&a=update&member_id={$member_id}",'未选择所在部门',2);
            }
            //>>图片处理
            //设置一个默认图片路径在这里
            $date['photo'] = $date['photo1'];
            if ($file['error'] != 4){
                //调用文件上传模板 UploadModel上的upload()方法,传入$file和要保存的目录,将保存后的路径返回
                $upload = new UploadModel();
                $logo = $upload->upload($file,'./Uploads/Admin/Member');
                $date['photo'] = $logo;
            }
            //>>调用MemberModel上的update()方法,传入$date
                 $this->member->updateDate($date);
            //显示页面
            $this->jump('修改成功','index.php?p=Admin&c=Member&a=index');

        }
        //接收数据
        $member_id = $_GET['member_id'];
        //处理数据
        $row = $this->member->getOne($member_id);
            //组名信息
        $group = new GroupModel();
        $groups = $group->getAll();

        //显示页面
        $this->assign('groups',$groups);
        $this->assign('row',$row);
        $this->display('update');
    }

    /**
     * 删除
     */
    public function delete(){
        //接收数据
        $member_id = $_GET['member_id'];
        //处理数据
            //>>有服务记录的员工账号不能被删除 --->根据id去服务记录表中查询
        $History = new HistoryModel();
        $result = $History->delete($member_id);
//        dump($result);
        if (!empty($result)){
            $this->redirect("index.php?p=Admin&c=member&a=index",'该员工不能被删除',2);
        }
            //>>否则删除
        $this->member->deleteDate($member_id);
        //显示页面
        $this->jump('成功删除','index.php?p=Admin&c=Member&a=index');
    }
}