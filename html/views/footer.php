<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<!-- Initialize Quill editor -->
<script>
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
  // let postObj = quill.getContents();
  // let postObj = quill.root.innerHTML;
  let inputData = $('#js-get-post-content').val();
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
    })
    .fail(function(msg, XMLHttpRequest, textStatus, errorThrown) {
      alert("getPostContent\nerror:\n" + msg.responseText);
      console.log(msg);
      console.log(XMLHttpRequest.status);
      console.log(textStatus);
      console.log(errorThrown);
    });
}
</script>

</body>

</html>