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
这里需要补充信息

####reset_encode_file
这里需要补充信息

####usage
这里需要补充信息