<?php

/**
 * VIPmodel
 */
class VipModel extends Model
{
    /**
     * @return array|null
     */
    public function getAll(){
        //sql
        $sql = $this->db->mySelect('vip');
        //执行
        return $this->db->fetchAll($sql);
    }
    /**
     * @return array|null
     */
    public function geOne($id){
        //sql
        $sql = $this->db->mySelect('vip'," where `vip_id`={$id}",'',[0,1]);
        //执行
        return $this->db->fetchRow($sql);
    }

}