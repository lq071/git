<?php

/**
 * 会员模型
 */
class UserModel extends Model
{
    /**
     *  获取全部数据
     * select * from `user` where XXX order by XXX limit $start,$pageSize;
     */
    public function getAll($condition=[],$page=1,$pageSize=10000000){
        $where = '';
        if(!empty($condition)){
            $where .= " WHERE " .implode(' AND ',$condition);
        }

        //分页
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM `user`".$where);   //总记录数
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
        $sql = $this->db->mySelect('user');
        $sql .=$where ." ORDER BY `money` desc LIMIT $start,$pageSize";
//        dump($sql);
        //执行 解析 返回
        $rows = $this->db->fetchAll($sql);
        return [$rows,$total];
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getOne($user_id){
        //SQL语句
        $sql = "SELECT * FROM `user` WHERE `user_id`={$user_id} LIMIT 0,1";
//        dump($sql);
        //执行 解析 返回
        $row = $this->db->fetchRow($sql);
        return $row;
    }

    /**
     * @param $username
     * @return array|null
     */
    public function getUser($username){
        //SQL语句
        $sql = "SELECT * FROM `user` WHERE `username` LIKE '{$username}' LIMIT 0,1";
        //$sql = "SELECT * FROM `user` WHERE `username` LIKE '{$username}' OR `realname` LIKE '{$username}' LIMIT 0,1";
//        dump($sql);
        //执行 解析 返回
        $row = $this->db->fetchRow($sql);
        return $row;
    }

    /**
     * @param $date
     * @param $id
     */
    public function update($date){
        //SQL语句
        $sql = $this->updateDate($date);
    }

    /**
     * @param $id
     */
    public function delete($id){
        //SQL语句
        $sql = "DELETE FROM `user` WHERE `user_id`='$id'";
        //执行
        $this->db->execute($sql);
    }
    public function update1($date){
        $sql= "update `user` set `mark`='{$date['mark']}' where `user_id`= '{$date['user_id']}'";
        $this->db->execute($sql);
    }
}
