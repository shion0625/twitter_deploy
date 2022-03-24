'use strict';
$(()=> {
  const popup = $('#js-popup');
  if(!popup) return;
  $('#js-black-bg').on('click', () => {
      popup.toggleClass('is-show');
  })
  $('#js-close-btn').on('click', () => {
      popup.toggleClass('is-show');
  })
  $('#js-show-popup').on('click', ()=> {
      popup.toggleClass('is-show');
  });
  });

//ウェブソケットを使用して送信されたデータをサーバサイドに送信
let conn = "";
$(() =>{
  conn = new WebSocket('ws://localhost:8081');
// if(conn && conn.readyState === 1) return false;
  conn.onopen = (event) => {
  console.log("Connection established!");
  };

  conn.onerror = (event) => {
  alert("エラーが発生しました");
  };

  conn.onmessage = (event) => {
    console.log(event);
    $("#js-posts").prepend(event.data);
  };
  conn.onclose = function(event) {
      alert("切断しました");
      setTimeout(open, 5000);
  };
});

function socketSend() {
  var $map = {"send": "postInfo"};
  $.ajax({
      type: 'POST',
      url: './views/component/ajax.php',
      data: $map,
      dataType: 'html',
    }).done(function(data){
      // ここに処理が完了したときのアクションを書く
      // alert("送信完了\nレスポンスデータ postInfo" + data);
      conn.send(data);
  }).fail(function(msg, XMLHttpRequest, textStatus, errorThrown){
      alert("error: "+msg.responseText);
      console.log(msg);
      console.log(XMLHttpRequest.status);
      console.log(textStatus);
      console.log(errorThrown);
  });

}
function close(){
  conn.close();
}

// function htmlentities(str){
//   return String(str).replace(/&/g,"&amp;")
//       .replace(/</g,"&lt;")
//       .replace(/>/g,"&gt;")
//       .replace(/"/g,"&quot;")
// }

// function createElem(element, className) {
//   const newElement = $("<"+element + " class=" + className +">")[0];
//   // console.log(newElement);
//   return newElement;
// }