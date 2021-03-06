<?php
require ROOT_PATH."Public/PHPExcel/Classes/PHPExcel.php";
/**
 * 导出Excel
 */
class ExcelController extends PlatformController
{
    public function export(){
        $Users = new UserModel();
        $rows = $Users->getAll()[0];
//        var_dump($rows);die;
//创建excel对象

        $objPHPExcel = new PHPExcel();

//添加一个表单
        $objPHPExcel->setActiveSheetIndex(0);


//设置表单名称
        $objPHPExcel->getActiveSheet()->setTitle("用户信息表");


        /**
         * 向表单中添加数据
         *
         * 1.表头
         * 2.数据
         */

        /**
         * 准备表头的名称
         */
        $xlsHeader = [
            'ID',
            '用户名',
            '真是名字',
            '性别',
            '电话',
            '备注',
            '余额',
            '会员身份',
            '积分'
        ];

        /**
         * 准备表格列名
         */
        $cellName = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];

        /**
         * 将表格第一行作为表格的简介行，需要合并
         */
//>>1.获取需要合并多少列
        $column_count = count($xlsHeader);
//>>2.合并第一行的三列
        $objPHPExcel->getActiveSheet()->mergeCells("A1:" . $cellName[$column_count - 1] . "1");
//>>3.设置合并后的内容
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "用户信息统计  创建时间：" . date("Y-m-d"));

        /**
         * 表格第二行开始设置表头
         */
        foreach ($xlsHeader as $k => $v) {
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$k] . "2", $v);
        }

        /**
         * 表格第三行开始添加表格数据
         */

        foreach ($rows as $k => $v) {
            //获取当前多少行


            $line = 3 + $k;
            $i=0;
               $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i].$line, $v['user_id']);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i].$line, $v['username']);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i].$line, $v['realname']);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i].$line, $v['sex']);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i].$line, $v['telephone']);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i].$line, $v['remark']);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i].$line, $v['money']);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i].$line, $v['is_vip']);
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i].$line, $v['mark']);

        }

        /*
         foreach ($rows as $k => $v) {
            //获取当前多少行
            $line = 3 + $k;
            $i = 0;
            foreach ($v as $key => $value) {
                $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i] . $line, $value);
                ++$i;
            }
        }*/



//导出excel
        $xlsname = iconv("utf-8", "gb2312", "用户信息表");

// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $xlsname . '.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}