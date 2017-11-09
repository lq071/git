<?php

abstract class Model
{
    //可以让继承提交中的类都使用该属性.
    protected $db;

    //存放错误信息
    protected  $error;

    public function __construct()
    {
        $this->db = DB::getInstance($GLOBALS['config']['db']);
    }


    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError(){
        return $this->error;
    }

    /**
     * 添加数据
     * @param $date
     */
    public function insertDate($date){
            //获取表名
            $model_name = get_class($this);
            $table_name = strtolower(substr($model_name,0,strpos($model_name,"Model")));

            //过滤掉数据库中不存在的字段
            //获取存在哪些字段
            $sql_fields = "desc {$table_name}";
            $column = $this->db->fetchAll($sql_fields);
            $all_fileds = [];
            foreach ($column as $v){
                if($v['Key'] == "PRI"){
                    $all_fileds['pk'] = $v['Field'];
                }else{
                    $all_fileds[] = $v['Field'];
                }
            }

            //开始过滤 删除对应键和值
            foreach ($date as $k=>$v){
                if(!in_array($k,$all_fileds)){
                    unset($date[$k]);
                }
            }

            $sql = "insert into {$table_name} set ";
            //自动拼写sql语句
            $fields = [];//implode()
            foreach ($date as $k=>$v){
                $fields[] = "`{$k}`='{$v}'";
            }
            $sql .= implode(',',$fields);
//            dump($sql);die;
            return $this->db->execute($sql);
    }

    /**
     * @param $date 数据
     * @return bool|mysqli_result|void
     */
    public function updateDate($date){
        $id = $date['id'];
        //获取表名
        $model_name = get_class($this);
        $table_name = strtolower(substr($model_name,0,strpos($model_name,"Model")));

        //过滤掉数据库中不存在的字段
        //获取存在哪些字段
        $sql_fields = "desc {$table_name}";
        $column = $this->db->fetchAll($sql_fields);
        $all_fileds = [];
        foreach ($column as $v){
            if($v['Key'] == "PRI"){
                $all_fileds['pk'] = $v['Field'];
            }else{
                $all_fileds[] = $v['Field'];
            }
        }

        //开始过滤 删除对应键和值
        foreach ($date as $k=>$v){
            if(!in_array($k,$all_fileds)){
                unset($date[$k]);
            }
        }
        $sql = "update `{$table_name}` set ";
        //自动拼写sql语句
        $fields = [];//implode()
        foreach ($date as $k=>$v){
            $fields[] = "`{$k}`='{$v}'";
        }

        $sql .= implode(',',$fields);
        $sql .=" where `{$all_fileds['pk']}`= '{$id}'";
//        dump($sql);die;
        return $this->db->execute($sql);
    }

    /**
     * @param $id 对应id
     * @return bool|mysqli_result|void
     */
    public function deleteDate($id){
        //获取表名
        $model_name = get_class($this);
        $table_name = strtolower(substr($model_name,0,strpos($model_name,"Model")));

        //过滤掉数据库中不存在的字段
        //获取存在哪些字段
        $sql_fields = "desc {$table_name}";
        $column = $this->db->fetchAll($sql_fields);
        $all_fileds = [];
        foreach ($column as $v){
            if($v['Key'] == "PRI"){
                $all_fileds['pk'] = $v['Field'];
            }else{
                $all_fileds[] = $v['Field'];
            }
        }

        $sql = "delete from {$table_name} ";
        $sql .=" where `{$all_fileds['pk']}`= '{$id}'";
//        return $sql;
        return $this->db->execute($sql);
    }

    /**
     * @return bool|mysqli_result|void
     * select * from `表名` where xxx order by 字段 asc|desc limit $start,$pageSize
     */
    public function selectDate($condition=[],$order=[],$limit=[],$page,$pageSize=100){
        //获取表名
        $model_name = get_class($this);
        $table_name = strtolower(substr($model_name,0,strpos($model_name,"Model")));

        //过滤掉数据库中不存在的字段
        //获取存在哪些字段
        $sql_fields = "desc {$table_name}";
        $column = $this->db->fetchAll($sql_fields);
        $all_fileds = [];
        foreach ($column as $v){
            if($v['Key'] == "PRI"){
                $all_fileds['pk'] = $v['Field'];
            }else{
                $all_fileds[] = $v['Field'];
            }
        }

        $sql = "select * from {$table_name}";

        return $this->db->execute($sql);
    }
}