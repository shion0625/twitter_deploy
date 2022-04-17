<?php

use Classes\Post\GetHomePosts;
use Classes\Post\AllPostNum;

//最大ページ数を求める。
$all_post_num = new AllPostNum();
$max_page = $all_post_num->allPostNum();

/**
 * 各投稿内容の表示ページで使用している。
 * GETメソッドで送信されたページ番号を取得している。それを元にデータベースからは必要な分取得している。
 */
$page_num = filter_input(INPUT_GET, 'page_num', FILTER_SANITIZE_NUMBER_INT);
$page_num = ($page_num ?: 1);
$start_num = ($page_num - 1) * 15;

//投稿内容をデータベースから取得
$get_post_db = new GetHomePosts();
$user_posts = $get_post_db->getHomePosts($start_num);
?>

<div id="js-test-contents"></div>
<div class='home-all-contents'>
  <div class=tweet-btn>
    <button id="js-show-popup" class="tweet-submit-btn btn120">ツイートする</button>
  </div>
  <?php if (!empty($_SESSION["userID"])) :?>
  <div class="popup" id="js-popup">
    <div class="popup-inner">
      <div class="close-btn" id="js-close-btn">
        <i class="fas fa-times"></i>
      </div>
      <div class="post-btn-container">
        <button id="js-post-btn" class="tweet-btn" name="send" form="tweet" onclick="getPostContent();"></button>
      </div>
      <div id="editor"></div>
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
    <?php include(__DIR__ . '/component/user_posts.php')?>
  </div>
</div>

<script type="text/javascript" src="assets/js/quill.min.js"></script>

<script>
'use strict';
let userId;
<?php if (!empty($_SESSION["userID"])):?>
userId = <?php echo json_encode($_SESSION['userID']);?>
<?php endif;?>


function validate(flag) {
  $("#js-post-btn").removeClass("onclic");
  if (flag) $("#js-post-btn").addClass("validate", 200, successCallback());
  else $("#js-post-btn").addClass("fail-validate", 200, failCallback());
}

function successCallback() {
  setTimeout(() => {
    $("#js-post-btn").removeClass("validate");
  }, 2000);
}

function failCallback() {
  setTimeout(() => {
    $("#js-post-btn").removeClass("fail-validate");
  }, 2000);
}

var toolbarOptions;

var windowWidth = $(window).width();
var windowSm = 630;
if (windowWidth <= windowSm) {
  toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],

    [{
      'list': 'ordered'
    }, {
      'list': 'bullet'
    }],

    [{
      'script': 'sub'
    }, {
      'script': 'super'
    }],

    [{
      'direction': 'rtl'
    }],

    [{
      'size': ['small', false, 'large', 'huge']
    }],
    [{
      'header': [1, 2, 3, 4, 5, 6, false]
    }],

    [{
      'color': []
    }, {
      'background': []
    }],
    [{
      'font': []
    }],
    [{
      'align': []
    }],

    ['clean']
  ];
} else {
  toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],
    [{
      'header': 1
    }, {
      'header': 2
    }],

    [{
      'list': 'ordered'
    }, {
      'list': 'bullet'
    }],

    [{
      'script': 'sub'
    }, {
      'script': 'super'
    }],
    [{
      'indent': '-1'
    }, {
      'indent': '+1'
    }],
    [{
      'direction': 'rtl'
    }],

    [{
      'size': ['small', false, 'large', 'huge']
    }],
    [{
      'header': [1, 2, 3, 4, 5, 6, false]
    }],

    [{
      'color': []
    }, {
      'background': []
    }],
    [{
      'font': []
    }],
    [{
      'align': []
    }],

    ['clean']
  ];
}

// Initialize Quill editor

const options = {
  // debug: 'info',
  modules: {
    toolbar: toolbarOptions
  },
  placeholder: '投稿内容を記入してください！！',
  readOnly: false,
  theme: 'snow'
};

let quill;
if ($('#editor')[0]) {
  quill = new Quill('#editor', options);
  quill.on('text-change', function(delta, oldDelta, source) {
    $('#js-get-post-content').val($('.ql-editor').html());
  });
}

function getPostContent() {
  $("#js-post-btn").addClass("onclic", 200);
  if (!quill) {
    setTimeout(() => {
      validate(false);
      alert_animation("不正な入力です。");
    }, 800);
  }
  let inputData = quill.root.innerHTML;
  let length = quill.getLength();
  let inputValue = $('.ql-editor')[0].innerText.trim().length;

  if (length <= 1 || inputValue == 0) {
    setTimeout(() => {
      validate(false);
      alert_animation("投稿内容が入力されていません。");
    }, 800);
    return;
  } else if (!userId) {
    setTimeout(() => {
      validate(false);
      alert_animation("ログインしてから投稿してください。");
    }, 800);
    return;
  }

  let map = {
    postHtml: inputData,
    send: "postSend",
    sender: userId
  };
  $.ajax({
      type: "POST",
      url: "./views/component/AjaxPosts.php",
      data: map,
      dataType: "text",
    })
    .done(function(data) {
      socketSend();
      setTimeout(() => {
        validate(true);
        alert_animation("投稿が正常に完了しました。");
        quill.deleteText(0, 100000);
      }, 800);
    })
    .fail(function(msg, XMLHttpRequest, textStatus, errorThrown) {
      validate(false);
      alert_animation('投稿に失敗しました。');
      console.log(msg);
      console.log(XMLHttpRequest.status);
      console.log(textStatus);
      console.log(errorThrown);
    });
}
</script>
<script type="text/javascript" src="assets/js/websocket.js"></script>