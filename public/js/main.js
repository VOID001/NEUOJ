var storage = window.localStorage;
var array = new Array();

$(function () {
  var channel = $('#contest-name').val() || '0';
  /*set chatroom-box height*/
  var settop = $('.chatroom-box').offset().top;
  $('.chatroom-content').height($(window).height()-100);
  $('.chatroom-content').css('top',-(settop-50));
  $('.chatroom-online-list').height($(window).height()-100);
  $('.chatroom-online-list').css('top',-(settop-50));
  $('.chatroom-online-body').height($(window).height()-100);
  $('.chatroom-body').height($(window).height()*0.7);

  /*sliderToggle*/
  $('.chatroom-btn').mouseenter(function () {
      $('#chat-count-badge').text('');
      storage.setItem('num_of_unread', '0'.toString());
      $('.chatroom-icon').hide(500);
      $('.chatroom-content').show(500);
  });
  $('.chatroom-content').mouseleave(function () {
      $('.chatroom-icon').show();
      $('.chatroom-content').hide();
  });
  
  $('.chatroom-online').mouseenter(function () {
      $('.chatroom-icon').hide(500);
      $('.chatroom-online-list').show(500);
  });
  $('.chatroom-online-list').mouseleave(function () {
      $('.chatroom-icon').show();
      $('.chatroom-online-list').hide();
  });

  /*init message*/
  array = [];
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    url: '/chatroom/record/' + channel + '/66',
    type: 'POST',
    async: 'true',
    dataType: 'json',
    success: function (json) {
      for (var item in json) {
        if(json[item]['channel'] != null) {
          var object = new Object();
          object['channel'] = json[item]['channel'];
          object['message'] = json[item]['message'];
          object['time'] = json[item]['time'];
          object['username'] = json[item]['username'];
          array.push(object);
        }
      }
      storage.setItem('chat_record' + channel, JSON.stringify(array.reverse()));
      if (storage.getItem('chat_record' + channel) != null) {
        array = JSON.parse(storage.getItem('chat_record' + channel));
        for (var i = 0; i < array.length; i++) {
          var content = '<div class="chatroom-msg">' +
              '<div class="col-md-3 custom-word chatroom-msg-username">' + array[i].username + '</div>';
          var username = $('#personal-username-btn').text();
          if (username == array[i].username) {
            content += '<div class="col-md-9 chatroom-msg-content chatroom-msg-content-mine">';
          } else {
            content += '<div class="col-md-9 chatroom-msg-content chatroom-msg-content-other">';
          }
          content += html2Escape(array[i].message) + '</div>' +
              '<div style="clear: both"></div>';
          $('#message-list').append(content);
        }
      }
      console.log($.cookie('first-login'));
      if($.cookie('first-login') == null) {
        $('#chat-count-badge').text(array.length.toString());
        $.cookie('first-login', 'false', {path: '/'});
      }
    },
    error: function () {
      console.log('ajaxRecord Error');
    }
  });

  //send message
  $('.chatroom-form').submit(function () {
    var $message = $('#chatroom-input').val();
    if ($message.trim() == '') {
      return false;
    }
    var form = new FormData($('.chatroom-form')[0]);
    $.ajax({
      url: '/chatroom/send',
      type: 'post',
      processData: false,
      contentType: false,
      data: form,
      dataType: "json"
    });
    $('.chatroom-input').val('');
    return false;
  });
});

/**socket**/
$(function () {
  var socket = io.connect("http://localhost:3000");
  var channel = $('#contest-name').val() || '0';
        /*get user info*/
          $.ajax({
              url: '/ajax/user',
              type: 'GET',
              async: true,
              dataType: 'json',
              success: function(data) {
                  console.log(data);
                  var username = data['username'];
                  var user_id = data['user_id'];
                  socket.emit('join', channel,data);
              }
          })

  /*get online count*/
  socket.on('get_count', function (data) {
    $('.chatroom-online-count').text('online: ' + data);
  });
  socket.on('getOnlineUsers', function (data) {
    for (var i = 0;i < data.length;i++)
    {
      var usercount = '<div id="user' + i + '"></div>';
      $('#user-list').append(usercount);
      var userinfo = '<div class="chatroom-userinfo">' + '<img class="img-circle" width="40" height="40" src="/avatar/' + data[i].user_id + '" />'
       + '<div class="chatroom-username">' + data[i].username + '</div>' + '</div>';
      $('#user'+i).html(userinfo);
    }
  });
  socket.on(channel, function (msg) {
    /*update UI*/
    var msg = eval('(' + msg + ')');
    var content = '<div class="chatroom-msg">' +
        '<div class="col-md-3 custom-word chatroom-msg-username">' + msg.username + '</div>';
    var username = $('#personal-username-btn').text();
    if (username == msg.username) {
      content += '<div class="col-md-9 chatroom-msg-content chatroom-msg-content-mine">';
    } else {
      content += '<div class="col-md-9 chatroom-msg-content chatroom-msg-content-other">';
    }
    content += html2Escape(msg.message) + '</div>' +
        '<div style="clear: both"></div>';
    $('#message-list').append(content);
    chatBodyToBottom();

    //get number of unread message
    var $img = $('.chatroom-btn img');
    var num_of_unread = 0;
    if ($img.getRotateAngle() != 180) {
      if (storage.getItem('num_of_unread') != null) {
        num_of_unread = parseInt(storage.getItem('num_of_unread'));
      }
      num_of_unread++;
      if (num_of_unread > 66) {
        $('#chat-count-badge').text('66+');
      } else {
        $('#chat-count-badge').text(num_of_unread);
      }
    }
    storage.setItem('num_of_unread', num_of_unread.toString());
    console.log('num_of_unread:' + num_of_unread);

    //localstorage
    if (storage.getItem('chat_record' + channel) != null) {
      array = JSON.parse(storage.getItem('chat_record' + channel));
    }
    if (array.length >= 20) {
      array.shift();
    }
    var object = new Object();
    object['channel'] = msg.channel;
    object['message'] = msg.message;
    object['time'] = msg.time;
    object['username'] = msg.username;
    array.push(object);
    storage.setItem('chat_record' + channel, JSON.stringify(array));
  });
});

/**function**/
function chatBodyToBottom() {
  var height = $('.chatroom-body').prop('scrollHeight');
  $('.chatroom-body').prop('scrollTop', height);
}
function html2Escape(myHtml) {
  if(myHtml != '' && myHtml != null)
  return myHtml.replace(/[<>&"]/g, function (c) {
    return {'<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;'}[c];
  });
}

// $('#logout').click(function () {
//   $.ajax({
//     url: '/ajax/user',
//     type: 'GET',
//     async: true,
//     dataType: 'json',
//     success: function(data) {
//       console.log(data);
//       var socket = io.connect("http://localhost:3000");
//       var username =data['username'];
//       var user_id = data['user_id'];
//       socket.emit('logout', data);
//     }
//   });
// });
