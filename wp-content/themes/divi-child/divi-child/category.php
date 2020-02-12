<?php

/**
 * The template for displaying category archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header();

?>

<!-- 
// get the current taxonomy term
$term = get_queried_object();


// vars
$image = get_field('image', $term);
$color = get_field('color', $term); -->


<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <header class="entry-header">
            <?php ?>
        </header>
        <div class="entry-content">
            <?php
            //get the current taxonomy is here
            $term = get_queried_object();
            $image = get_field('category_image', $term);
            echo '<pre>';
            print_r($image);
            echo '</pre>';
            ?>
        </div>
    </main>
</div>
</div>

<?php get_footer(); ?>