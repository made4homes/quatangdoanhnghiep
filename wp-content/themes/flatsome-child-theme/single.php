<?php
get_header(); ?>


<!-- <div class="row row-small"> -->



<?php 
$post_categories = wp_get_post_categories( get_the_ID() );

foreach($post_categories as $c){
    $cat = get_category( $c );
	if ( $cat->slug == 'collection') {
		echo '<div class="row-full">';
	}else{
		echo '<div class="row row-small" style="padding-left: 20px;
    padding-right: 20px;
    text-align: justify;">';
	} 
}
?>


<div class="large-12 col notpadding" style="background-color: transparent; padding: 0 !important;">
	<div class="breadcrum">	</div>
   <main>
        <?php
        while (have_posts()) :
            the_post();
        ?>
            <article id="post-<?php the_ID(); ?>">
                <header class="entry-header" style="margin-top:25px;">
                    <h1 class="row row-small entry-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="row row-small entry-footer footer-social" style = "margin-bottom: 25px;">
                       <?php echo do_shortcode('[share]'); ?>
                </footer>
            </article>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
           // if (comments_open() || get_comments_number()) :
             //   comments_template();
            //endif;
            ?>

        <?php endwhile; ?>
    </main><!-- #main -->

</div> 

</div>

<?php get_footer(); ?>
