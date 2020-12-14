<?php require_once("includes/header.php"); ?>

<?php

$id = $_GET['id'];
if($id == "" || !isset($id)){
    header("Location: /");
}

if(isset($_POST['post_name'])){
    // upload
    $post_name = mysqli_real_escape_string($conn, $_POST['post_name']);
    $post_caption = mysqli_real_escape_string($conn, $_POST['caption']);
    $post_scheduled = date("Y-m-d H:i:s", strtotime($_POST['post_date_time'], "-5 hours"));
    $post_hashtag_keyword = mysqli_real_escape_string($conn, $_POST['hashtag_keyword']);
    $post_img_src = "";
    // upload file
    // if(isset($_FILES['image'])){
    //     $errors= array();
    //     $file_name = $_FILES['image']['name'];
    //     $file_size =$_FILES['image']['size'];
    //     $file_tmp =$_FILES['image']['tmp_name'];
    //     $file_type=$_FILES['image']['type'];
    //     $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
        
    //     $extensions= array("jpeg","jpg");
        
    //     if(in_array($file_ext,$extensions)=== false){
    //        $errors[]="extension not allowed, please choose a JPEG or JPG file.";
    //     }
        
    //     // if($file_size > 2097152){
    //     //    $errors[]='File size must be less than 10 MB';
    //     // }
        
    //     if(empty($errors)==true){
    //        $post_img_src = "images/".time().".".$file_ext;
    //        move_uploaded_file($file_tmp, $post_img_src);
    //     }else{
    //        print_r($errors);
    //     }
    //  }
    //  $sql = "INSERT INTO postings (user_id, name, caption, scheduled_for, img_src, created, posted) VALUES ($user_id, '$post_name', '$post_caption', '$post_scheduled', '$post_img_src', NOW(), 0)";
    $sql = "UPDATE postings SET name='$post_name', caption='$post_caption', hashtag_keyword='$post_hashtag_keyword', scheduled_for='$post_scheduled' WHERE user_id=$user_id AND id=$id";
    mysqli_query($conn, $sql) OR die(mysqli_error($conn));
     header("Location: /");
}else{
    // get post info
    $sql = "SELECT name, caption, img_src, scheduled_for, hashtag_keyword FROM postings WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0){
        header("Location: /");
    }
    $row = mysqli_fetch_assoc($result);
    $post_name = $row['name'];
    $post_img_src = $row['img_src'];
    $post_caption = $row['caption'];
    $post_scheduled = date("m/d/Y g:i A", strtotime($row['scheduled_for']));
    $post_hashtag_keyword = $row['hashtag_keyword'];
}

?>

<div class="container">
<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group row">
    <label for="post_name" class="col-4 col-form-label">Post Name</label> 
    <div class="col-8">
      <input id="post_name" name="post_name" placeholder="Post Name (For internal use only)" type="text" class="form-control" value="<?=$post_name?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="image" class="col-4 col-form-label">Image</label> 
    <div class="col-8">
        <a href="<?=$post_img_src?>"><?=$post_img_src?></a>
    </div>
  </div>
  <div class="form-group row">
    <label for="caption" class="col-4 col-form-label">Caption</label> 
    <div class="col-8">
      <textarea id="caption" name="caption" cols="40" rows="5" required="required" class="form-control"><?=$post_caption?></textarea>
    </div>
  </div>
  <div class="form-group row">
    <label for="hashtag_keyword" class="col-4 col-form-label">Hashtag Keyword</label> 
    <div class="col-8">
      <input id="hashtag_keyword" name="hashtag_keyword" placeholder="Hashtag Keyword (To generate hashtags on post)" type="text" class="form-control" value="<?=$post_hashtag_keyword?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="post_date_time" class="col-4 col-form-label">Post Date/Time</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-calendar-check-o"></i>
          </div>
        </div> 
        <input id="post_date_time" name="post_date_time" placeholder="Click to set Post Date/Time" type="text" class="form-control" value="<?=$post_scheduled?>">
      </div>
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button id="submit-btn" name="submit" type="submit" class="btn btn-primary">Save</button>
    </div>
  </div>
</form>
</div>

<?php require_once("includes/footer.php"); ?>

<script>
    jQuery(document).ready(function($) {
    if (window.jQuery().datetimepicker) {
        $('#post_date_time').datetimepicker({
            // Formats
            // follow MomentJS docs: https://momentjs.com/docs/#/displaying/format/
            format: 'MM/DD/YYYY hh:mm A',
            
            // Your Icons
            // as Bootstrap 4 is not using Glyphicons anymore
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-check',
                clear: 'fa fa-trash',
                close: 'fa fa-times'
            }
        });
    }
});

</script>