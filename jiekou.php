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
        $CQ->sendPrivateMsg($qq, "请在群里回复，如需超时抖动窗口提醒，请添加我好友~");
        break;

    case 2:
        //收到群聊天信息
        $qq = $array['qq'];
        $group = $array['group'];
        $msg = $array['msg'];

        $msg = str_replace(" ", "", $msg);  //去掉空格
        $date = date("Y-m-d H:i:s");


        //查找群对应的公司名称
        $re_group = mysql_query("SELECT company FROM setting WHERE `group`='$group'");
        if($r_group = mysql_fetch_array($re_group)) {
            $company = $r_group['company']; //公司名称
            if($company == "") {
                $CQ->sendGroupMsg($group, '群号公司名称未对应，请联系管理员');
                unset($CQ);//释放连接
                exit();                
            }
        }else {
            $CQ->sendGroupMsg($group, '群号公司名称未对应，请联系管理员');
            unset($CQ);//释放连接
            exit();
        }

        if(strpos($msg, '回') !== FALSE) {

            $re = mysql_query("SELECT * FROM log WHERE qq='$qq' AND ISNULL(back_time) AND company='$company' ORDER BY add_time asc");
            if(mysql_num_rows($re)) {

                while ($row = mysql_fetch_array($re)) {
                    mysql_query("UPDATE log SET back_time='$date' WHERE Id={$row['Id']} AND company='$company'");
                    $min = round((strtotime($date) - strtotime($row['add_time']))/60);  //时间相差分钟数
                    
                    if(strpos($row['item'], '+') !== FALSE) {
                        $item = explode("+", $row['item']);


                        $re2 = mysql_query("SELECT * FROM item WHERE company='$company'");  //所有事件

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
                        $re2 = mysql_query("SELECT * FROM item WHERE name='$item' AND company='$company'");  //查找对应事件
                        $arr = mysql_fetch_array($re2);
                        $all_min = $arr['time'];
                    }
                    

                    $re_n = mysql_query("SELECT name FROM stuff WHERE qq='$qq' LIMIT 1");  //查询员工名称
                    if($arr = mysql_fetch_array($re_n)) {
                        $name = $arr['name'];
                    }else {
                        $name = "无名氏";
                    }

                    //更新日用时记录
                    $re_t = mysql_query("SELECT * FROM time WHERE qq='$qq' AND `date`=current_date AND company='$company' LIMIT 1"); //查询当日用时记录是否已存在，如果不存在插入一个
                    $re_t1 = mysql_query("SELECT time FROM setting WHERE company='$company' LIMIT 1");//查询规定每日用时



                    //如果查询到规定用时，则按规定用时,否则默认120分钟
                    $set_time = ($r2 = mysql_fetch_array($re_t1)) ? $r2['time'] : 120;  
                    if($r = mysql_fetch_array($re_t)) {
                        //当日超时时间
                        $over_time = ($r['use_time'] + $min) > $set_time ? ($r['use_time'] + $min) - $set_time : 0; 
                        
                        mysql_query("UPDATE time SET use_time=use_time+'$min', over_time='$over_time' WHERE qq='$qq' AND `date`=current_date AND company='$company'");

                    } else {
                        $over_time = $min > $set_time ? $min - $set_time : 0;
                        mysql_query("INSERT INTO time (name, qq, use_time, `date`, over_time, company) VALUES('$name', '$qq', '$min', current_date, '$over_time', '$company')");
                    }



                    $over_time = $min - $all_min;  //超时时间
                    $id = $row['Id'];
                    mysql_query("UPDATE log SET use_time='$min' WHERE Id='$id'");  //更新用时记录
                    if($over_time > 0) {  //超时回复信息
                        $over_msg = ",超时：".$over_time."分钟";
                    }else {
                        $over_msg = "";
                        $over_time = 0;
                    }

                    mysql_query("UPDATE log SET over_time='$over_time' WHERE Id='$id'");  //更新超时记录

                    $CQ->sendGroupMsg($group, $CQ->cqAt($qq)." ".$name." ".$row['item']." 已经记录签回,用时：".$min."分钟".$over_msg);            
                }    
            }else {
                $CQ->sendGroupMsg($group,'没有未签回记录');
            }        

        }else {

            $sql = mysql_query("SELECT name FROM item WHERE company='$company'");

            $str = "";  //以"|"分隔的事件名称
           
            while ($row = mysql_fetch_array($sql)) {
                $str .= $row['name']."|";
               
            }

            $str = substr($str, 0, strlen($str)-1);  //去掉最后一个字符
            

            $num = preg_match_all("/$str/", $msg, $matches);  //提取所有与数据库事件匹配的
            if($num == 0) {
                //$CQ->sendGroupMsg($group,'请正确输入您的信息: '.$str);
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
            $re = mysql_query("INSERT INTO log (qq, name, item, add_time, company) VALUES ('$qq', '$name', '$str1', '$date', '$company')");
            
            if($re) {
                if(strpos($str1, '+') !== FALSE) {
                    $item = explode("+", $str1);
                    $re2 = mysql_query("SELECT * FROM item WHERE company='$company'");  //所有事件

                    $all_min = 0;  //设定的超时时间
                    while ( $arr = mysql_fetch_array($re2)) {
                        foreach ($item as $i) {
                            if($arr['name'] == $i) {
                                $all_min += $arr['time'];
                            }
                        }
                    }
                } else {
                    $item = $str1;
                    $re2 = mysql_query("SELECT * FROM item WHERE name='$item' AND company='$company'");  //查找对应事件
                    $arr = mysql_fetch_array($re2);
                    $all_min = $arr['time'];
                }


                $CQ->sendGroupMsg($group, $CQ->cqAt($qq).$name."已经记录" . $str1 . ", 请在".$all_min."分钟内回来签'回' " .date("H:i:s"));            
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
