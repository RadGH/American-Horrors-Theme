<?php
class WP_Widget_Recent_VODs extends WP_Widget {
	
	/**
	 * Sets up a new Recent VODs widget instance.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_recent_entries',
			'description' => __( 'Most recent VODs.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'recent-videos', __( 'Recent VODs' ), $widget_ops );
		$this->alt_option_name = 'widget_recent_entries';
	}
	
	/**
	 * Outputs the content for the current Recent VODs widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent VODs widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}
		
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent VODs' );
		
		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		
		/**
		 * Filters the arguments for the Recent VODs widget.
		 *
		 * @param array $args An array of arguments used to retrieve the recent videos.
		 */
		$r = new WP_Query( apply_filters( 'widget_videos_args', array(
			'post_type'            => 'vod',
			'posts_per_page'       => $number,
			'no_found_rows'        => true,
			'post_status'          => 'publish',
			'ignore_sticky_videos' => true
		) ) );
		
		if ($r->have_posts()) :
			?>
			<?php echo $args['before_widget']; ?>
			<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
			<ul>
				<?php while ( $r->have_posts() ) : $r->the_post(); ?>
					<li class="vod-item">
						<?php echo theme_get_vod_thumbnail( get_the_ID(), true ); ?>
						<a href="<?php the_permalink(); ?>">
							<?php get_the_title() ? the_title() : the_ID(); ?>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php echo $args['after_widget']; ?>
			<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
		
		endif;
	}
	
	/**
	 * Handles updating the settings for the current Recent VODs widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		return $instance;
	}
	
	/**
	 * Outputs the settings form for the Recent VODs widget.
	 *
	 * @param array $instance Current settings.
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of videos to show:' ); ?></label>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>
		<?php
	}
}

function theme_register_recent_vods_widget() {
	register_widget( 'WP_Widget_Recent_VODs' );
}
add_action( 'widgets_init', 'theme_register_recent_vods_widget' );