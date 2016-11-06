$(function () {
  $('.chatroom-btn').click(function() {
    var $content_div = $('.chatroom-content');
    $content_div.slideToggle(500);
  });
});

/*socket*/
var socket = io.connect("http://localhost:3000");
socket.on('message', function(msg) {
  var msg = eval('(' + msg + ')');
  var content = '<div class="chatroom-msg">' +
    '<div class="col-md-3 custom-word chatroom-msg-username">' + msg.username + '</div>' +
    '<div class="col-md-9 chatroom-msg-content">' + msg.message + '</div>' +
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