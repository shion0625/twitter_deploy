<?php
use Classes\Post\GetHomePosts;

//投稿内容をデータベースから取得

//データベースに投稿内容を保存
$get_post_db = new GetHomePosts();
$user_posts = $get_post_db->getHomePosts();
?>

<script type="text/javascript">
'use strict';
const username = <?php echo json_encode($_SESSION['username']);?>;
const userId = <?php echo json_encode($_SESSION['userID']);?>;

$(document).on("click", ".text-button", async() => {
    txtChange();
    await surroundSpan();
});

function txtChange() {
    document.querySelectorAll("[type=button][data-decoration]").forEach((x) => {
        let cnt = 0;
        x.addEventListener("click", () => {
            if (cnt == 0) {
                const decoration = x.dataset["decoration"];
                const sel = getSelection();
                if (sel.focusNode !== null) {
                    const start = sel.getRangeAt(0).startContainer.parentNode;
                    const end = sel.getRangeAt(0).endContainer.parentNode;
                    if (
                        start.closest("#js-post-content") &&
                        end.closest("#js-post-content")
                    ) {
                        decorateSelectedTxt(sel, start, end, decoration);
                    }
                    cnt++;
                }
            }
        });
    });
}

function getPostContent() {
    let postText = $("#js-post-content")[0].innerHTML.toString();
    postText = htmlentities(changeTag(postText));
    let $map = { postText: postText, send: "postSend", sender: userId };
    $.ajax({
            type: "POST",
            url: "./views/component/AjaxPosts.php",
            data: $map,
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

function surroundSpan(result) {
    return new Promise((resolve, reject) => {
        let postText = $("#js-post-content");
        let nodeList = postText[0].childNodes;
        nodeList = lineFeed(nodeList);
        let newNodeList = [];
        for (let i = 0; i < nodeList.length; i++) {
            const element = nodeList[i];
            if (element.tagName == "SPAN" || element.tagName == "B" || element.tagName == "I") {
                if (element.innerHTML.length > 1) {
                    newNodeList = decompositionSpan(element, newNodeList);
                } else if (element.innerHTML.length == 0) {
                    element.remove();
                } else {
                    newNodeList.push(element);
                }
            } else if (element.tagName == "BR") {
                newNodeList.push(element);
            } else {
                newNodeList = encloseSpan(element, newNodeList);
            }
        }
        if (newNodeList.length != 0) {
            postText[0].innerHTML = "";
            for (let i = newNodeList.length; i >= 0; i--) {
                postText.prepend(newNodeList[i]);
            }
        }
        resolve();
    });
}

function lineFeed(nodeList) {
    let newNodeList = [];
    const returnElement = document.createElement("br");
    for (let node of nodeList) {
        if (node.tagName == "DIV") {
            newNodeList.push(returnElement);
            newNodeList.push(node.childNodes[0]);
        } else {
            newNodeList.push(node);
        }
    }
    return newNodeList;
}

function decompositionSpan(element, newNodeList) {
    let className = element.className.trim();
    if (element.tagName == 'B') className = "bold";
    if (element.tagName == 'I') className = "italic";
    const classNameList = className.split(" ").filter(Boolean);
    const charList = element.innerHTML.split("");
    for (let i in charList) {
        let newElement = document.createElement("span");
        newElement.innerHTML = charList[i];
        if (classNameList.length != 0) {
            for (let i in classNameList) {
                newElement.classList.add(classNameList[i]);
            }
        }
        newNodeList.push(newElement);
    }
    return newNodeList;
}

function encloseSpan(element, newNodeList) {
    if (element.textContent.length < 1) return;
    const charList = element.textContent.split("");
    for (let i in charList) {
        let newElement = document.createElement("span");
        newElement.innerHTML = charList[i];
        newNodeList.push(newElement);
    }
    return newNodeList;
}

function decorateSelectedTxt(sel, start, end, decoration) {
    const dom = [
        ...sel.getRangeAt(0).cloneContents().querySelectorAll("span, br"),
    ];
    if (dom.length == 0) {
        const txtElem = sel.getRangeAt(0).cloneContents().textContent;
        if (txtElem == "") {
            return;
        } else if (txtElem.length == 1) {
            start.innerHTML = txtElem;
            dom.push(start);
        } else {
            let startClassName = start.className.trim();
            if (start.tagName == 'B') startClassName = "bold";
            if (start.tagName == 'I') startClassName = "italic";
            const classNameList = startClassName.split(" ").filter(Boolean);
            const charList = txtElem.split("");
            const elmList = [];
            for (let i in charList) {
                let newElement = document.createElement("span");
                newElement.innerHTML = charList[i];
                if (classNameList.length != 0) {
                    for (let i in classNameList) {
                        newElement.classList.add(classNameList[i]);
                    }
                }
                dom.push(newElement);
            }
            let parent = end.parentNode;
            sel.deleteFromDocument();
            sel.removeAllRanges();
            dom.forEach((x) => {
                if (x.tagName != "BR") {
                    x.classList.toggle(decoration);
                }
                parent.appendChild(x, end);
            });
            return;
        }
    }
    let parent = end.parentNode;
    sel.deleteFromDocument();
    sel.removeAllRanges();
    dom.forEach((x) => {
        if (x.tagName != "BR") {
            x.classList.toggle(decoration);
        }
        parent.insertBefore(x, end);
    });
}

function changeTag(str) {
    return String(str)
        .replace(/<span/g, "Š;")
        .replace(/<\/span>/g, "/Š;")
        .replace(/class="/g, "č;");
}

function returnHtmlentities(str) {
    return (
        String(str)
        .replace(/&lt;/g, "<")
        // .replace(/&amp;/g,"&")
        .replace(/&gt;/g, ">")
    );
    // .replace(/&quot;/g,"\"")
}

function htmlentities(str) {
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");
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
        onclick="getPostContent();">ツイートする</button>
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
                  <i class="fas fa-bold" data-decoration="bold" ></i>
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
            <?php include(__DIR__ . '/component/user_posts.php')?>
    </div>
</div>
<!-- 012345<span>6789AB</span>CDEFGHIJKLMNOPQRSTUVWXYZ -->
<!-- 私の名前は淀川海都です。\nよろしくお願いします。 -->