<?php  get_header();
//echo get_template_directory_uri();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <h1>Books</h1>
        <div id="books-list"></div>
    </main>
</div>
<input name="wp_rest" id="wp_rest" value="<?php echo wp_create_nonce( 'wp_rest' ) ?>" style="display: none; visibility: hidden">



<?php get_footer(); ?>