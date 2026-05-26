<?php
/**
 * Page template.
 *
 * @package Street_Techspot_Ventures
 */

get_header();
?>

<main id="primary" class="stv-page-main">
	<div class="stv-page-shell">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="stv-page-title"><?php the_title(); ?></h1>
				<div class="stv-page-content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
