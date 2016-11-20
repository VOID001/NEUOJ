var storage = window.localStorage;
var array = new Array();

$(function () {
  var channel = $('#contest-name').val() || '0';
  //sliderToggle
  $('.chatroom-btn').click(function() {
    var $img = $('.chatroom-btn img');
    var $content_div = $('.chatroom-content');
    if($img.getRotateAngle() == 180) {
      $img.rotate({
        angle: 180,
        animateTo: 360,
        duration: 800
      });
    } else {
      $img.rotate({
        angle: 0,
        animateTo: 180,
        duration: 800
      });
      $('#chat-count-badge').text('');
      storage.setItem('num_of_unread', '0'.toString());
    }
    $content_div.slideToggle(500);
  });

  //init message
  var $count = $('.chatroom-msg').length;
  if($count == 0) {
    if(storage.getItem('chat_record' + channel) != null) {
      array = JSON.parse(storage.getItem('chat_record' + channel));
      for (var i = 0; i < array.length; i++) {
        $('#message-list').append(array[i]);
      }
    }
  }

  //send message
  $('.chatroom-form').submit(function() {
    var $message = $('#chatroom-input').val();
    if($message.trim() == '') {
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
$(function() {
  var socket = io.connect("http://localhost:3000");
  var channel = $('#contest-name').val() || '0';

  //get online count
  socket.on('get_count', function(data) {
    $('.chatroom-online-count').text('online: ' + data);
  });

  socket.on(channel , function(msg) {

    //update UI
    var msg = eval('(' + msg + ')');
    var content = '<div class="chatroom-msg">' +
        '<div class="col-md-3 custom-word chatroom-msg-username">' + msg.username + '</div>';
    var username = $('#personal-username-btn').text();
    if(username == msg.username) {
      content += '<div class="col-md-9 chatroom-msg-content chatroom-msg-content-mine">';
    } else  {
      content += '<div class="col-md-9 chatroom-msg-content chatroom-msg-content-other">';
    }
    content += msg.message + '</div>' +
        '<div style="clear: both"></div>';
    $('#message-list').append(content);
    chatBodyToBottom();

    //get number of unread message
    var $img = $('.chatroom-btn img');
    var num_of_unread = 0;
    if($img.getRotateAngle() != 180) {
      if(storage.getItem('num_of_unread') != null) {
        num_of_unread = parseInt(storage.getItem('num_of_unread'));
      }
      num_of_unread ++;
      if(num_of_unread > 66) {
        $('#chat-count-badge').text('66+');
      } else {
        $('#chat-count-badge').text(num_of_unread);
      }
    }
    storage.setItem('num_of_unread', num_of_unread.toString());
    console.log('num_of_unread:' + num_of_unread);


    //localstorage
    if(storage.getItem('chat_record' + channel) != null) {
      array = JSON.parse(storage.getItem('chat_record' + channel));
    }
    if(array.length >= 20) {
      array.shift();
    }
    array.push(content);
    storage.setItem('chat_record' + channel, JSON.stringify(array));
  });
});

/**function**/
function chatBodyToBottom() {
  var height = $('.chatroom-body').prop('scrollHeight');
  $('.chatroom-body').prop('scrollTop', height);
}
