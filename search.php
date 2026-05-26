<?php
/**
 * Search results template.
 *
 * @package Street_Techspot_Ventures
 */

get_header();
?>

<main id="primary" class="min-h-screen bg-white py-12 text-slate-950">
	<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
		<h1 class="text-3xl font-black">
			<?php
			printf(
				/* translators: %s: Search query. */
				esc_html__( 'Search results for "%s"', 'street-techspot-ventures' ),
				esc_html( get_search_query() )
			);
			?>
		</h1>

		<div class="mt-8 space-y-8">
			<?php if ( have_posts() ) : ?>
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'border-b border-slate-200 pb-8' ); ?>>
						<h2 class="text-2xl font-black">
							<a class="hover:text-cyan-700" href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h2>
						<div class="mt-4 text-slate-700">
							<?php the_excerpt(); ?>
						</div>
					</article>
					<?php
				endwhile;
				the_posts_navigation();
				?>
			<?php else : ?>
				<p class="rounded border border-slate-200 bg-slate-50 p-6 text-slate-700">
					<?php echo esc_html__( 'No results matched your search.', 'street-techspot-ventures' ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php
get_footer();
