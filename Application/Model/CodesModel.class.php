<?php

/**
 * 代金券模型
 */
class CodesModel extends Model
{
    /**
     * 查询所有数据
     * @return array
     */
    public  function getAll(){
        //准备sql
        $sql="select c.*,u.username  from `codes` c ,`user` u where c.user_id=u.user_id";
        //执行sql
        $rows=$this->db->fetchAll($sql);
        //返回结果
        return $rows;
    }
    /**
     * 根据user_id 查询数据
     * @param $user_id
     * @return array
     */
    public function getAllById($user_id){
        //准备sql
        $sql="select c.* from `codes` c where c.user_id=$user_id";
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
        //执行sql
        $result=$this->insertDate($data);
        //返回结果
        return $result;
    }

    /**
     * 根据id 获取一条数据
     * @param $id
     * @return array|null
     */
    public function getOne($id)
    {
        //准备sql
        $sql="select c.*,u.username  from `codes` c ,user u where c.user_id=u.user_id and code_id={$id}";
        //执行sql
        $row=$this->db->fetchRow($sql);
        //返回结果
        return $row;
    }

    /**
     * @param $date
     * @return array|null
     */
    public function inquiry($date){
        //准备sql
        $sql = "select * from `codes` where `code`='{$date}'";
        //执行
        return $this->db->fetchRow($sql);
    }

    /**
     * 更新数据
     * @param $data
     * @return bool|mysqli_result|void
     */
/*>>>>>>> .theirs   */ public function update($data){
        //准备sql
        //执行sql
        $result=$this->updateDate($data);
        //返回结果
        return $result;
    }

/*<<<<<<< .mine  */  public function update1($status,$money,$code_id){
   /**
     * 修改状态
     * @param $data
     * @return bool|mysqli_result
     */
   /* public function updateStatus($data){*/
     //准备sql
    $sql="update `codes` set `status`='{$status}',`money`='{$money}' WHERE `code_id`='{$code_id}'";
     /*$sql="update `codes` set status={$data} where code_id={$data['id']}";
>>>>>>> .theirs        //执行sql*/
        $result=$this->db->execute($sql);
        //返回结果
        return $result;
    }

    /**
     * 根据id删除数据
     * @param $id
     * @return bool|mysqli_result|void
     */
    public function delete($id){
        //准备sql
        //执行sql
        $result=$this->deleteDate($id);
        //返回结果
        return $result;
    }
    public function updateMoney($data){
        $status = 2;
        if ($data['money'] == 0){
            $status= 0;
        }
        //写SQL
        $sql = "update `codes` set `money`={$data['money'] },`status`='{$status}'";
        //判断代金券余额是否为0 为 0 将代金券更新为 下线

        $sql.="WHERE `code_id`='{$data['code_id']}'";
        //执行
        $this->db->execute($sql);
    }
}