<?php
date_default_timezone_set("Asia/Shanghai");
include 'config.php';

//连接数据库
$con = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['pwd'], $dbconfig['dbname']);
if (!$con){
  die('无法连接数据库: ' . mysqli_connect_error());
}

// 从cookie获取登录信息
if($_COOKIE['qq'] && $_COOKIE['company'] && $_COOKIE['md5str']) {
    $qq = $_COOKIE["qq"];
    $md5str = $_COOKIE["md5str"];
    $re = mysqli_query($con, "SELECT Id FROM stuff WHERE qq='".$qq."' AND md5str='".$md5str."'");
    if(!mysqli_num_rows($re)) {
        unset($_COOKIE["qq"]);
        unset($_COOKIE["name"]);
        unset($_COOKIE["company"]);
        unset($_COOKIE["md5str"]);
        mysqli_query($con, "UPDATE stuff SET md5str='' WHERE qq='".$qq."'");

        $data =  new stdClass();
        $data->flag = -1;
        $reJSON = json_encode($data);
        exit($reJSON);
    }
} else {
        $data =  new stdClass();
        $data->flag = -1;
        $reJSON = json_encode($data);
        exit($reJSON);
}


$date = date("Y-m-d H:i:s");  //日期
$data = new stdClass();

if(isset($_POST['action'])) {
	$action = $_POST['action'];  // 操作
	$re = mysqli_query($con, "SELECT name, company FROM stuff WHERE qq={$qq} LIMIT 1");
	if($row = mysqli_fetch_assoc($re)) {
		$name = $row['name'];   // 姓名
		$company = $row['company'];  // 所属公司
	}

	if(strpos($action, '签回')) {
        $re = mysqli_query($con, "SELECT * FROM log WHERE qq='$qq' AND ISNULL(back_time) AND company='$company' ORDER BY add_time asc");
        if(mysqli_num_rows($re)) {

            while ($row = mysqli_fetch_assoc($re)) {
                mysqli_query($con, "UPDATE log SET back_time='$date' WHERE Id={$row['Id']} AND company='$company'");
                $min = round((strtotime($date) - strtotime($row['add_time']))/60);  //时间相差分钟数
                
                if(strpos($row['item'], '+') !== FALSE) {
                    $item = explode("+", $row['item']);


                    $re2 = mysqli_query($con, "SELECT * FROM item WHERE company='$company'");  //所有事件

                    $all_min = 0;  //设定的超时时间
                    while ( $arr = mysqli_fetch_assoc($re2)) {
                        foreach ($item as $i) {
                            if($arr['name'] == $i) {
                                $all_min += $arr['time'];
                            }
                        }
                    }

                    //如果预计时间超过网站设置的单次最大时间，则以设置的最大时间为准
                    $re3 = mysqli_query($con, "SELECT max_time FROM setting  WHERE company='$company'");
                    $arr = mysqli_fetch_assoc($re3);
                    $all_min = $all_min > $arr['max_time'] ? $arr['max_time'] : $all_min;
                } else {
                    $item = $row['item'];
                    $re2 = mysqli_query($con, "SELECT * FROM item WHERE name='$item' AND company='$company'");  //查找对应事件
                    $arr = mysqli_fetch_assoc($re2);
                    $all_min = $arr['time'];
                }
                

                $re_n = mysqli_query($con, "SELECT name FROM stuff WHERE qq='$qq' LIMIT 1");  //查询员工名称
                if($arr = mysqli_fetch_assoc($re_n)) {
                    $name = $arr['name'];
                }else {
                    $name = "无名氏";
                }

                //更新日用时记录
                $re_t = mysqli_query($con, "SELECT * FROM time WHERE qq='$qq' AND `date`=current_date AND company='$company' LIMIT 1"); //查询当日用时记录是否已存在，如果不存在插入一个
                $re_t1 = mysqli_query($con, "SELECT time FROM setting WHERE company='$company' LIMIT 1");//查询规定每日用时



                //如果查询到规定用时，则按规定用时,否则默认120分钟
                $set_time = ($r2 = mysqli_fetch_assoc($re_t1)) ? $r2['time'] : 120;  
                if($r = mysqli_fetch_assoc($re_t)) {
                    //当日超时时间
                    $over_time = ($r['use_time'] + $min) > $set_time ? ($r['use_time'] + $min) - $set_time : 0; 
                    
                    mysqli_query($con, "UPDATE time SET use_time=use_time+'$min', over_time='$over_time', times=times+1 WHERE qq='$qq' AND `date`=current_date AND company='$company'");

                } else {
                    $over_time = $min > $set_time ? $min - $set_time : 0;
                    mysqli_query($con, "INSERT INTO time (name, qq, use_time, `date`, over_time, company, times) VALUES('$name', '$qq', '$min', current_date, '$over_time', '$company', 1)");
                }



                $over_time = $min - $all_min;  //超时时间
                $id = $row['Id'];
                mysqli_query($con, "UPDATE log SET use_time='$min' WHERE Id='$id'");  //更新用时记录
                if($over_time > 0) {  //超时回复信息
                    $over_msg = ",超时：".$over_time."分钟";
                }else {
                    $over_msg = "";
                    $over_time = 0;
                }

                mysqli_query($con, "UPDATE log SET over_time='$over_time' WHERE Id='$id'");  //更新超时记录
           
                // 返回信息
                $data->flag = 1;
                $data->msg = "签回成功,用时：".$min."分钟".$over_msg;
                $reJSON = json_encode($data);
                exit($reJSON);
            }    
        }else {
                $data->flag = 0;
                $data->msg = "签回失败,没有未签回记录";
                $reJSON = json_encode($data);
                exit($reJSON);
        }  		
	} else {  // 签离
		$re = mysqli_query($con, "SELECT Id,item,add_time FROM log WHERE qq='".$qq."' AND isnull(back_time) ORDER BY add_time ASC LIMIT 1");

		// 有未签回记录, 添加到未签回记录
		if($row = mysqli_fetch_assoc($re)) {
            $add_time = $row['add_time'];
            if((time()-strtotime($add_time)) > 30) {
                $data->flag = 0;
                $data->msg = "您有未签回记录，请先签回"; 
                $reJSON = json_encode($data);  
                exit($reJSON);        
            }
			$item = $row['item']."+".$action;
            $id = $row['Id'];
            $re = mysqli_query($con, "UPDATE log SET item='".$item."' WHERE Id='".$id."'");	
            if($re) {
                $data->flag = 1;
                $data->msg = "记录".$action."成功";              
            } else {
                $data->flag = 0;
                $data->msg = "签到失败，请联系管理员";
            }
            $reJSON = json_encode($data); 
            exit($reJSON);
		} else { // 无未签回记录
			$re = mysqli_query($con, "INSERT INTO log (qq, name, item, add_time, company) VALUES ('$qq', '$name', '$action', '$date', '$company')");
            if($re) {
                $data->flag = 1;
                $data->msg = "记录".$action."成功";              
            } else {
                $data->flag = 0;
                $data->msg = "签到失败，请联系管理员";
            }
            $reJSON = json_encode($data); 
            exit($reJSON);			
		}
	}
} else if(isset($_POST['new_pass'])) {
    $new_pass = $_POST['new_pass'];
    if(!preg_match('/^\S{3,}$/', $new_pass)) {
        $data->flag = 0;
        $data->msg = "密码最少为三位";
        $reJSON = json_encode($data);
        exit($reJSON);	        
    }
    $re = mysqli_query($con, "UPDATE stuff SET pass='".$new_pass."' WHERE qq='".$qq."'");
    if($re) {
        $data->flag = 1;
        $reJSON = json_encode($data);
        exit($reJSON);        
    } else {
        $data->flag = 0;
        $data->msg = "修改失败";
        $reJSON = json_encode($data);
        exit($reJSON);	        
    }

}