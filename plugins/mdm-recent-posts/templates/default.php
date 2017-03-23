<?php
/*
 * Widget Template: Default Template
 */
?>

<?php if ( $wp_query->have_posts() ) : ?>
    <ul class="recent_posts">
        <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
            <li class="recent_post"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php ++$count; ?>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>