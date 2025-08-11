<?php

$db = parse_ini_file("../db.ini");

$base_url = "https://{$_SERVER['HTTP_HOST']}"
 . dirname(dirname($_SERVER["REQUEST_URI"]))
 . "/exhibits/show/";
$dsn = "mysql:host={$db["host"]};dbname={$db["dbname"]};charset={$db["charset"]}";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $db["username"], $db["password"], $options);
if (!preg_match("/^[a-zA-Z0-9_]*$/", $db["prefix"])) {
  exit(0);
}

$exhibits = $pdo->prepare("
  SELECT id, title, slug, description, modified
  FROM {$db["prefix"]}exhibits exhibits
  WHERE public = 1
");

$tags = $pdo->prepare("
  SELECT name
  FROM {$db["prefix"]}tags tags JOIN
       {$db["prefix"]}records_tags records_tags ON tags.id = records_tags.tag_id
  WHERE records_tags.record_id = :record_id
");

$results = [];
$exhibits->execute();
foreach ($exhibits as $exhibit) {
  $result = [
    "url" => $base_url . $exhibit["slug"],
    "title" => $exhibit["title"],
    "description" => $exhibit["description"],
    "modified" => $exhibit["modified"],
  ];
  $result["tags"] = [];
  $tags->execute([":record_id" => $exhibit["id"]]);
  foreach ($tags as $tag) {
    $result["tags"][] = $tag["name"];
  }
  $results[] = $result;
}

header("Content-Type: application/json");
echo json_encode($results);
