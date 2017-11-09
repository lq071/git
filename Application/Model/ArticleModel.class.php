<?php

/**
 * 活动模型
 */
class ArticleModel extends Model
{
    /**
     *  获取全部数据
     * select * from `user` where XXX order by XXX limit $start,$pageSize;
     */
    public function getAll($condition=[],$page=1,$pageSize=10){
        $where = '';
        if(!empty($condition)){
            $where .= " WHERE " .implode(' AND ',$condition);
        }

        //分页
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM `article`".$where);   //总记录数
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
        $sql = $this->db->mySelect('article',$where,['end','desc']);
        $sql .= " LIMIT $start,$pageSize";
//        dump($sql);
        //执行 解析 返回
        $rows = $this->db->fetchAll($sql);
        return [$rows,$total];
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getOne($id){
        //写SQL
        $sql = "SELECT * FROM `article` WHERE `article_id`=$id";
        //执行 解析 返回
        return $this->db->fetchRow($sql);
    }

    /**
     * @param $date
     * @param $id
     */
    public function update($date){
        //写SQL
        $result = $this->updateDate($date);
        if ($result == false){
            $this->error = "修改失败";
            return false;
        }
    }
    /**
     * @param $id
     */
    public function delete($id){
        //SQL语句
        $sql = "DELETE FROM `article` WHERE `article_id`='$id'";
        //执行
        $this->db->execute($sql);
    }
}