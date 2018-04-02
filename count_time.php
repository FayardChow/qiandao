<?php

// 计算离开时间
// $all_min : 设定的用时
// $now_use_time : 目前用时
function count_time($con, $qq, $company) {
    $re = mysqli_query($con, "SELECT item,add_time FROM log WHERE qq='$qq' AND ISNULL(back_time) AND company='$company' ORDER BY add_time asc LIMIT 1");
    if($row = mysqli_fetch_assoc($re)) {
        $item = $row['item'];
        $add_time = $row['add_time'];
        $now_time = time();
        // exit(strtotime("now").'');
        // exit($now_time-$add_time.'');
        $now_use_time = round(($now_time-strtotime($add_time))/60);  // 目前使用时间


        // 计算签回剩余时间
        if(strpos($item, '+') !== FALSE) {
            $item = explode("+", $item);

            $re = mysqli_query($con, "SELECT name,time FROM item WHERE company='$company'");  //所有事件

            $all_min = 0;  //设定的超时时间
            while ( $row = mysqli_fetch_assoc($re)) {
                foreach ($item as $i) {
                    if($row['name'] == $i) {
                        $all_min += $row['time'];
                    }
                }
            }

            //如果预计时间超过网站设置的单次最大时间，则以设置的最大时间为准
            $all_min = $all_min > $max_time ? $max_time : $all_min;
        } else {
            $re = mysqli_query($con, "SELECT time FROM item WHERE name='$item' AND company='$company'");  //查找对应事件
            $row = mysqli_fetch_assoc($re);
            $all_min = $row['time'];
        }
    }
}


?>