<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Humescores
 */

get_header();
?>

<div id="content" class="site-content">

<?php if ( have_posts() ) : ?>

	<header class="page-header">
		<h1 class="page-title">
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Search Results for: %s', 'humescores' ), '<span>' . get_search_query() . '</span>' );
			?>
		</h1>
	</header><!-- .page-header -->
<?php 
else :

	get_template_part( 'template-parts/content', 'none' );
	return;

endif;
?>
	<main id="primary" class="site-main">


			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				// get_template_part( 'template-parts/content', 'search' );
				get_template_part( 'template-parts/content' );

			endwhile;

			the_posts_pagination( array(
				'prev_text' => __( 'Newer', 'humescores' ),
				'next_text' => __( 'Older', 'humescores' ),
				'before_page_number' => '<span class="screen-reader-text">' . __( 'Page', 'humescores' ) . '</span>',
			) );

		?>

	</main><!-- #main -->

<?php
get_sidebar();
?>

</div><!-- #content -->

<?php
get_footer();
