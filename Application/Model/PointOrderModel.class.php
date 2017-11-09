<?php


class PointOrderModel extends Model
{
    /**
     * 获取所有数据
     * @param $page
     * @param $pageSize
     * @return array
     */
    public function getAll($page,$pageSize,$where=''){

        //分页
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM `article`");   //总记录数
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

        //准备sql
        $sql="select o.*, u.username, p.goods from `pointOrder` o,`user` u,`points` p where o.user_id = u.user_id and o.point_id = p.point_id {$where} ORDER BY id DESC";
        $sql .= " LIMIT {$start},{$pageSize}";
        //执行sql
        $rows=$this->db->fetchAll($sql);
        //返回结果
        return [$rows,$total];
    }
    /**
     * 根据 id 修改状态
     * @param $data
     * @return bool|mysqli_result
     */
    public function updateStatus($data){
        //准备sql
        $sql = "update `pointOrder` set status={$data['status']} where id = {$data['id']}";
        //执行sql
        $result = $this->db->execute($sql);
        //返回结果
        return $result;
    }
    /**
     * 添加数据
     * @param $data
     * @return bool|mysqli_result
     */
    public function add($data){
        //准备sql
       $result = $this->insertDate($data);
        //返回结果
        return $result;
    }


}