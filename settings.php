<?php require_once("includes/header.php"); ?>

<?php

if(isset($_POST['insta_user'])){
    $insta_user = mysqli_real_escape_string($conn, $_POST['insta_user']);
    $insta_password = mysqli_real_escape_string($conn, $_POST['insta_password']);
    $sql = "UPDATE users SET insta_user='$insta_user', insta_password='$insta_password' WHERE id=$user_id";
    mysqli_query($conn, $sql);
}

$sql = "SELECT insta_user, insta_password FROM users WHERE id=$user_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$insta_user = $row['insta_user'];
$insta_password = $row['insta_password'];

?>

<div class="container">
    <form action="" method="post">
        <table>
            <tr>
                <td>Instagram Username</td>
                <td><input type="text" name="insta_user" id="insta_user" class="form-control" value="<?=$insta_user?>"></td>
            </tr>
            <tr>
                <td>Instagram Password</td>
                <td><input type="password" name="insta_password" id="insta_password" class="form-control" value="<?=$insta_password?>"></td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit" style="float: right;" class="btn btn-primary">Save</button></td>
            </tr>
        </table>
    </form>
    <p><?=$msg?></p>
</div>

<?php require_once("includes/footer.php"); ?>