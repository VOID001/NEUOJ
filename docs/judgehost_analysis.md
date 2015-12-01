DOMJudge 测评后端API分析
====

###调用流程简介:
1. domserver存储有所有数据信息,包括测评必须的信息
2. domserver提供一套RESTful API供judgedaemon客户端调用, judgedaemon可以有多个实例同时运行
3. judgedaemon运行之后, 每隔一定时间向domserver的api请求数据, 如果有数据的话, server会返回给judgedaemon一个json包,包含要运行的judge所需要的所有信息
4. judgedaemon将信息解析之后, 制作出相应的程序执行,和编译脚本 compile.sh , run.sh 之后执行(可以选择在CHROOT环境下执行, 暂时没有考虑)
5. 执行后的结果通过参数和返回值的形式返回给judgedaemon , 之后judgedaemon将测评结果数据提交给domserver, domserver随即更新数据库内的信息

###judgedaemon.main.php内函数分析

* dbconfig_get_rest
* fetch_executable
* judge
* read_credentials
* request
* rest_encode_file
* usage

####dbconfig_get_rest
这里需要补充信息

####fetch_executable
这里需要补充信息

####judge
这里需要补充信息

####read_credentials
这里需要补充信息

####request
* arguments:
  $url:请求的restapi的路径
  $verb: 请求方式(GET POST PUT...)
  $data: 通过curl GET or POST时候附带给server的数据
  $failonerror: 表示这个函数失败的时候是fail还是报警告
* description: 
  所有的curl请求通过这个函数进行, 这个函数就是封装好的curl请求函数, 当 $verb 参数为GET时, 表示通过GET请求数据, 即请求的是 restapi 中的 xxxx_GET的API 执行过程如下, 先通过curl_init 初始化curl进程, 然后经过setopt设置好各种参数, 然后通过curl_exec向服务器发出请求, 将服务器回传的信息通过$response变量存储起来,并且通过curl_getinfo得到服务器的响应代码,然后进行error handling(如果response为NULL说明函数执行失败)
  
* retval
  返回值为从服务器获得的回传数据, json格式
   返回数据样例:
   ```
   [{"filename":"a.c","content":"LyoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioKICAgID4gRmlsZSBOYW1lOiAvdG1wL2EuYwogICAgPiBBdXRob3I6IFZPSURfMTMzCiAgICA+ICMjIyMjIyMjIyMjIyMjIyMjIyMgCiAgICA+IE1haWw6ICMjIyMjIyMjIyMjIyMjIyMjIyMgCiAgICA+IENyZWF0ZWQgVGltZTogVHVlIDAxIERlYyAyMDE1IDEyOjQ2OjM5IFBNIENTVAogKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqLwojaW5jbHVkZTxzdGRpby5oPgoKaW50IG1haW4odm9pZCkKewogICAgcHJpbnRmKCJIZWxsbyB3b3JsZCFcbiIpOwogICAgcmV0dXJuIDA7Cn0K"}]
   ```

####reset_encode_file
这里需要补充信息

####usage
这里需要补充信息
