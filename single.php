<?php
$get_image_link = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');
//print_r( $get_image_link ) ;
?>

<h1><?php the_title(); ?>;</h1>
<img src="<?php echo $get_image_link[0]?>" width="600">
<p> <?php echo get_the_date();?></p>
<p> <?php the_content();?></p>
<div class="commentsHolder">
    <?php comment_form();?>
    <?php comments_template();?>
</div>




