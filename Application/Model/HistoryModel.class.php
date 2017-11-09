<?php

/**
 * Created by PhpStorm.
 * User: eachone
 * Date: 2017/11/3
 * Time: 19:21
 */
class HistoryModel extends Model
{
    /**
     * 获取全部数据
     */
    /**
     *  获取全部数据
     * select * from `user` where XXX order by XXX limit $start,$pageSize;
     */
    public function getAll($condition=[],$page=1,$pageSize=1000000){
        $where = '';
        if(!empty($condition)){
            $where .= " WHERE " .implode(' AND ',$condition);
        }

        //分页
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM `history`".$where);   //总记录数
//       dump($total);
        //基础分页数据计算
        $totalPage = ceil($total/$pageSize);    //总页数
        //考虑到有完全不符合搜索条件时,此时 $total =0 ,导致了$totalPage为 0,所以必须设置 $totalPage 做判断
//        dump($totalPage);
        //限制$page越界
        $page = $page > $totalPage ? $totalPage : $page;
        $page = $page < 1 ? 1 : $page;
        //计算当前页和显示记录数的关系 $start代表limit从哪个开始
        $start = ($page - 1)*$pageSize;

        //写SQL
        $sql = $this->db->mySelect('history');
        $sql .=$where ." ORDER BY history_id desc LIMIT $start,$pageSize";
//        dump($sql);
        //执行 解析 返回
        $rows = $this->db->fetchAll($sql);
        return [$rows,$total];
    }

    /**
     * 查询充值/消费最多的人
     * SELECT SUM(amount) FROM `history` WHERE `type` = 2 AND `user_id` = 1;
     * @param $dates
     * @param $type
     * @return array
     */
    public function recharge($dates,$type){
        $result = [];
        foreach ($dates as $date){
            //写SQL
            $sql = "SELECT SUM(amount) FROM `history` WHERE `type` =$type  AND `user_id`='{$date['user_id']}'";
            //执行
            $sumMoney = $this->db->fetchColumn($sql);
            $date['sumMoney'] =$sumMoney;
            $result[] = $date;
        }
        return $result;
    }

    /**
     * SELECT COUNT(member_id) FROM `history` WHERE member_id=1
     * @param $dates
     */
    public function serviceStar($dates){
        $result = [];
        foreach ($dates as $date){
            //写SQL
            $sql = "SELECT COUNT(member_id) FROM `history` WHERE `member_id`='{$date['member_id']}'";
            //执行
            $sumService = $this->db->fetchColumn($sql);
            $date['sumService'] =$sumService;
            $result[] = $date;
        }
        return $result;
    }

    /**
     * @param $date
     */
    public function insert($date){
        //写SQL
        $sql = $this->db->myInsert('history',$date);
        //执行
        $this->db->execute($sql);
    }
    public function delete($member_id){
        //写SQL
        $sql = "SELECT * FROM `history` WHERE `member_id`='{$member_id}'";
        //执行  解析  返回
        return $this->db->fetchRow($sql);
    }

    /**
     * 充值消费排行
     * @return array
     */
    public function TypeTop($type){
        if($type==1){
            //准备sql
            $sql = " select v.* from (select u.username,sum(amount) amount from history h,`user` u where h.user_id=u.user_id and type=1 group by h.user_id order by amount desc) v limit 3";
        }elseif($type==0){
            $sql = " select v.* from (select u.username,sum(amount) amount from history h,`user` u where h.user_id=u.user_id and type=0 group by h.user_id order by amount desc) v limit 3";
        }
        //执行sql
        $result=$this->db->fetchAll($sql);
        //返回结果
        return $result;
    }
    /**
     * 美发师排行
     * @return array
     */
    public function memberTop(){
        //准备sql
        $sql=" select v.* from (select m.username,count(h.member_id) num from history h,member m where h.member_id=m.member_id  group by h.member_id order by num desc) v limit 3";
        //执行sql
        $result=$this->db->fetchAll($sql);
        //返回结果
        return $result;
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getOne($condition=[],$page,$pageSize=20){
        //$sql = "select * from `history` where user_id='{$id}'";

        $where = '';
        if(!empty($condition)){
            $where .= " WHERE " .implode(' AND ',$condition);
        }

        //分页
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM `history` h,`member` m".$where);   //总记录数
//       dump($total);
        //基础分页数据计算
        $totalPage = ceil($total/$pageSize);    //总页数
        //考虑到有完全不符合搜索条件时,此时 $total =0 ,导致了$totalPage为 0,所以必须设置 $totalPage 做判断
//        dump($totalPage);
        //限制$page越界
        $page = $page > $totalPage ? $totalPage : $page;
        $page = $page < 1 ? 1 : $page;
        //计算当前页和显示记录数的关系 $start代表limit从哪个开始
        $start = ($page - 1)*$pageSize;

        //写SQL
        $sql = "select m.username,h.* from `history` h,`member` m ".$where ." order by h.`time` desc";
        $sql .= " LIMIT $start,$pageSize";
//        dump($sql);
        //执行 解析 返回
        $rows = $this->db->fetchAll($sql);
        return [$rows,$total];


        return $this->db->fetchAll($sql);
    }

}