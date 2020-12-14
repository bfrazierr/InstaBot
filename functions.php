<?php
require_once("includes/db_connect.php");
session_start();
if(!isset($_SESSION['user_id'])){
    die();
}
$user_id = $_SESSION['user_id'];


$action = $_POST['action'];

switch($action){
    case 'cancel_posting':
        $posting_id = $_POST['posting_id'];
        $sql = "DELETE FROM postings WHERE user_id=$user_id AND id=$posting_id";
        mysqli_query($conn, $sql);
    break;
}