<?php


class PointsModel extends Model
{
    public function getAll($where=''){
        //准备sql
        $sql = "select p.* from `points` p {$where}";
        //执行sql
        $rows=$this->db->fetchAll($sql);
        //返回结果
        return $rows;
    }
    public function add($data){
        //准备sql
        //执行sql
        $result=$this->insertDate($data);
        //返回结果
        return $result;
    }
    public function getOne($id){
        //准备sql
        $sql = "select p.* from `points` p where point_id={$id}";
        //执行sql
        $row=$this->db->fetchRow($sql);
        //返回结果
        return $row;
    }
    public function update($data){
        //准备sql
        //执行sql
        $result=$this->updateDate($data);
        //返回结果
        return $result;
    }
    public function delete($id){
        //准备sql
        $sql="delete from `points` where point_id={$id}";
        //执行sql
        $result=$this->db->execute($sql);
        //返回结果
        return $result;
    }

    /**
     *
     */
    public function getAll01($condition,$page,$pageSize){
        $where = '';
        if(!empty($condition)){
            $where .= " WHERE " .implode(' AND ',$condition);
        }

        //分页
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM `points`".$where);   //总记录数
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
        $sql = $this->db->mySelect('points',$where,['num','asc']);
        $sql .= " LIMIT $start,$pageSize";
//        dump($sql);
        //执行 解析 返回
        $rows = $this->db->fetchAll($sql);
        return [$rows,$total];
    }
}