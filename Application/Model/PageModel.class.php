<?php

/**
 * 分页工具条
 */
class PageModel
{
    public static function showPage($page,$pageSize,$total,$url){
        $prePage =($page - 1) < 1 ? 1 :$page - 1;
        $totalPage = ceil($total/$pageSize);
        $nextPage =($page + 1) > $totalPage ? $totalPage :$page + 1;
        $html = <<<HTML
         <ul class="am-pagination am-fr">
           
                <li>共{$total}条记录</li>
                <li>总页数为{$totalPage}</li>
                <li>当前为{$page}页</li>
                　　
                <li><a href="index.php?$url&page=1">«</a></li>
                <li><a href="index.php?$url&page=$prePage">上一页</a></li>
                <li><a href="index.php?$url&page=$nextPage">下一页</a></li>
                <li><a href="index.php?$url&page=$totalPage">»</a></li>
          </ul>
HTML;
        return $html;
    }
}