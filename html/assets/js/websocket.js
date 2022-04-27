'use strict';

//ウェブソケットを使用して送信されたデータをサーバサイドに送信
let conn = "";
$(() => {
    conn = new WebSocket('ws://localhost:8081');
    // if(conn && conn.readyState === 1) return false;
    conn.onopen = (event) => {
        console.log("Connection established!");
    };

    conn.onerror = (event) => {
        alert("エラーが発生しました");
    };

    conn.onmessage = (event) => {
        $("#js-posts").prepend(event.data);
    };
    conn.onclose = function(event) {
        alert("切断しました");
        setTimeout(open, 5000);
    };
});

function socketSend() {
    var $map = { "send": "postInfo" };
    $.ajax({
        type: 'POST',
        url: './views/component/AjaxPosts.php',
        data: $map,
        dataType: 'html',
    }).done(function(data) {
        conn.send(data);
    }).fail(function(msg, XMLHttpRequest, textStatus, errorThrown) {
        alert("socketSend \nerror: \n" + msg.responseText);
    });
}

function close() {
    conn.close();
}
