<?php

/**
 * 充值模型
 */
class RechargeModel extends Model
{
    /**
     * 获取全部数据
     */
    public function getAll(){
        //写SQL
        $sql = $this->db->mySelect('recharge','',['recharge_id','desc']);
        //执行 解析 返回
        $rows = $this->db->fetchAll($sql);
        return [$rows];
    }
    public function getOne($id){
        //写SQL
        $sql = "SELECT * FROM `recharge` WHERE `recharge_id`='{$id}'";
        //执行 解析 返回
        return $this->db->fetchRow($sql);
    }


    //根据用户输入的金额,返回对应赠送的金额
    //SELECT donation FROM recharge WHERE money < 590 ORDER BY donation DESC LIMIT 1
    /**
     * @param $money
     * @return array|null
     */
    public function donation($money){
        //写SQL
        $sql= "SELECT `donation` FROM `recharge` WHERE `money` <= {$money} ORDER BY `donation` DESC LIMIT 0,1";
        //执行 解析 返回
        return $this->db->fetchColumn($sql);
    }
}