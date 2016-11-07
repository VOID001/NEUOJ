$(function () {
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
    }
    $content_div.slideToggle(500);
  });
});

/*socket*/
var socket = io.connect("http://localhost:3000");
socket.on('message', function(msg) {
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
});
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