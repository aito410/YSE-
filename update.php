<?php
// データベースに接続
require_once "db.php";

// POSTデータを受け取る
$posts = $_POST;
// var_dump($posts);
$price = $posts['price'];

// 合計金額「sales.price」を保存するSQLを作成
$sql = "INSERT INTO sales (price) VALUES ({$price});";
// SQLを実行
$pdo->query($sql);

// レジ画面に戻る（リダイレクト：ページ転送）
header('Location: index.php');