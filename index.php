<?php require_once("includes/header.php"); ?>

<?php

$sql = "SELECT id, name, caption, hashtag_keyword, img_src, scheduled_for, posted FROM postings WHERE user_id=$user_id ORDER BY scheduled_for DESC";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$row_count = mysqli_num_rows($result);
?>

<div class="container">
    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th>ID</th>
            <th>Posted</th>
            <th>Name</th>
            <th>Image URL</th>
            <th>Caption</th>
            <th>Hashtag Keyword</th>
            <th>Scheduled Date/Time</th>
            <th>Action</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)){ ?>
        <tr>
            <td><?=$row['id']?></td>
            <td><?=$row['posted'] == 1 ? "<span style='font-weight: bold;color:green'>Yes</span>" : "Pending"?></td>
            <td><?=$row['name']?></td>
            <td><a href="/<?=$row['img_src']?>"><?=$row['img_src']?></a></td>
            <td><?=$row['caption']?></td>
            <td><?=$row['hashtag_keyword']?></td>
            <td><?=date("m/d/Y g:i A", strtotime($row['scheduled_for']))?></td>
            <td>
                <?php if($row['posted'] != 1){?>
                    <div class="action-btns">
                        <a href="/edit-posting.php?id=<?=$row['id']?>" class="btn btn-primary edit-btn">Edit</a>
                        <button class="btn btn-danger cancel-btn" data-id="<?=$row['id']?>">Cancel</button>
                    </div>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php if($row_count == 0) {?>
    <center>No posts are currently scheduled.</center>
    <?php } ?>
</div>

<?php require_once("includes/footer.php"); ?>

<script>
    $(".cancel-btn").click(function(elem){
        let id = $(this).data("id");
        if(confirm("Are you sure you want to cancel post " + id + "?")){
            $.ajax({
                type: "POST",
                url: "functions.php",
                data: {
                    action: "cancel_posting",
                    posting_id: id
                },
                success: function(){
                    alert("Posting canceled successfully.");
                    location.reload();
                }
            })
        }
    })
</script>