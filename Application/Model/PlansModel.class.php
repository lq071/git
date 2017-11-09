<?php
class PlansModel extends Model
{
    /**
     * 获取所有数据
     * @return array
     */
    public function getAll($status){
        //准备sql
        $sql="select * from `plans`";
        if ($status==1){
            $sql .= " WHERE `status` = 1 ";
        }
        $sql .=" ORDER BY `money` DESC";
        //执行sql
        $rows=$this->db->fetchAll($sql);
        //返回结果
        return $rows;
    }

    /**
     * 添加数据
     * @param $data  需要添加的数据
     * @return bool|mysqli_result
     */
    public function add($data){
        //准备sql
        //执行sql
        $result=$this->insertDate($data);
        //返回结果
        return $result;
    }

    /**
     * 根据id 查询一条数据
     * @param $id
     * @return array|null
     */
    public function getOne($id){
        //准备sql
        $sql="select * from plans where plan_id='{$id}'";
//        dump($sql);die;
        //执行sql
        $row=$this->db->fetchRow($sql);
        //返回结果
        return $row;
    }

    /**
     * 更新数据
     * @param $data 需要更新的数据
     * @return bool|mysqli_result
     */
    public function update($data){
        //准备sql
        $sql="update plans set 
`name`='{$data['name']}',
`des`='{$data['des']}',
`money`='{$data['money']}',
`status`='{$data['status']}',
`condition`='{$data['condition']}' 
where plan_id='{$data['id']}'
";
        //执行sql
        $result=$this->db->execute($sql);
        //返回结果
        return $result;
    }

    /**
     * 根据id删除数据
     * @param $id
     * @return bool|mysqli_result
     */
    public function delete($id){
        //准备sql
        $sql="delete from plans where plan_id={$id}";
        //执行sql
        $result=$this->db->execute($sql);
        //返回结果
        return $result;
    }
    /**
     * 获取有效套餐
     */
    public function getValid($condition){
        $sql = $this->db->mySelect('plans',$condition,['money','desc']);
        return $this->db->fetchAll($sql);
    }
}