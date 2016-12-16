<!doctype html>
<html>
<head>
  <title>Add Contest</title>
  @include("layout.head")
  @include("layout.dashboard_header")
  <link rel="stylesheet" href="/css/main.css">
  <script src="/js/searchFunction.js"></script>
  <style>
    .student-list {
      margin-top: 160px;
    }

    form {
      padding: 0;
      margin: 0;
    }

    #private-table table {
      margin: 0;
      width: 100%;
    }

    #tmp_form {
      position: absolute;
      top: 80px;
      right: 80px;
    }

    .student-list-checkbox-table {
      max-height: 1000px;
      overflow-y: scroll;
    }

    #mymodal {
      margin-top: 5%;
    }

    #mymodal .modal-dialog {
      width: 35%;
    }

    #mymodal button {
      border-radius: 3px;
    }
  </style>
</head>
<body>
@include("layout.dashboard_nav")
<div class="back-container">
  <!--modal for error display-->
  <div id="error_list">
    <div class="modal fade" id="mymodal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                  class="sr-only">Close</span></button>
            <h4 class="modal-title">Error</h4>
          </div>
          <div class="modal-body">
            <p>1. 可能上传的文件不是xls类型<br/>
              2. 可能设定的列在上传的文件中不存在<br/>
              3. 可能获得的是空白的信息<br/>
              请重新导入.
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Get</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <form class="back-problem-form" action="/dashboard/contest/add" method="post">
    {{ csrf_field() }}
    <div class="contest-left">
      <h3 class="custom-heading" id="dashboard-custom-heading">Add Contest</h3>
      <table class="custom-table">
        @foreach($errors->all() as $error)
        <tr>
            <td colspan="2"><span class="label label-warning">{{ $error }}</span></td>
          </tr>
        @endforeach
        <tr>
          <td>Contest Name</td>
          <td><input class="form-control" name="contest_name" type="text" value="{{ old('contest_name') }}" required/>
          </td>
        </tr>
        <tr>
          <td>Begin Time</td>
          <td><input class="form-control" name="begin_time" type="datetime-local" value="{{ old('begin_time') }}"
                     required/></td>
        </tr>
        <tr>
          <td>End Time</td>
          <td><input class="form-control" name="end_time" type="datetime-local" value="{{ old('end_time') }}" required/>
          </td>
        </tr>
        <tr>
          <td>Contest Type</td>
          <td>
            <input id="public-radio" name="contest_type" type="radio" value="public" checked/>public
            <input id="private-radio" name="contest_type" type="radio" value="private"/>private
            <input id="register-radio" name="contest_type" type="radio" value="register"/>register
          </td>
        </tr>
      </table>
      <div class="contest-b-private-table" id="private-table">
        <table class="custom-table">
          <tr>
            <td>Allowed Username</td>
            <td><textarea id="list_txt" class="form-control resize-none" name="user_list"
                          placeholder="Input the user name , seperate each with comma" rows="5"></textarea></td>
          </tr>
        </table>
      </div>
      <div id="register-table">
        <table class="custom-table">
          <tr>
            <td>Register Begin Time</td>
            <td><input class="form-control" type="datetime-local" name="register_begin_time"
                       value="{{ old('register_begin_time') }}"/></td>
          </tr>
          <tr>
            <td>Register End Time</td>
            <td><input class="form-control" type="datetime-local" name="register_end_time"
                       value="{{ old('register_end_time') }}"/></td>
          </tr>
        </table>
      </div>
      <div class="training-b-add-chapter text-center">
        <label>Select Problem</label>
        <a href="javascript:addProblem()">Add Problem</a>
      </div>
      <div class="back-problem-add-list text-center"></div>
      <input class="center-block" type="submit" value="Submit"/>
    </div>
  </form>
</div>

<script src="/js/tableExport.js"></script>
<script src="/js/jquery.base64.js"></script>
<script type="text/javascript">
  /*tab of contest type*/
  $(function () {
    $("#dashboard_contest").addClass("dashboard-subnav-active");
    if ($('#public-radio')[0].checked) {
      $('#private-table').hide();
      $('#register-table').hide();
    } else if ($('#private-radio')[0].checked) {
      $('#private-table').show();
      $('#register-table').hide();
    } else {
      $('#private-table').hide();
      $('#register-table').show();
    }
    $('#public-radio').click(function () {
      $('#private-table').slideUp();
      $('#register-table').slideUp();
    })
    $('#private-radio').click(function () {
      $('#private-table').slideDown();
      $('#register-table').slideUp();
    })
    $('#register-radio').click(function () {
      $('#private-table').slideUp();
      $('#register-table').slideDown();
    })
  })
</script>
<script>
  /*add private contest*/
  function getList() {
    var form = new FormData($("#tmp_form")[0]);
    $.ajax({
      url: '/ajax/memberlist',
      type: 'post',
      processData: false,
      contentType: false,
      data: form,
      success: function (data) {
        if (data == '' || data == null) {
          $("#mymodal").modal();
        } else {
          $(".student-list-checkbox").children('tr').remove();
          var datas = new Array();
          datas = data.split(",");
          for (var i = 0; i < datas.length - 1; i++) {
            var student = '<tr>' +
                '<td><input type="checkbox" value="' + datas[i] + '" class="checked" id="checked' + i + '" checked></td>' +
                '<td>' + (i + 1) + '</td>' +
                '<td>' + datas[i] + '</td>' +
                '<script>' +
                '$("#checked' + i + '").click(function(){' +
                'var checkboxes = $(".checked");' +
                'var list = "";' +
                'for(var i=0;i<checkboxes.length;i++) {' +
                'if(checkboxes[i].checked == true) {' +
                'if(i != checkboxes.length-1)' +
                '	list += checkboxes[i].value+",";' +
                'else' +
                '	list += checkboxes[i].value;' +
                '}' +
                '}' +
                '$("#list_txt").val(list);' +
                '})' +
                '<\/script>' +
                '</tr>';
            $(".student-list-checkbox").append(student);
          }
          var checkboxes = $(".checked");
          var list = "";
          for (var i = 0; i < checkboxes.length; i++) {
            if (i != checkboxes.length - 1)
              list += checkboxes[i].value + ",";
            else
              list += checkboxes[i].value;
          }
          $("#list_txt").val(list);
        }
      }
    });
  }
  function appendRight() {
    var right = '<div class="col-md-6 student-list contest-right">' +
        '<div class="student-list">' +
        '<div class="student-list-checkbox-table">' +
        '<table class="table table-bordered table-hover ">' +
        '<thead>' +
        '<tr>' +
        '<th>选择</th>' +
        '<th>顺序</th>' +
        '<th>指定列</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody class="student-list-checkbox">' +
        '</tbody>' +
        '</table>' +
        '</div>' +
        '</div>' +
        '</div>';
    $(".back-problem-form").append(right);
    var form = '';
    form += '<form enctype="multipart/form-data" id="tmp_form">' +
        '{{ csrf_field() }}';
    form += '<h4>Randomly generate user</h4>' +
        '* Number: &nbsp;&nbsp;<input id="random_num_input" value=""/><br/>' +
        '<button id="random_generate_btn" class="btn btn-grey" data-loading-text="Generating, waiting..." type="button">Generate</button><br/>';
    form += '<div id="random_user_table_box"><table id="random_user_table">' +
        '<tr><td>username</td><td>password</td>' +
        '</table></div>';
    form += '<h4>Import user list</h4>' +
        '* Choose Column: &nbsp;&nbsp;<input id="selected_col" name="selected_col" value=""/><br/>' +
        '* Choose File: &nbsp;&nbsp;<input id="import-user" name="memberlist" style="display: inline-block" type="file" />' +
        '<input id="file_type" name="file_type" value="xls" hidden/><br/>' +
        '<input id="import_btn" type="button" value="import">' +
        '</form><br/>';
    $(".back-container").append(form);

    /*randomly generate users*/
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $("#random_generate_btn").click(function(){
      var num = $('#random_num_input').val().trim();
      if(num != '') {
        $(this).button("loading");
        $.ajax({
          url: '/dashboard/contest/randusers/'+ num,
          type: 'POST',
          async: 'true',
          dataType: 'json',
          success: function(json) {
            console.log(json);
            var random_list = '';
            for(var item in json) {
              var tr = '<tr><td>' + item + '</td><td>' + json[item] + '</td></tr>';
              if(random_list == '') {
                random_list += item;
              } else {
                random_list += ',' + item;
              }
              $('#random_user_table').append(tr);
            }
            $('#list_txt').val(random_list);
            var random_export_btn = '<button id="random_export_btn" class="btn btn-grey" type="button">Export</button>';
            $('#random_generate_btn').replaceWith(random_export_btn);
            $('#random_export_btn').click(function(){
              $('#random_user_table').tableExport({
                type: 'excel',
                escape:'false',
                fileName: 'memberList'
              })
            });
          }
        })
      } else {
        $('#random_num_input').val('Please input number!!!');
      }
    });
  }
  var lastClick = -1;//0 means public or register, 1 mean private
  $(function () {
    $("#public-radio").click(function () {
      lastClick = 0;
      $(".contest-left").removeClass("col-md-6");
      $("#tmp_form").remove();
      $(".contest-right").remove();
    });
    $("#register-radio").click(function () {
      lastClick = 0;
      $(".contest-left").removeClass("col-md-6");
      $("#tmp_form").remove();
      $(".contest-right").remove();

    });
    $("#private-radio").click(function () {
      if (lastClick != 1) {
        lastClick = 1;
        $(".contest-left").addClass("col-md-6");
        appendRight();
        $("#import_btn").click(getList);
      }
    });
    if ($("#private-radio")[0].checked == true) {
      $("#private-radio").click();
    }
  });
</script>
<script type="text/javascript">
  /*ajax for problem*/
  var titleData = [];
  $.ajax({
    url: '/ajax/problem_title',
    type: 'GET',
    async: true,
    dataType: 'json',
    success: function (result) {
      titleData = result;
    }
  });
  var count = 0;
  function addProblem() {
    var problemItem = '<div id=p_' + count + '>' +
        '<span>ID </span>' +
        '<div class="search-container">' +
        '<input class="form-control search-title problem-id contest-b-problem-input" type="text" name="problem_id[]" autocomplete="off" />' +
        '<div class="search-option hidden"></div>' +
        '</div>' +
        '<span>Title </span>' +
        '<input class="form-control problem-title contest-b-problem-input" type="text" name="problem_name[]" autocomplete="off" />' +
        '<span>Color</span>' +
        '<input class="form-control" style="width:5%;padding:0;" type="color" name=problem_color[] />' +
        '<a href="javascript:delProblem(' + count + ')">Delete</a>' +
        '</div>';
    $('.back-problem-add-list').append(problemItem);
    count++;
    bindSearchFunction(titleData);
  }
  function delProblem(divId) {
    $('#p_' + divId).remove();
  }
</script>
</body>
</html>