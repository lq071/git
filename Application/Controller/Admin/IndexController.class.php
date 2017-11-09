<?php

/**
 * 首页控制器
 */
class IndexController extends PlatformController
{
    /**
     * 首页
     */
    public function index(){
        //接收数据
        //处理数据
        //会员名 根据$rows['user_id']查会员名
            $users = new UserModel();
            $Resusers = $users->getAll()[0];

        //服务员名
            $members = new MemberModel();
            $ResMembers = $members->getAll()[0];
            $countMember = count($ResMembers);
            $history = new HistoryModel();
        //查询充值金额最多的人
            $rechargeTop = $history->recharge($Resusers,1);
        //调用排序方法
            $rechargeTop= $this->arr_sort($rechargeTop,'sumMoney','desc');
//        dump($rechargeTop);die;

        //查询消费金额最多的人
            $spendTop = $history->recharge($Resusers,0);
        //调用排序方法
            $spendTop= $this->arr_sort($spendTop,'sumMoney','desc');

        //查询服务之星
            $serviceTop = $history->serviceStar($ResMembers);
//        dump($serviceTop);die;
        //调用排序方法
            $serviceTop= $this->arr_sort($serviceTop,'sumService','desc');

        //组名信息
            $group = new GroupModel();
            $groups = $group->getAll();
            $countGroup = count($groups);
        //显示页面
//        dump($Resusers);die;
            $this->assign('Resusers',$Resusers);
            $this->assign('countMember',$countMember);
            $this->assign('countGroup',$countGroup);
            $this->assign('rechargeTop',$rechargeTop);
            $this->assign('spendTop',$spendTop);
            $this->assign('serviceTop',$serviceTop);
            $this->assign('groups',$groups);
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

    /**
     * 充值
     */
    public function insert(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//            dump($_POST);die;
//            接收数据
            $date = $_POST;

            //处理数据
            //>>先判断名字

            if (empty($date['user_id'])){
                $this->redirect('index.php?p=Admin&c=Index&a=insert','未选择会员',2);
            }

            if (empty($date['money'])){
                //>>充值类型
                $date['money'] = 0;
            }

            //>>显示充值活动  将充值金额一一对应
            $checkRecharge = new RechargeModel();
            //$donation为赠送金额
            $donation = $checkRecharge->donation($date['money']);
//            dump($donation);
            //充值总金额
            $date['remainder'] = $donation + $date['money'];


            //调用用户Model
            $changeUser = new UserModel();
            $result = $changeUser->getOne($date['user_id']);
            //将用户充值信息更新到user表中
            $user['money'] =$result['money'] + $date['remainder']; //更新余额
            $user['id'] = $result['user_id'];

                //将消费记录到消费表中
            $date['type'] = 1;
            $date['time']=time();
            $date['amount']=$date['money'];
            $date['remainder']=$user['money'];

            $changeHistory = new HistoryModel();

            //根据用户的信息查到他的消费总金额,升级vip
//            dump($result);die;
            $Onerecharge = $changeHistory->recharge([$result],1)[0];
//            dump($Onerecharge);
            $checkVip = new VipModel();
            $Vips = $checkVip->getAll();

            if ($date['money'] > $Vips[0]['proviso']){
                //>>大于500,自动成为VIP
                $user['is_vip'] = 1;
                $user['level'] = $Vips[0]['level'];
            }
//                dump($Vips[0]['proviso']);die;
            foreach ($Vips as $vip){
                if ($Onerecharge['sumMoney']  > $vip['proviso']){
//                    $user['id'] = $result['user_id'];
                    $user['level'] = $vip['level'];
                    $user['is_vip'] = 1;
                    $user['rebate'] = $vip['discount'];
                }
            }
//            dump($Vips);
//            dump($user);die;
            $changeUser->updateDate($user);

            $changeHistory->insertDate($date);
            //显示页面
            $this->jump('充值成功','index.php?p=Admin&c=Index&a=index');
        }

        //GET方式
            //接收数据
            //处理数据
                //>>显示服务员
                $checkMember = new MemberModel();
                $members = $checkMember->getAll()[0];
//                dump($members);
        $checkRecharge = new RechargeModel();
        $recharges = $checkRecharge->getAll()[0];

        $users = new UserModel();
        $Resusers = $users->getAll()[0];
//                dump($recharges);
            //显示页面
        $this->assign('members',$members);
        $this->assign('Resusers',$Resusers);
        $this->assign('recharges',$recharges);
        $this->display('insert');
    }

    /**
     * 消费
     */
    public function update(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//            dump($_POST);die;
            //接收数据
            $date = $_POST;
            //处理数据
                //>>判断是否选择了服务员
            if (empty($date['member_id'])){
                $this->redirect('index.php?p=Admin&c=Index&a=update','未选择已服务人员',2);
            }
                //>>判断是否选择了套餐   选择了获取对应的金额
            if (empty($date['plan_id'])){
                $this->redirect('index.php?p=Admin&c=Index&a=update','没选择服务套餐',2);
            }

            $checkPlans = new PlansModel();
            $date['amount'] = $checkPlans->getOne($date['plan_id'])['money'];
//            dump($date['money']);die;
            //>.对应套餐的金额
//            dump($date['amount']);die;

            //>>先判断名字,调用UserModel上面的getUser()方法,传入$username
            $checkUser = new UserModel();
            $result = $checkUser->getUser($date['username']);
//            dump($result['money']);die;
            if (empty($result)){
                //>>可要求注册
                $this->redirect('index.php?p=Admin&c=Index&a=update','暂未成为会员,请先注册',5);
            }
            $date['user_id'] = $result['user_id'];  //>>需要在消费金额表中使用

            //积分换算
            $User['mark']=(integer)$date['amount'] + $result['mark'];


//            dump($date['amount']);
            //>>VIP会员消费自动打折
            /*$result['rebate']=0.5;  //折扣*/
            if (isset($result['is_vip']) && $result['is_vip'] == 1){
                $date['amount'] = $date['amount'] * ($result['rebate'] *0.1);  //>>打折后的金额
            }
//            dump($date['amount']);
//            dump($result['rebate'] *0.1);
//            die;




                //>>判断是否选择了代金券
            //是,根据 输入的代金券代码 和 该代金券该用户是否能使用 是否能使用
            //代金券出错或者金额不足,提示
            //代金券正确并且金额满足,使用代金券,直到代金券余额为0,状态标记为 已使用
            if (!empty($date['code'])){
                //有代金券
                $checkCode = new CodesModel();
                $code = $checkCode->inquiry($date['code']);
//                dump($code);die;
                $date['code_id'] = $code['code_id'];
                if (empty($code)){  //>>输入的代金券代码不能正确查找到
                    $this->redirect('index.php?p=Admin&c=Index&a=update','代金券代码不存在',2);
                }

                if ($code['user_id'] != $result['user_id']){
                    $this->redirect('index.php?p=Admin&c=Index&a=update','无权限使用该代金券',2);
                }

                if ($code['status'] == 1){
                    $this->redirect('index.php?p=Admin&c=Index&a=update','该代金券已无余额',2);
                }



                        //代金券充足
                        //代金券充足
                        //代金券充足
//                dump($code['money']);die;
                if ($date['amount'] <= $code['money']){
                    //如果 需要的金额 小于 代金券的金额 ,显示消费成功 将代金券的金额更新,
                    //判断 是否有输入金额
                        //>>不为空,将输入金额充入会员表
                    if (!empty($date['money'])){    //有输入
                        $User['id'] = $result['user_id'];
                        $User['money'] = $result['money'] + $date['money'];
                        $date['remainder'] = $User['money'];
                        $checkUser->update($User);
                    }
                    //>>更新代金券金额
//                    dump($code['money']);
//                    dump($code['amount']);die;
                    $code['money'] = $code['money'] - $date['amount'];
                    $checkCode->updateMoney($code);
                    $date['remainder'] = $result['money'];
                }



                        //代金券不足
                        //代金券不足
                        //代金券不足
                else{
                    //需要的金额 大于 代金券的金额   判断是否输入金额


                            //无输入金额
                            //无输入金额
                            //无输入金额
                    if (empty($date['money'])){ //无输入金额
                        //无输入金额
                            //检查用户余额
                        //判断会员的余额和代金券的余额的和 是否 小于消费金额
                        if (($result['money']+$code['money'])<$date['amount']){
                            //小于
                            $this->redirect('index.php?p=Admin&c=Index&a=update','账户余额或代金券余额不足,请先充值',2);
                        }
                            //大于
                         //将代金券的余额设为0   , 并且更新用户的余额  跟新到消费记录

                        $checkCode->update1(1,0,$date['code_id']);
                        $User['id'] = $result['user_id'];
                        $User['money'] = $result['money']-$date['amount']+$code['money'];
                        $checkUser->update($User);
                        $date['remainder'] =$User['money'];
                    }
                    else{ //有输入金额

                            //判断输入的金额与代金券的金额 的和 与消费的金额
                        if (($date['money'] + $code['money']) < $date['amount']){
                                //使用会员余额
                            if (($result['money'] + $date['money'] + $code['money']) < $date['amount']){    //都不足
                                $this->redirect('index.php?p=Admin&c=Index&a=update','会员的余额或代金券或输入的金额不足',2);
                            }
                            $checkCode->update1(1,0,$date['code_id']);
                            $User['id'] = $result['user_id'];
                            $User['money'] = $result['money']+($code['money']+$date['money']-$date['amount']);
                            $checkUser->update($User);
                            $date['remainder'] =$User['money'];
                        }
                            //>>输入的金额与代金券的金额 大于 消费的金额   将多余的钱充入会员表
                        //并且代金券余额为0

                        $checkCode->update1(1,0,$date['code_id']);
                        $User['id'] = $result['user_id'];
                        $User['money'] = $result['money']+($date['money']+$code['money'] -$date['amount']);
                        $checkUser->update($User);
                        $date['remainder'] =$User['money'];
                    }
                }
            }


            //不使用代金券
            //不使用代金券
            //不使用代金券
            else{
                //不使用代金券

                //没有输入金额
                //没有输入金额
                //没有输入金额
                if (empty($date['money'])){
                    //既没有输入金额,又不使用代金券  使用账户余额
                        //>>会员的余额 小于 套餐金额
                    if ($date['amount'] > $result['money']){
                        //没有输入金额,又不使用代金券  使用账户余额 不足
                        $this->redirect('index.php?p=Admin&c=Index&a=insert','账户余额不足,请充值',4);
                    }
                        //没有输入金额,又不使用代金券  使用账户余额 足够
                        //>>会员的余额 大于 套餐金额  消费  更新会员信息
                    $User['id'] = $result['user_id'];
                    $User['money'] = ($result['money'] -$date['amount']);
                    $checkUser->update($User);
                    $date['remainder'] =$User['money'];
                }

                //有输入金额
                //有输入金额
                //有输入金额
                else{
                    //有输入金额,又不使用代金券
                    //>>输入的金额 大于 消费金额
                    if ($date['money'] > $date['amount']){
                        //>>输入的金额 大于 消费金额   将多余的钱存入 会员表
                        $User['id'] = $result['user_id'];
                        $User['money'] = $date['money'] - $date['amount'];
                        $checkUser->update($User);
                        $date['remainder'] =$User['money'];
                    }else{
                        //>>输入的金额 小于 消费金额   使用会员余额
                            //输入的金额+会员余额 小于  消费金额
                        if (($date['money'] + $result['money']) < $date['amount']){
                            $this->redirect('index.php?p=Admin&c=Index&a=insert','输入金额或账户余额不足',2);
                        }
                            //输入的金额+会员余额 大于  消费金额  更新用户表
                        $User['id'] = $result['user_id'];
                        $User['money'] = $result['money'] -($date['amount'] - $date['money']);
                        $checkUser->update($User);
                        $date['remainder'] =$User['money'];
                    }
                }
            }

//            $date['remainder'] = $User['money'];
//            dump($date['remainder']);die;



//            dump($date);die;
            //将消费记录到消费表中
            $date['type'] = 0;
            $date['time']=time();
//            dump($User['money']);
//            dump($date);
            $changeHistory = new HistoryModel();
            $changeHistory->insertDate($date);
            //显示页面
            $this->jump('消费成功','index.php?p=Admin&c=Index&a=index');
        }

        //GET方式
        //接收数据
        //处理数据
        //>>显示服务员
        $checkMember = new MemberModel();
        $members = $checkMember->getAll()[0];
//                dump($members);
        $checkPlans = new PlansModel();
        $condition = " where `status`= 1 ";
        $plans = $checkPlans->getValid($condition);

//                dump($recharges);
        //显示页面
        $this->assign('members',$members);
        $this->assign('plans',$plans);
        $this->display('update');
    }
}