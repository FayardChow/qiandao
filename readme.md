﻿配置方法
---

1.配置config.php, 填写数据库信息
2.导入install.sql


 |  参数 |  默认值  |  说明  |
 | --- | --- | --- |
 |  url  |  ```http://127.0.0.1```  |  接口地址，不支持```HTTPS```，如果设置，视为使用动态交互功能|
 |  port  |  ```9999```  |  动态交互的监听端口  |
 |  key  |  ```123```  |  校验数据所需的key值，如果设置，视为使用校验数据功能  |
 |  effectTime  |  ```30```  |  校验数据所设置的有效时间，单位：秒  |
