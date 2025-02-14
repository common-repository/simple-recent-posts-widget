<?php
/*
Plugin Name: Simple Recent Posts Widget
Plugin URI: https://wordpress.org/plugins/simple-recent-posts-widget/
Description: Provides a simple recent posts widget, including thumbnails, category, and number options.
Version: 1.0
Author: Anshul Labs
Author URI: http://anshullabs.xyz/
*/

/* Simple Recent Posts Widget Class */
class al_simple_recent_posts extends WP_Widget {

	/** constructor */
	function al_simple_recent_posts() {
		parent::__construct( false, $name = __( 'Simple Recent Posts', 'simple-recent-posts' ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {

		extract( $args );
		global $posttypes;
		$title          = apply_filters( 'widget_title', $instance['title'] );
		$cat            = apply_filters( 'widget_title', $instance['cat'] );
		$number         = apply_filters( 'widget_title', $instance['number'] );
		$offset         = apply_filters( 'widget_title', $instance['offset'] );
		$thumbnail_size = apply_filters( 'widget_title', $instance['thumbnail_size'] );
		$thumbnail      = $instance['thumbnail'];
		$dateshow       = $instance['dateshow'];
		$posttype       = $instance['posttype'];
		?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) {
			echo $before_title . $title . $after_title;
		} ?>
		<ul class="no-bullets">
			<?php
			global $post;
			$tmp_post = $post;
			
			// get the category IDs and place them in an array
			$args    = array(
				'posts_per_page' => $number,
				'offset'         => $offset,
				'post_type'      => $posttype,
				'cat'            => $cat
			);

			$all_posts = get_posts( $args );
			if (is_array($all_posts)) :
				foreach ( $all_posts as $post ) : setup_postdata( $post ); ?>
					<li <?php if ( ! empty( $thumbnail_size ) ) {
						$size = $thumbnail_size + 8;
						echo 'style="height: ' . $size . 'px;"';
					} ?> >
						<?php if ( $thumbnail == true ) { ?>
							<a href="<?php the_permalink(); ?>" style="float: left; margin: 0 5px 0 0;">
								<?php the_post_thumbnail( array( $thumbnail_size ) ); ?>
							</a>
						<?php } ?>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br/>
						
						<?php if ( $dateshow == true ) { ?>
						<span class="time"><?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) .' ago'; ?></span>
						<?php } ?>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php $post = $tmp_post; ?>
		</ul>
		<?php echo $after_widget; ?>
		<?php
	}

	// Update Widget Data. 
	function update( $new_instance, $old_instance ) {
		global $posttypes;
		$instance                   = $old_instance;
		$instance['title']          = strip_tags( $new_instance['title'] );
		$instance['cat']            = strip_tags( $new_instance['cat'] );
		$instance['number']         = strip_tags( $new_instance['number'] );
		$instance['offset']         = strip_tags( $new_instance['offset'] );
		$instance['thumbnail_size'] = strip_tags( $new_instance['thumbnail_size'] );
		$instance['thumbnail']      = $new_instance['thumbnail'];
		$instance['dateshow']       = $new_instance['dateshow'];
		$instance['posttype']       = $new_instance['posttype'];
		return $instance;
	}

	// SHow widget Form in widget page.
	function form( $instance ) {

		$posttypes      = get_post_types( '', 'objects' );
		$title          = esc_attr( $instance['title'] );
		$cat            = esc_attr( $instance['cat'] );
		$number         = esc_attr( $instance['number'] );
		$offset         = esc_attr( $instance['offset'] );
		$thumbnail_size = esc_attr( $instance['thumbnail_size'] );
		$thumbnail      = esc_attr( $instance['thumbnail'] );
		$dateshow       = esc_attr( $instance['dateshow'] );
		$posttype       = esc_attr( $instance['posttype'] );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'cat' ); ?>"><?php _e( 'Category IDs, separated by commas' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'cat' ); ?>" name="<?php echo $this->get_field_name( 'cat' ); ?>" type="text" value="<?php echo $cat; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number to Show:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>"/>
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Offset (the number of posts to skip):' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'offset' ); ?>" name="<?php echo $this->get_field_name( 'offset' ); ?>" type="text" value="<?php echo $offset; ?>"/>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'dateshow' ); ?>" name="<?php echo $this->get_field_name( 'dateshow' ); ?>" type="checkbox" value="1" <?php checked( '1', $dateshow ); ?> />
			<label for="<?php echo $this->get_field_id( 'dateshow' ); ?>"><?php _e( 'Display Date ?' ); ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail' ); ?>" type="checkbox" value="1" <?php checked( '1', $thumbnail ); ?>/>
			<label for="<?php echo $this->get_field_id( 'thumbnail' ); ?>"><?php _e( 'Display thumbnails?' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>">
				<?php _e( 'Size of the thumbnails, e.g. <em>80</em> = 80px x 80px' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'thumbnail_size' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail_size' ); ?>" type="text" value="<?php echo $thumbnail_size; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posttype' ); ?>"><?php _e( 'Choose the Post Type to display' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'posttype' ); ?>" id="<?php echo $this->get_field_id( 'posttype' ); ?>" class="widefat">
				<?php
				foreach ( $posttypes as $option ) {
					echo '<option value="' . $option->name . '" id="' . $option->name . '"', $posttype == $option->name ? ' selected="selected"' : '', '>', $option->name, '</option>';
				}
				?>
			</select>
		</p>
		<?php
	}

	function get_feature_promo( $desc, $url, $upgrade = "UPGRADE" ) {
		$feature_desc = sanitize_text_field( htmlspecialchars( $desc ) );
		$promo  = '<br>';
		$promo .= '<span style="background-color:DarkGoldenRod; color:white;font-style:normal;text-weight:bold">';
		$promo .= '&nbsp;' . $upgrade . ':&nbsp;';
		$promo .= '</span>';
		$promo .= '<span style="color:DarkGoldenRod;font-style:normal;">';
		$promo .= '&nbsp;' . $feature_desc . ' ';
		$promo .= '<A target="_blank" HREF="' . $url . '">Learn more.</A>';
		$promo .= '</span>';
		return $promo;
	}
} // class al_simple_recent_posts
// register Simple Recent Posts widget
add_action( 'widgets_init', create_function( '', 'return register_widget("al_simple_recent_posts");' ) );

?>