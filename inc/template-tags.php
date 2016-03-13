<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Journal
 */

if ( ! function_exists( 'journal_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function journal_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'journal' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'journal' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'journal_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function journal_entry_footer() { ?>
	<?php if ( 'post' === get_post_type() ) : ?>

		<div class="post-categories">
			<?php $categories = get_the_category(); ?>
			<?php if ( ! empty( $categories ) ) : ?>
				<?php foreach( $categories as $category ) : ?>
					<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" alt="<?php echo esc_attr( sprintf( esc_attr__( 'View all posts in %s', 'journal' ), $category->name ) ); ?>">
						<?php echo esc_html( $category->name ); ?>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<div class="post-tags">
			<?php $tags = get_the_tags(); ?>
			<?php if ( ! empty( $tags ) ) : ?>
				<?php foreach( $tags as $tag ) : ?>
					<a href="<?php echo esc_url( get_category_link( $tag->term_id ) ); ?>" alt="<?php echo esc_attr( sprintf( esc_attr__( 'View all posts in %s', 'journal' ), $tag->name ) ); ?>">
						<?php echo esc_html( $tag->name ); ?>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

	<?php endif; ?>

	<?php if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
		<span class="comments-link">
			<?php comments_popup_link( esc_html__( 'Leave a comment', 'journal' ), esc_html__( '1 Comment', 'journal' ), esc_html__( '% Comments', 'journal' ) ); ?>
		</span>
	<?php endif;

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'journal' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function journal_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'journal_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'journal_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so journal_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so journal_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in journal_categorized_blog.
 */
function journal_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'journal_categories' );
}
add_action( 'edit_category', 'journal_category_transient_flusher' );
add_action( 'save_post',     'journal_category_transient_flusher' );
