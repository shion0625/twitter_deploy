  <script type="text/javascript" src="../assets/js/index.js"></script>
  <script type="text/javascript" src="../assets/js/dltBtn.js"></script>
  <!-- Include the Quill library -->
  <script type="text/javascript" src="../assets/js/quill.min.js"></script>
  <!-- <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> -->
  <!-- Initialize Quill editor -->
  <script>
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

var toolbarOptions = [
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

const options = {
  // debug: 'info',
  modules: {
    toolbar: toolbarOptions
  },
  placeholder: '投稿内容を記入してください！！',
  readOnly: false,
  theme: 'snow'
};

const quill = new Quill('#editor', options);
quill.on('text-change', function(delta, oldDelta, source) {
  $('#js-get-post-content').val($('.ql-editor').html());
});

function getPostContent() {
  $("#js-post-btn").addClass("onclic", 200);
  let inputData = $('#js-get-post-content').val();
  if (inputData.innerText == undefined) {
    setTimeout(() => {
      validate(false);
      alert_animation("投稿内容が入力されていません。");
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

  </body>

  </html>