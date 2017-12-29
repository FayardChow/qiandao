<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set("Asia/Shanghai");
include 'config.php';



$array = $CQ->receive(); //接收插件推送的数据
if(!$array) exit; //没传入数据，终止运行

//连接数据库
$con = mysql_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['pwd']);
if (!$con){
  die('无法连接数据库: ' . mysql_error());
}
mysql_select_db($dbconfig['dbname'], $con);





switch($array['type']) {
    case 1:
        //收到私聊信息
        $qq = $array['qq'];
        $msg = $array['msg'];
        $CQ->sendPrivateMsg($qq, "收到一条消息:$msg");
        break;

    case 2:
        //收到群聊天信息
        $qq = $array['qq'];
        $group = $array['group'];
        $msg = $array['msg'];

        $msg = str_replace(" ", "", $msg);  //去掉空格
        $date = date("Y-m-d H:i:s");

        if(strpos($msg, '回') !== FALSE) {
            $re = mysql_query("UPDATE log SET back_time='$date' WHERE qq='$qq' AND ISNULL(back_time)");
            if($re) {
                $re1 = mysql_query("SELECT * FROM log WHERE qq='$qq' ORDER BY add_time DESC LIMIT 1");
                $row = mysql_fetch_array($re1);

                $min = round((strtotime($row['back_time']) - strtotime($row['add_time']))/60);  //时间相差分钟数
                
                if(strpos($row['item'], '+') !== FALSE) {
                    $item = explode("+", $row['item']);
                    $re2 = mysql_query("SELECT * FROM item");  //所有事件

                    $all_min = 0;  //设定的超时时间
                    while ( $arr = mysql_fetch_array($re2)) {
                        foreach ($item as $i) {
                            if($arr['name'] == $i) {
                                $all_min += $arr['time'];
                            }
                        }
                    }
                } else {
                    $item = $row['item'];
                    $re2 = mysql_query("SELECT * FROM item WHERE name='$item'");  //查找对应事件
                    $arr = mysql_fetch_array($re2);
                    $all_min = $arr['time'];
                }

                $over_time = $min - $all_min;  //超时时间
                if($over_time > 0) {  //超时回复信息
                    $over_msg = ",超时：".$over_time."分钟";
                    $id = $row['Id'];
                    mysql_query("UPDATE log SET over_time='$over_time' WHERE id='$id'");  //更新超时记录
                }else {
                    $over_msg = "";
                }

                    $re_n = mysql_query("SELECT name FROM stuff WHERE qq='$qq' LIMIT 1");  //查询员工名称
                    if($arr = mysql_fetch_array($re_n)) {
                        $name = $arr['name'];
                    }else {
                        $name = "无名氏";
                    }

                    $CQ->sendGroupMsg($group,$CQ->cqAt($qq));
                    $CQ->sendGroupMsg($group, $name." ".$row['item']." 已经记录签回,用时：".$min."分钟".$over_msg);            
            }else {
                $CQ->sendGroupMsg($group,'数据库插入失败');
            }        

        }else {

            $sql = mysql_query("SELECT name FROM item");

            $str = "";  //以"|"分隔的事件名称
           
            while ($row = mysql_fetch_array($sql)) {
                $str .= $row['name']."|";
               
            }

            $str = substr($str, 0, strlen($str)-1);  //去掉最后一个字符
            

            $num = preg_match_all("/$str/", $msg, $matches);  //提取所有与数据库事件匹配的
            if($num == 0) {
                $CQ->sendGroupMsg($group,'请正确输入您的信息: '.$str);
                exit();
            }

            $r = mysql_query("SELECT name FROM stuff WHERE qq='$qq' LIMIT 1"); //查询qq对应姓名

            if($a = mysql_fetch_array($r)) {
                $name = $a['name'];
            } else {
                $name = "无名氏";
            }

            $str1 = "";   //以"+"分隔的事件名称

            foreach ($matches[0] as $m) {
                 $str1 .= $m."+";
            }      
  
            $str1 = substr($str1, 0, strlen($str1)-1);  //去掉最后一个字符


            //插入签到记录
            $re = mysql_query("INSERT INTO log (qq, name, item, add_time) VALUES ('$qq', '$name', '$str1', '$date')");
            
            if($re) {
                $CQ->sendGroupMsg($group, $CQ->cqAt($qq)." $name 已经记录 " . $str1 . " " .date("H:i:s"));            
            }else {
                $CQ->sendGroupMsg($group,'数据库插入失败');
            }            
        }

        break;

    case 4:
        //收到讨论组信息
        $group = $array['group'];
        $msg = $array['msg'];
        $CQ->sendDiscussMsg($group, "FromHttpSocket:$msg");
        break;

    case 11:
        //有群成员上传文件
        $group = $array['group'];
        $file = $array['fileInfo'];
        $msg = $CQ->cqAt($array['qq']).'上传了文件';
        $msg .= "\r\n";
        $msg .= '文件名：'.$file['name'];
        $CQ->sendGroupMsg($group, $msg);
        break;

    case 103:
        //群成员增加
        $group = $array['group'];
        $qq = $array['beingOperateQQ'];
        $groupInfo = $CQ->getGroupInfo($group);
        $groupName = (!$groupInfo['status']) ? $groupInfo['result']['gName'] : '本群';
        $msg = '欢迎'.$CQ->cqAt($qq).'加入'.$groupName;
        $CQ->sendGroupMsg($group, $msg);
        break;
}

mysql_close($con);
unset($CQ);//释放连接
