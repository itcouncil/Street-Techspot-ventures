<?php
/**
 * Page template.
 *
 * @package Street_Techspot_Ventures
 */

get_header();
?>

<main id="primary" class="min-h-screen bg-white py-12 text-slate-950">
	<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="text-4xl font-black"><?php the_title(); ?></h1>
				<div class="prose prose-slate mt-8 max-w-none">
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
