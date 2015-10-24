<?php
$date = new \DateTime();
$date->setTimestamp(strtotime($post->date));
?>

<div class="row">
    <h1><?php echo $post->title ?></h1>
    <p class="small"><?php echo $date->format('F j, Y H:i:s') ?></p>
    <?php echo htmlspecialchars_decode($post->content) ?>
     <?php if (!is_null($user)) { ?>
        <?php echo '<hr>'?>
       <?php echo '<p align="right"><a class="btn btn-default btn btn-default btn-xs" href="' . $getRoute('edit_post',array('id'=>$post->id)) . '" role="button">Edit</a>'?>
       <?php echo '<a class="btn btn-default btn btn-default btn-xs" href="' . $getRoute('remove_post', array('id'=>$post->id)) . '" role="button">Delete</a></p>'?>
        <?php echo '<hr>';?>
    <?php } ?>
    </p>
</div>
