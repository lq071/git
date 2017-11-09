<?php

/**
 * 会员控制器
 */
class UserController extends PlatformController
{
    private $user;   //保存对象

    public function __construct()
    {
        parent::__construct();
        $this->user = new UserModel();
    }

    /**
     *
     */
    public function index(){
//        dump($_REQUEST['keyword']);
        //接收数据
        //处理数据
            //分页
        $page = $_GET['page'] ?? 1;
        $pageSize =7;

            //搜索
        $condition = [];
        if (!empty($_REQUEST['sex'])){
            $condition[] = "`sex`='{$_REQUEST['sex']}'";
        }
        if (!empty($_REQUEST['is_vip'])){
            $condition[]= "`is_vip`='{$_REQUEST['is_vip']}'-1";
        }
        if (!empty($_REQUEST['keyword'])){
            $condition[]= "`username` LIKE '%{$_REQUEST['keyword']}%' OR `realname` LIKE '%{$_REQUEST['keyword']}%' OR `telephone` LIKE '%{$_REQUEST['keyword']}%'";
        }

            //用户信息
        $result = $this->user->getAll($condition,$page,$pageSize);
        list($rows,$total) = $result;

            //删除$_REQUEST中多余的page
        unset($_REQUEST['page']);
            //调用分页工具条
        $pageShow = PageModel::showPage($page,$pageSize,$total,http_build_query($_REQUEST));

        //显示页面
        $this->assign('html',$pageShow);
        $this->assign('rows',$rows);
        $this->display('index');
    }

    /**
     *  新增会员
     */
    public function insert(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//            dump($_POST);
//            dump($_FILES);
//            die;
            //接收数据
            $date = $_POST;
            $file = $_FILES['photo'];

            //处理数据
                //>>用户名不能为空
            if (empty($date['username'])){
                $this->redirect("index.php?p=Admin&c=User&a=insert",'用户名不能为空',2);
            }
                //可以要求有相同的名字,但不能有相同的用户名
            $checkUser = $this->user->getUser($date['username']);
//            dump($date['username']);
//            dump($checkUser);die;
            if (isset($checkUser)){
                $this->redirect("index.php?p=Admin&c=User&a=insert",'该用户名已存在',2);
            }
                //>>新旧密码对比
            if (empty($date['password']) || empty($date['repassword'])){
                $this->redirect("index.php?p=Admin&c=User&a=insert",'密码不能为空',2);
            }
                //>>第一次密码和第二次保持一致
            if ($date['password'] != $date['repassword']){
                $this->redirect("index.php?p=Admin&c=User&a=insert",'两次输入密码不能一致',2);
            }
                //>>名字不能为空
            if (empty($date['realname'])){
                $this->redirect("index.php?p=Admin&c=User&a=insert",'姓名不能为空',2);
            }
                //>>电话不能为空
            if (empty($date['telephone'])){
                $this->redirect("index.php?p=Admin&c=User&a=insert",'电话不能为空',2);
            }
            //设置为VIP
            if ($date['is_vip'] == 1){
                $date['level'] = 'VIP1';
                $date['rebate'] = 10;
            }
                //>>图片处理
                    //设置一个默认图片路径在这里
            $date['photo'] = "./Uploads/Admin/User/20171103175639.jpg";
            if ($file['error'] != 4){
                    //调用文件上传模板 UploadModel上的upload()方法,传入$file和要保存的目录,将保存后的路径返回
                $upload = new UploadModel();
                $logo = $upload->upload($file,'./Uploads/Admin/User');
                $date['photo'] = $logo;
            }
                    //>>调用UserModel上的insert()方法,传入$date
            $this->user->insertDate($date);

            //显示页面
            $this->jump('添加成功','index.php?p=Admin&c=User&a=index');
        }
        //接收数据
        //处理数据
        //显示页面
        $this->display('insert');
    }

    /**
     * 修改回显
     */
    public function update(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//            dump($_POST);
//            dump($_FILES);
//            die;
            //接收数据
            $date = $_POST;
            $file = $_FILES['photo'];

            //处理数据
                //>>用户名不能为空
            if (empty($date['username'])){
                $this->redirect("index.php?p=Admin&c=User&a=update&user_id={$_POST['id']}",'用户名不能为空',2);
            }
                //>>旧密码不为空,判断新密码和确认密码
            if (!empty($date['oldpassword'])){
                    //>>判断旧密码是否正确
                if (md5($date['oldpassword'].'abcd123')!= $date['oldpwd']){
                    $this->redirect("index.php?p=Admin&c=User&a=update&user_id={$_POST['id']}",'旧密码输入不正确',2);
                }
                    //>>新旧密码对比
                if (empty($date['password']) || empty($date['repassword'])){
                    $this->redirect("index.php?p=Admin&c=User&a=update&user_id={$_POST['id']}",'密码不能为空',2);
                }
                    //>>第一次密码和第二次保持一致
                if ($date['password'] != $date['repassword']){
                    $this->redirect("index.php?p=Admin&c=User&a=update&user_id={$_POST['id']}",'两次输入密码不一致',2);
                }
            }else{
                unset($date['repassword']);
                unset($date['password']);
            }
                //>>名字不能为空
            if (empty($date['realname'])){
                $this->redirect("index.php?p=Admin&c=User&a=update&user_id={$_POST['id']}",'姓名不能为空',2);
            }
                //>>电话不能为空
            if (empty($date['telephone'])){
                $this->redirect("index.php?p=Admin&c=User&a=update&user_id={$_POST['id']}",'电话不能为空',2);
            }

                //>>图片处理
                    //设置一个默认图片路径在这里
            $date['photo'] = $date['oldphoto'];
            if ($file['error'] != 4){
                    //调用文件上传模板 UploadModel上的upload()方法,传入$file和要保存的目录,将保存后的路径返回
                $upload = new UploadModel();
                $logo = $upload->upload($file,'./Uploads/Admin/User');
                $date['photo'] = $logo;
            }
                //>>调用UserModel上的update()方法,传入$date
            $this->user->updateDate($date);

            //显示页面
            $this->jump('修改成功','index.php?p=Admin&c=User&a=index');
        }

        //GET方式
        //接收数据
        $user_id = $_GET['user_id'];
        //处理数据
        $row = $this->user->getOne($user_id);
        //显示页面
        $this->assign('row',$row);
        $this->display('update');
    }
    public function delete(){
        //接收数据
        $user_id = $_GET['user_id'];
        //处理数据
            //先根据$user_id去查 histories 中 的数据
            $history = new  HistoryModel();
            $histories = $history->getAll()[0];
//            dump($histories);
//            dump($user_id);die;
            //>>做判断,有消费记录的会员不能被删除,即会员的id会出现在消费的表中
            foreach ($histories as $hist){
//                dump($hist);
                if ($user_id == $hist['user_id']){
                    $this->redirect("index.php?p=Admin&c=User&a=index",'该会员存在消费记录,不能删除',2);
                }
            }
        $this->user->delete($user_id);
        //显示页面
        $this->jump('成功删除','index.php?p=Admin&c=User&a=index');
    }
}