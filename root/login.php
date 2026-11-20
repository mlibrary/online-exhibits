<?php
if (strpos($_SERVER['REQUEST_URI'], '/login') === 0) {
  mlibrary_login();
}

function mlibrary_login() {
  if (strpos($_SERVER['QUERY_STRING'], 'dest=/online-exhibits/') === 0) {
    header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . mb_substr($_SERVER['QUERY_STRING'], 5, mb_strlen($_SERVER['QUERY_STRING'])));
    exit();
  }
  else {
    header("Location: {$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/");
    exit();
  }
}

