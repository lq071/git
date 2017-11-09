<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/29
 * Time: 14:46
 */
class Controller
{
    private $datas = []; //存放数据容器. 该容器中的数据需要在页面中使用到.

    /**
     * 加载当前控制器对应的视图文件夹下的模板
     * @param $template 模板的名字
     */
    public function display($template)
    {
        extract($this->datas); //将datas中的数据解析成变量.  变量名就是键的名字
        require CURRENT_VIEW_PATH . $template . '.html';
    }

    /**
     * 将数据放到$data中
     * @param $name
     * @param $value
     */
    public function assign($name, $value = '')
    {
        if (is_array($name)) {
            //如果name是数组,将$name的数据直接合并到$datas中
            $this->datas = array_merge($this->datas, $name);  //$name = array('key1'=>value1,'key2'=>value2);
        } else {
            $this->datas[$name] = $value;
        }
    }


    /**
     * 跳转
     * @param $url  跳转的url
     * @param $msg   提示的信息
     * @param $time  等待时间,秒
     */
    protected static function redirect($url, $msg = '', $time = 0)
    {
        if (!headers_sent()) {  //headers_sent检测header是否发送给浏览器
            //header没有发送,使用header跳转
            if ($time == 0) { //立即跳转
                header("Location: $url");
            } else {  //延迟跳转
                echo '<h1>' . $msg . '</h1>';  //跳转之前输出提示信息
                header("Refresh: $time;url=$url");
            }
        } else {
            if ($time != 0) {   //延时跳转
                echo '<h1>' . $msg . '</h1>';  //提示信息
                $time = $time * 1000;
            }
            //使用js跳转
            echo <<<JS
            <script type='text/javascript'>
                window.setTimeout(function(){
                  location.href = '{$url}';
                },{$time});
            </script>
JS;
        }
        exit;  //跳转之后没有必要再执行其他的代码.
    }

    /**
     * @param string $message跳转信息成功或者失败
     * @param string $jumpUrl跳转地址
     * @param int $time跳转时间
     */
    function jump($message = '操作成功', $jumpUrl = '', $time = 2)
    {
        $str = '<!DOCTYPE HTML>';
        $str .= '<html>';
        $str .= '<head>';
        $str .= '<meta charset="utf-8">';
        $str .= '<title>页面提示</title>';
        $str .= '<style type="text/css">';
        $str .= '*{margin:0; padding:0}a{color:#369; ;text-decoration:none;}a:hover{text-decoration:underline}body{height:100%; font:12px/18px Tahoma, Arial,  sans-serif; color:#424242; background:#FFFFF5}.message{width:450px; height:120px; margin:16% auto; border:1px solid #99b1c4; background:#ecf7fb}.message h3{height:28px; line-height:28px; background:#2c91c6; text-align:center; color:#fff; font-size:14px}.msg_txt{padding:10px; margin-top:8px}.msg_txt h4{line-height:26px; font-size:14px}.msg_txt h4.red{color:#f30}.msg_txt p{line-height:22px}';
        $str .= '</style>';
        $str .= '</head>';
        $str .= '<body>';
        $str .= '<div class="message">';
        $str .= '<h3>操作提示</h3>';
        $str .= '<div class="msg_txt">';
        $str .= '<h4 class="red">' . $message . '</h4>';
        $str .= "<p>系统将在 <span style='color:blue;font-weight:bold'>$time</span> 秒后自动跳转,如果不想等待,直接点击 <a href='$jumpUrl'>这里</a> 跳转</p>";
        $str .= "<script>setTimeout(function() {
  location.replace('$jumpUrl');
},$time+'000')</script>";
        $str .= '</div>';
        $str .= '</div>';
        $str .= '</body>';
        $str .= '</html>';
        echo $str;
        die;
    }

}