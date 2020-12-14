<?php
require_once(__DIR__ . "/../includes/db_connect.php");

// grab the posts data
$raw_data = exec("node " . __DIR__  . "/../InstaPoster/dankmemes.js");
$data = json_decode($raw_data, true);

// create post times
$img_count = count($data);
$post_segragator = 24 / $img_count;

// loop through and store data
for($i=0; $i<count($data); $i++){
    $caption = mysqli_real_escape_string($conn, $data[$i]['caption']);
    $img_url = $data[$i]['img_url'];
    $keywords = $data[$i]['keywords'];
    $hashtag_keyword = isset($keywords[0]) ? mysqli_real_escape_string($conn, $keywords[0]) : "";

    // download and store image
    $img_local_url = "images/" . time() . "_$i" . ".jpg";
    $img_location = "/var/www/insta.capisave.com/$img_local_url";
    copy($img_url, $img_location);

    // post time
    $rand_minute = sprintf("%02d", floor(rand(0, 59)));
    $scheduled_dt = date("Y-m-d") . " " . (sprintf("%02d", floor($post_segragator * $i))) . ":" . $rand_minute . ":00";

    // schedule post
    $sql = "INSERT INTO postings (user_id, name, img_src, caption, hashtag_keyword, scheduled_for, created, posted) VALUES
                              (1, 'Dank Memes Automation', '$img_local_url', '$caption', '$hashtag_keyword', '$scheduled_dt', NOW(), 0)";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));
}