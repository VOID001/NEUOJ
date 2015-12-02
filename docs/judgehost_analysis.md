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
* arguments:
 $name: 用于表示想要get的配置的名称
 
* description:
 获取服务器的配置信息
 
* retval
 服务器的相应的配置信息

####fetch_executable
__核心函数,用于下载用户提交的代码,并且将代码编译, 生成运行测评的脚本run.sh 并且返回run.sh的绝对路径共后续调用使用__

* arguments: 
 $workdirpath: 测评路径
 $execid: 需要fetch的执行脚本archive的id
 $md5sum: zip的校验和
* description:
 该函数根据提供的三个参数, 先检查是否本地已经存在运行本次测评需要的所有脚本(或者二进制文件), 如果不存在则从server 访问 REST/executable ,获取下来相应的execid对应的base64加密后的zip文件,解码,然后在本地的workdir/executable/$execid/下进行解压 ,并且根据不同语言写入不同的脚本内容
* retval:
 返回值为相应的执行脚本(run.sh)所在的绝对路径

####judge(主测评函数)
* arguments: 
 $row: 一个数组,里面含有测评所需要的所有信息, $row是在服务器端获得数据之后decode,然后存入的数据
* description:
 该函数实现比较复杂, 将该函数的整个执行以流程的形式说明
 1. 检查是否有创建文件等一系列的权限
 2. 获取比赛队员提交的文件 需要访问RESTapi 的 submission_files ,id存在$row['submitid']内
 3. 获取编译该语言代码所需的 compile script ,调用fetch_executable, 取出所需的compile_script,并且返回一个绝对路径 $execrunpath
 4. 运行LIBJUDGE_DIR下的compile.sh脚本, 对代码进行编译
 5. 检查compile的成功与否, 成功则继续,否则退出,并将compile result返回给server
 6. 如果有CHROOT的设置,则建立chroot环境
 7. 循环获取每个testcase, 本地无testcase则去远端获取(input & output) ,将他们放在 $workdirpath/testcase/下
 8. 获取hardtimelimit(运行时限), __**未解决**__为什么要用overshoot_time这个函数来求运行时限(line 612左右 定义在 lib/lib.misc.php下)
 9. 执行testcase run脚本, 测评开始
 10. 判断测评结果(返回值的含义)
 11. 尝试从program.metadata中读取测评耗时(等信息)
 12. 将测评结果返回给server RESTapi POST 请求 judging_runs 返回的数据如下:(数据的解释可能有偏差)
 
 ```
 	testcaseid: 测试数据id
    runresult: 执行结果
    runtime: 执行时间
    judgehost: 测评的host名
    output_run: 测评标准输出
    output_error: 测评中的地方发
    output_system: 测评信息
    output_diff: 测评diff
 ```
 13. 如果有chroot环境则destroy掉
 14. 一次judge结束

* retval:NULL


####read_credentials
* arguments: NULL
* description: 
  获取配置文件(ETCDIR/restapi.secret)中的认证信息并且添加到全局认证变量 $endpoints 中.配置文件中通过空白字符对每个信息域进行分割, 信息含有 认证记录的index(字符串), RESTAPI的入口地址 和访问RESTAPI需要的用户名和密码.
* retval
  实际上是将全局变量 $endpoints的信息更新了, 不过没有返回值

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
* arguments:
 $file: 文件所在路径
 $sizelimit: 是否限制文件的大小 (传递给 dj_get_file_contents)
* description:
 将文件用base64和urlencode进行编码,并返回其值, 如果限定了$sizelimit = TRUE 的话, 文件会被限制在50000B 
* retval:
 返回文件encode之后的字符串

####usage
* arguments: NULL
* description:
 显示说明信息并退出
* retval: NULL
