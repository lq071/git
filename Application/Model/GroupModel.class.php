<?php


class GroupModel extends Model
{
    /**
     * 获取所有数据
     * @return mixed
     */
    public function getAll(){
        //准备sql
        $sql="select * from `group`";
        //执行sql
        $groups=$this->db->fetchAll($sql);
       // 返回
        return $groups;
    }
    //SELECT g.`name`,m.username,m.group_id FROM member m,`group` g WHERE m.group_id =2 AND g.group_id =2

    /**
     * 获取一个部门的所有员工
     * @param $group_id
     */
    public function getList($group_id){
        $sql = "SELECT g.`name`,m.username,m.sex FROM member m,`group` g WHERE m.group_id ='{$group_id}' AND g.group_id ='{$group_id}'";
        //执行 多条 解析 返回
        return $this->db->fetchAll($sql);
    }
    /**
     * 添加数据
     * @param $data
     * @return bool|mysqli_result
     */
    public function add($data){
        //准备sql
        $sql="insert into `group` set name='{$data['name']}'";
        //执行sql
        $result=$this->db->execute($sql);
        //返回
        return $result;
    }

    /**
     * 根据id查询数据
     * @param $id
     * @return array|null
     */
    public function getOne($id){
        //准备sql
        $sql="select * from `group` where group_id={$id}";
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
    public function update($data,$id){
        //准备sql
        $sql="update `group` set name='{$data['name']}' WHERE group_id={$id}";
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
        $sql="select m.* from member m where m.group_id={$id}";
        //执行sql
        $members=$this->db->fetchAll($sql);
        if(empty($members)){
            //准备sql
            $sql="delete from `group` where group_id={$id}";
            //执行sql
            $result=$this->db->execute($sql);
            //返回结果
            return $result;
        }else{
            $this->error="该部门下有员工,不能直接删除该部门";
            return false;
        }

    }
}