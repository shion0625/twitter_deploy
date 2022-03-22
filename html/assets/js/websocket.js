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
      const dataArray = event.data.split(">");
      let divObj = createElem("div", 'post');
      let postObj=$("#js-posts").prepend(divObj);
      for(let i in dataArray) {
          let data = document.createTextNode(dataArray[i].trim());
          let pObj;
          if(i == 0) {
              pObj = createElem("p", 'post-user-detail');
              let imgObj = createElem("img", 'user-post-img');
              let spanObj = createElem("span", 'tweet-username');
              spanObj.append(data);
              pObj.append(imgObj);
              pObj.append(spanObj);
          }
          else if(i == 1){
              pObj = createElem("p", 'tweet-content');
              pObj.append(data);
          }else if(i == 2) {
              pObj = createElem("p", 'appendix');
              let spanObj= createElem("span");
              spanObj.append(data);
              pObj.append(spanObj);
          }
          divObj.append(pObj);
      }
  };
  conn.onclose = function(event) {
      alert("切断しました");
      setTimeout(open, 5000);
  };
});

function socketSend() {
  let dateObj = new Date();
  const fullYear = dateObj.getFullYear();
  const month = dateObj.getMonth();
  const date = dateObj.getDate();
  const hours = dateObj.getHours();
  const minute = dateObj.getMinutes();
  const seconds = dateObj.getSeconds();

  const localDate = [fullYear, month, date].join("-");
  const localTime =[hours, minute, seconds].join(":");
  let postContent = $("#js-post-content").val();
  console.log(postContent);
  postContent = htmlentities(postContent);
  conn.send(username +
  ">"+postContent+
  ">"+localDate +" "+ localTime);
  console.log('send');
}
function close(){
  conn.close();
}


function htmlentities(str){
  return String(str).replace(/&/g,"&amp;")
      .replace(/</g,"&lt;")
      .replace(/>/g,"&gt;")
      .replace(/"/g,"&quot;")
}

function createElem(element, className) {
  const newElement = $("<"+element + " class=" + className +">")[0];
  console.log(newElement);
  return newElement;
}

