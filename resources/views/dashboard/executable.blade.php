<!doctype html>
<html>
<head>
    <title>Executables</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function() {
            $("#dashboard_executable").addClass("dashboard-subnav-active");
        })
    </script>
    <style>
        .my_modal {
            margin-top: 5%;
        }
        .my_modal .modal-dialog{
           width: 35%;
        }
        .row {
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .row input {
            height: 30px;
        }
        .modal-footer a{
            margin: 0;
            padding: 5px;
            margin-left: 12%;
            border-radius: 2px;
        }

    </style>
</head>
<body>
@include("layout.dashboard_nav")
<div class="back-container">
    <h3 class="custom-heading">Executables</h3>
    <div class="back-list">
        <a class="btn btn-grey" id="btn_new" href="#modal" data-toggle="modal">Create</a>
        <div class="modal fade my_modal" id="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title text-center">Add Executable</h4>
                    </div>
                    <div class="modal-body">
                        <form enctype="multipart/form-data" id="tmp_form">
                            {{ csrf_field() }}
                            <div class="row">
                                <label class="col-md-3">ID</label>
                                <input class="col-md-8" name="execid" type="text">
                            </div>
                            <div class="row">
                                <label class="col-md-3">Type</label>
                                <input class="col-md-8" name="type" type="text">
                            </div>
                            <div class="row">
                                <label class="col-md-3">Input File</label>
                                <input class="col-md-8" name="file" type="file">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" type="button"  data-dismiss="modal">Cancel</a>
                        <a class="btn btn-primary" type="button" id="tmp_form_submit">Submit</a>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-hover custom-list">
            <thead>
                <th class="text-center" width="3%">ID</th>
                <th class="text-center" width="5%">Type</th>
                <th class="text-center" width="20%">Input File</th>
                <th class="text-center" width="20%">MD5 Sum</th>
                <th class="text-center" width="12%">Operation</th>
            </thead>
            <tbody id="executable_list"></tbody>
        </table>
        <div id="modal_list"></div>
    </div>
</div>
<script>
    //post form
    $(function(){
        $("#tmp_form_submit").click(function(){
            var form = new FormData($("#tmp_form")[0]);
            $.ajax({
                url: '/ajax/contests',
                type: 'put',
                processData: false,
                contentType: false,
                data: form,
                success: function(){
                    window.location.reload();
                }
            })
        });
    });
    //get table
    $(function(){
        $.ajax({
            url: '/ajax/contests',
            type: 'get',
            async: true,
            dataType: 'json',
            success: function(json){
                //var str = '[{"execid":"c","type":"1","input_file":"test_file","md5sum":"123"},' +
                        //'{"execid":"cpp","type":"2","input_file":"test_file1","md5sum":"345"}]';
                //var json = eval('('+str+')');
                console.log(json);
                var obj =eval(json);

                for(var i= 0; i< obj.length; i++) {
                    var executable = '<tr><td>' + obj[i].execid + '</td>'+
                            '<td>' + obj[i].type + '</td>'+
                            '<td>' + obj[i].input_file + '</td>'+
                            '<td>' + obj[i].md5sum + '</td>'+
                            '<td>'+
                                '<a class="btn btn-grey" id="executable_edit'+obj[i].execid+'" href="#modal_edit'+obj[i].execid+'" data-toggle="modal">&nbsp;&nbsp;Edit&nbsp;&nbsp;</a>'+
                                '&nbsp;' +
                            '<a class="btn btn-grey" id="executable_delete'+obj[i].execid+'">Delete</a>' +
                            '<script>'+
                                '$(function(){'+
                                    '$("#executable_delete'+obj[i].execid+'").click(function(){'+
                                        '$.ajax({'+
                                            'url: "/ajax/contests1",'+
                                            'type: "DELETE",'+
                                            'data: {"execid":"'+obj[i].execid+'"},'+
                                            'async: true,'+
                                            'success: function(){'+
                                                'window.location.reload();'+
                                            '}'+
                                        '})'+
                                    '});'+
                                '});'+
                            '<\/script>'+
                            '</td></tr>';
                    $("#executable_list").append(executable);
                    // edit
                    var modal = '<div class="modal fade my_modal" id="modal_edit'+obj[i].execid+'" tabindex="-1">'+
                            '<div class="modal-dialog">'+
                                '<div class="modal-content">'+
                                    '<div class="modal-header">'+
                                        '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>'+
                                        '<h4 class="modal-title text-center">Edit Executable</h4>'+
                                    '</div>'+
                                    '<div class="modal-body">'+
                                        '<form enctype="multipart/form-data" id="tmp_form'+obj[i].execid+'">'+
                                        '{{ csrf_field() }}'+
                                            '<div class="row">'+
                                                '<label class="col-md-3 pull-left">ID</label>'+
                                                '<input class="col-md-8" name="execid" type="text" value="'+obj[i].execid+'">'+
                                            '</div>'+
                                            '<div class="row">'+
                                                '<label class="col-md-3">Type</label>'+
                                                '<input class="col-md-8" name="type" type="text" value="'+obj[i].type+'">'+
                                            '</div>'+
                                            '<div class="row">'+
                                                '<label class="col-md-3">Old Input File</label>'+
                                                '<input class="col-md-8" value="'+obj[i].input_file+'"disabled>'+
                                            '</div>'+
                                            '<div class="row">'+
                                                '<label class="col-md-3">New Input File</label>'+
                                                '<input class="col-md-8" name="file" type="file">'+
                                            '</div>'+
                                        '</form>'+
                                    '</div>'+
                                    '<div class="modal-footer">'+
                                        '<a class="btn btn-default" type="button"  data-dismiss="modal">Cancel</a>'+
                                        '<a class="btn btn-primary" type="button" id="tmp_form_submit'+obj[i].execid+'">Submit</a>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<script>'+
                            '$(function(){'+
                                '$("#tmp_form_submit'+obj[i].execid+'").click(function(){'+
                                    'var form = new FormData($("#tmp_form'+obj[i].execid+'")[0]);'+
                                    '$.ajax({'+
                                        'url: "/ajax/contests",'+
                                        'type: "post",'+
                                        'processData: false,'+
                                        'contentType: false,'+
                                        'data: form,'+
                                        'success: function(){'+
                                            'window.location.reload();'+
                                        '}'+
                                    '})'+
                                '});'+
                            '});'+
                        '<\/script>';
                    $("#modal_list").append(modal);
                }
            }
        })
    })
</script>
<script>

</script>
</body>
</html>