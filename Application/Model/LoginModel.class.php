<?php

class LoginModel extends Model
{
    /**
     * 验证用户信息
     * @param $username
     * @param $password
     * @return array|bool|null
     */
    public function checkUser($username,$password){
        //准备sql
        //$password=md5($password);
        $sql="select * from `member` where username='{$username}' and password='{$password}'";
        //执行sql
        $userinfo=$this->db->fetchRow($sql);
        //返回结果  成功返回用户信息,失败返回false
        if(empty($userinfo)){
            return false;
        }else{
            return $userinfo;
        }
    }

    /**
     * 验证id 和password
     * @param $id
     * @param $password
     * @return array|bool|null
     */
    public function checkIdPassword($id,$password){
        //准备sql
        $sql="select * from `member` where `member_id`={$id}";
        //执行sql
        $row=$this->db->fetchRow($sql);
        //加密加盐数据库的密码 再进行验证
        $db_password=md5($row['password'].'linqin');
        //验证密码
        if($password != $db_password){
            return false;
        }else{
            return $row;
        }
    }

    /*--------------------------会员验证--------------------------------*/
    /**
     * 验证用户信息
     * @param $username
     * @param $password
     * @return array|bool|null
     */
    public function checkVip($username,$password){
        //准备sql
        //$password=md5($password);
        $sql="select * from `user` where username='{$username}' and password='{$password}'";
        //执行sql
        $userinfo=$this->db->fetchRow($sql);
        //返回结果  成功返回用户信息,失败返回false
        if(empty($userinfo)){
            return false;
        }else{
            return $userinfo;
        }
    }

    /**
     * 验证id 和password
     * @param $id
     * @param $password
     * @return array|bool|null
     */
    public function checkIdPwd($id,$password){
        //准备sql
        $sql="select * from member where id={$id}";
        //执行sql
        $row=$this->db->fetchRow($sql);
        //加密加盐数据库的密码 再进行验证
        $db_password=md5($row['password'].'linqian');
        //验证密码
        if($password != $db_password){
            return false;
        }else{
            return $row;
        }
    }
}