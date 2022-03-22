<?php
/**
 * htmlspecialcharsのラッパー関数
 *
 * @param string $str
 * @return string
 */
function fun_h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//すでにログインしている場合（ログインページの場合）
function fun_require_unlogined_session()
{
    if (isset($_SESSION['userID'])) {
        session_regenerate_id(true);
        header("Location: /");
        // echo "すでにログインしています。";
        exit();
    }
}
//まだログインしているかの判別
// function fun_require_logined_session() {
  // session_regenerate_id(TRUE);
//   if (!isset($_SESSION['userID'])) {
//     header('Location: /?page=login');
//     exit;
//   }
// }

function delete_session()
{
    session_unset();
    session_destroy();
    return;
}
/**
 * CSRFトークンの生成
 *
 * @return string トークン
 */
function fun_generate_token()
{
    // セッションIDからハッシュを生成
    return hash('sha256', session_id());
}

/**
 * CSRFトークンの検証
 *
 * @param string $token
 * @return bool 検証結果
 */
function fun_validate_token($token)
{
    // 送信されてきた$tokenがこちらで生成したハッシュと一致するか検証
    return $token === fun_generate_token();
}

function debug_to_console($data)
{
    if (is_array($data) || is_object($data)) {
        echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
    } else {
        echo("<script>console.log('PHP: $data');</script>");
    }
}
