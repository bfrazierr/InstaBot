<?php
require_once(__DIR__ . "/../includes/db_connect.php");
// get all postings that need posted now
$curdt = date("Y-m-d H:i:s", strtotime( "-5 hours"));
$sql = "SELECT a.id, a.img_src, a.caption, a.hashtag_keyword, b.insta_user, b.insta_password FROM postings a INNER JOIN users b ON a.user_id=b.id WHERE a.scheduled_for<'$curdt' AND a.posted<>1";
$result = mysqli_query($conn, $sql) or die('baha');
while($row = mysqli_fetch_assoc($result)){
    $id = $row['id'];
    $img_src = $row['img_src'];
    $caption = $row['caption'];
    $hashtag_keyword = $row['hashtag_keyword'];
    $insta_user = $row['insta_user'];
    $insta_password = $row['insta_password'];
    // die("node /var/www/insta.capisave.com/InstaPoster/post.js '$insta_user' '$insta_password' '/var/www/insta.capisave.com/$img_src' '$caption' '$first_comment'");
    $first_comment = "";
    if($hashtag_keyword != ""){
        $first_comment = exec("node /var/www/insta.capisave.com/InstaPoster/scrape_hashtags.js '$hashtag_keyword'");
    }
    // post 
    exec("node /var/www/insta.capisave.com/InstaPoster/post.js '$insta_user' '$insta_password' '/var/www/insta.capisave.com/$img_src' '$caption' '$first_comment'");
    $sql = "UPDATE postings SET posted=1 WHERE id=$id";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));
}

// clean up any failed processes
exec("pkill -f chrome");
exec("pkill -f node");