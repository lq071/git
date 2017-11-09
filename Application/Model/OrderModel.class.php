<?php
class OrderModel extends Model
{
    /**
     * 获取所有数据
     * @return array
     */
    public function getAll(){
        //准备sql
        $sql="select o.* from `order` o";
        //执行sql
        $rows=$this->db->fetchAll($sql);
        //返回结果
        return $rows;
    }
    /**
     * 根据会员id获取所有数据
     * @return array
     */
    public function getAllById($user_id){
        //准备sql
        $sql="select * from `order` o where o.user_id = $user_id";
        //执行sql
        $rows=$this->db->fetchAll($sql);
        //返回结果
        return $rows;
    }
    /**
     * 添加数据
     * @param $data
     * @return bool|mysqli_result
     */
    public function add($data){
        //准备sql
        $sql="insert into `order` set 
`phone`='{$data['phone']}',
`realname`='{$data['realname']}',
`barber`='{$data['barber']}',
`content`='{$data['content']}',
`date`='{$data['date']}',
`plan`='{$data['plan']}',
`user_id`='{$data['user_id']}',
`status`='0'
";
        //执行sql
        $result=$this->db->execute($sql);
        //返回
        return $result;
    }

    /**
     * 根据id获取一条数据
     * @param $id
     * @return bool|mysqli_result
     */
    public function getOne($id){
        //准备sql
        $sql="select o.* from `order` o where o.order_id ={$id}";
        //执行sql
        $row = $this -> db ->fetchRow($sql);
        return $row;
    }

    /**
     * 更新order的状态和回复
     * @param $data
     * @return bool|mysqli_result
     */
    public function update($data)
    {
        $sql="";
        if(!empty($data['status'])){
            $sql = "update `order`  set status={$data['status']} where order_id = {$data['id']}";
        }elseif (!empty($data['reply'])){
            $sql = "update `order`  set reply='{$data['reply']}' where order_id = {$data['id']}";
        }
//        dump($sql);die;
        $result = $this->db->execute($sql);
        return $result;
    }

    /**
     * 根据id删除数据
     * @param $id
     * @return bool|mysqli_result
     */
    public function delete($id)
    {
        //准备sql
        $sql = "update `order` set `status`=3 where order_id={$id}";
        //执行sql
        $result = $this->db->execute($sql);
        //返回结果
        return $result;
    }
}