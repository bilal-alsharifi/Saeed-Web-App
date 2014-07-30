<?php

header("Content-Type: application/rss+xml; charset=UTF-8");

include_once ('../config/config.php');
$db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
$db->exec("set names utf8");
$query = "SELECT * FROM news ORDER BY date DESC;";
$sth = $db->prepare($query);
$sth->execute();
$news = $sth->fetchAll();


$rssfeed = '<?xml version="1.0" encoding="UTF-8"?>';
$rssfeed .= '<rss version="2.0">';
$rssfeed .= '<channel>';
$rssfeed .= '<title>ساعد للتأمين | الأخبار</title>';
$rssfeed .= '<link>' . BASE_URL . '</link>';
$rssfeed .= '<description>شركة ساعد للتأمين</description>';
$rssfeed .= '<language>ar</language>';
$rssfeed .= '<copyright>Copyright © 2012-2013 Saeed insurance company. All Rights Reserved</copyright>';
foreach ($news as $n) {
    $rssfeed .= '<item>';
    $rssfeed .= '<title>' . $n['title'] . '</title>';
    $rssfeed .= '<description>' . $n['description'] . '</description>';
    $rssfeed .= '<link>' . $n['link'] . '</link>';
    $rssfeed .= '<pubDate>' . $n['date'] . '</pubDate>';
    $rssfeed .= '</item>';
}

$rssfeed .= '</channel>';
$rssfeed .= '</rss>';

echo $rssfeed;


?>