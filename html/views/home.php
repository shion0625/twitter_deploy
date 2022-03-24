<?php
use Classes\Post\GetHomePosts;

//投稿内容をデータベースから取得

//データベースに投稿内容を保存
$get_post_db = new GetHomePosts();
$user_posts = $get_post_db->getHomePosts();
?>

<script type="text/javascript">
    const username = <?php echo json_encode($_SESSION['username']);?>;
    const userId = <?php echo json_encode($_SESSION['userID']);?>;
    const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
    const surroundSpan = async() => {
      let t = $('#js-post-content');
      let nodeList = t[0].childNodes;
      let nextNodeList = [];
      console.log(nodeList);
      let returnElement = document.createElement('br');
      for(const node of nodeList){
        if(node.tagName == "DIV"){
          console.log(node.childNodes[0]);
          nextNodeList.push(returnElement);
          nextNodeList.push(node.childNodes[0]);
        }else{
          nextNodeList.push(node);
        }
      }
      console.log(nextNodeList);
      let newNodeList = [];
      for (let i = 0; i < nextNodeList.length; i++) {
        let element = nextNodeList[i];
        console.log(element);
        let newElement;
        if (element.tagName == "SPAN") {
          if (element.innerHTML.length > 1) {
            let className = element.className.trim();
            let classNameList = className.split(" ").filter(Boolean);
            let charList = element.innerHTML.split("");
            for (let i in charList) {
              let newElement = document.createElement('span');
              newElement.innerHTML = charList[i];
              if (classNameList.length != 0) {
                for (let i in classNameList) {
                  newElement.classList.add(classNameList[i]);
                }
              }
              newNodeList.push(newElement);
            }
          } else if (element.innerHTML.length == 0) {
            element.remove();
          } else {
            newNodeList.push(element);
          }
        } else if(element.tagName == "BR"){
          newNodeList.push(element);
        }else {
          let charList = element.data.split("");
          for (let i in charList) {
            newElement = document.createElement('span');
            newElement.innerHTML = charList[i];
            newNodeList.push(newElement);
          }
        }
      }
      console.log(nodeList);
      console.log('hi');
      if (newNodeList.length != 0) {
        t[0].innerHTML = '';
        for (let i = newNodeList.length; i >= 0; i--) {
          t.prepend(newNodeList[i]);
        }
      }
      return 0;
    }

    async function txtChange(e) {
      let result = await surroundSpan();
      if(result == 0){
      }
      document.querySelectorAll('[type=button][data-decoration]').forEach(x => {
        x.addEventListener('click', () => {
          const decoration = x.dataset["decoration"];
          const sel = getSelection();
          if (sel.focusNode !== null) {
            let start = sel.getRangeAt(0).startContainer.parentNode;
            let end = sel.getRangeAt(0).endContainer.parentNode;
            if (start.closest('#js-post-content') && end.closest('#js-post-content')) {
              const dom = [...sel.getRangeAt(0).cloneContents().querySelectorAll('span')];
              const parent = end.parentNode;
              console.log(dom);
              console.log(dom);
                dom.shift();
              }
              if (dom[dom.length - 1].textContent == "") {
                dom.pop();
              } else {
                end = end.nextElementSibling;
              }
              sel.deleteFromDocument();
              sel.removeAllRanges();
              dom.forEach(x => {
                x.classList.toggle(decoration);
                parent.insertBefore(x, end);
              });
            }
          });
        });
      }

    $(document).on('click', '.text-button', async function (e) {
      txtChange(e);
    });

const getPostContent = ()=>{
    let postText = $('#js-post-content')[0].innerHTML.toString();
    postText = htmlentities(changeTag(postText));
    let $map = {"postText": postText, "send": "postSend", "sender": userId};
    $.ajax({
        type: 'POST',
        url: './views/component/AjaxPosts.php',
        data: $map,
        dataType: 'html'
    }).done(function(data){
      alert("successful");
    }).fail(function(msg, XMLHttpRequest, textStatus, errorThrown){
        alert("error: "+msg.responseText);
        console.log(msg);
        console.log(XMLHttpRequest.status);
        console.log(textStatus);
        console.log(errorThrown);
    });
}

function changeTag(str){
    console.log(str);
    return String(str).replace(/<span/g, "Š;")
        .replace(/<\/span>/g,"/Š;")
        .replace(/class="/g,"č;")
    }

function returnHtmlentities(str){
    return String(str).replace(/&lt;/g,"<")
    // .replace(/&amp;/g,"&")
        .replace(/&gt;/g,">")
    // .replace(/&quot;/g,"\"")
}

function htmlentities(str){
    return String(str).replace(/&/g,"&amp;")
        .replace(/</g,"&lt;")
        .replace(/>/g,"&gt;")
        .replace(/"/g,"&quot;")
}
</script>

<script type="text/javascript"src="../assets/js/websocket.js"></script>

<div class='home-all-contents'>
    <div class=tweet-btn>
        <button id="js-show-popup">ツイートする</button>
    </div>
    <?php if (!empty($_SESSION["userID"])) :?>
    <div class="popup" id="js-popup">
    <div class="popup-inner">
        <div class="close-btn" id="js-close-btn">
            <i class="fas fa-times"></i>
        </div>
        <button
        class="tweet-submit-btn btn"
        name="send"
        form="tweet"
        onclick=" getPostContent(); socketSend();">ツイートする</button>
        <div id="tweet" id="js-tweet-form" class="tweet-form">
            <label for="post-content">投稿を入力して下さい</label>
            <div id="js-post-content" class="tweet-textarea"  role="textbox"
            contenteditable="true"
            aria-multiline="true" aria-required="true" aria-autocomplete="list" spellcheck="auto" dir="auto"
            name="tweet-input"></div>
        </div>
        <p class="tweet-items">
                <button type="button" class="tweet-item text-button"
                id="js-strong" data-decoration="bold" value="bold">
                  <i class="fas fa-bold" data-decoration="bold"></i>
                </い>
                <button type="button" class="tweet-item text-button"
                id="js-italic" data-decoration="italic" value="italic">
                  <i class="fas fa-italic" data-decoration="italic"></i>
                </button>
                <button type="button" class="tweet-item text-button"
                id="js-underline" data-decoration="underline" value="underline">
                  <i class="fas fa-underline" data-decoration="underline"></i>
                </button>
                <!-- <button type="button" class="tweet-item" id="js-link"><i class="fas fa-link"></i></button>
                <button type="button" class="tweet-item" id="js-paperclip"><i class="fas fa-paperclip"></i></button>
                <button type="button" class="tweet-item" id="js-image"><i class="far fa-image"></i></button> -->
                <small style="color:red">文字を入力後、左のボタンを1度押すと太文字などが反応します。</small>
        </p>
    </div>
    <div class="black-background" id="js-black-bg"></div>
    </div>
    <?php else :?>
    <div class="popup" id="js-popup">
    <div class="popup-inner">
    <div class="close-btn" id="js-close-btn">
        <i class="fas fa-times"></i>
    </div>
        <p class="tweet-not-login">
            ログインしてください。
        </p>
    </div>
    <div class="black-background" id="js-black-bg"></div>
    </div>
    <?php endif;?>
    <div id="js-posts" class="user-posts">
        <!-- <div id="js-posts"></div> -->
            <?php include(__DIR__ . '/component/user_posts.php')?>
    </div>
</div>

<!-- 012345<span>6789AB</span>CDEFGHIJKLMNOPQRSTUVWXYZ -->
<!-- 私の名前は淀川海都です。\nよろしくお願いします。 -->