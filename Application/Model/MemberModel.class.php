<?php

/**
 * 成员模块
 */
class MemberModel extends Model
{
    /**
     *  获取全部数据
     * select * from `member` where XXX order by XXX limit $start,$pageSize;
     */
    public function getAll($condition=[],$page=1,$pageSize=100000){
        $where = '';
        if(!empty($condition)){
            $where .= " WHERE " .implode(' AND ',$condition);
        }

        //分页
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM `member`".$where);   //总记录数
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
        $sql = $this->db->mySelect('member');
        $sql .=$where ." LIMIT $start,$pageSize";
//        dump($sql);
        //执行 解析 返回
        $rows = $this->db->fetchAll($sql);
        return [$rows,$total];
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getOne($member_id){
        //SQL语句
        $sql = "SELECT * FROM `member` WHERE `member_id`={$member_id} LIMIT 0,1";
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
        //执行
        $this->db->execute($sql);
    }
    public function delete($id){
        //SQL语句
        $sql = "DELETE FROM `member` WHERE `member_id`='$id'";
        //执行
        $this->db->execute($sql);
    }
    /**
     * 获取所有员工
     * @return array
     */
    public function getMember(){
        //准备sql
        $sql="select * from `member`";
        //执行sql
        $members=$this->db->fetchAll($sql);
        //返回结果
        return $members;
    }
}