<?php

/**
 * 商品上传模板
 */
class UploadModel extends Model
{
    /**
     * @param $file $_FILES
     * @param $dir  保存目录
     */
    public function upload($file, $dir){
//        dump($file);
//        dump($dir);
        $upload_type = ["image/jpeg","image/png","image/gif","image/bpm"];
        $upload_size = 2*1024*1024;
        //判断文件上传格式   不符合返回false
        if (!in_array($file['type'],$upload_type)){
                $this->error = "您上传的图片格式不正确,请确认上传".implode(',',$upload_type);
                return false;
        }
        //判断文件上传大小  不符合返回false
        if ($file['size'] > $upload_size){
            $this->error = "上传文件过大,请上传文件大小在2M以下的文件";
            return false;
        }
        //判断上传是否合法
        if (!is_uploaded_file($file['tmp_name'])){
            $this->error = "上传文件失败,未能找到图片位置,请刷新...";
            return false;
        }
        //只有在$file['error'] 等于 0 的情况下代表文件上传成功 ,做判断
        if ($file['error'] == 0){
//文件名
            $filename = uniqid().strrchr($file['name'],'.');
//        dump($filename);
            //新文件的目录
            $dirName = $dir.'/'.date('Ymt').'/';
//        dump($dirName);
//        dump($dirName.$filename);
            if (!is_dir($dirName)){
                mkdir($dirName,0777,true);
            }
            if (move_uploaded_file($file['tmp_name'],$dirName.$filename)){
                return $dirName.$filename;
            }
        }else{
            $this->error = "上传文件失败...";
            return false;
        }
    }
}