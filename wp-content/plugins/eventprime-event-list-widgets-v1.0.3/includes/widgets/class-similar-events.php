<?php
if (!defined( 'ABSPATH')){
	exit;
}

class EventM_Similar_Event_Widget extends WP_Widget 
{

	private $dao;

	public function __construct() {
		$this->dao = new EventM_More_Widget_DAO();
		$widget_options = array (
			'classname' => 'EventM_Similar_Event_Widget',
			'description' => 'Show list of similar events.'
		);
		parent::__construct( 'EventM_Similar_Event_Widget', 'EventPrime Similar Events', $widget_options );
	}

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
  	wp_enqueue_style('em_more_se_widget_css', plugin_dir_url(__DIR__) . 'css/em_more_widget_style.css', false, EVENTPRIME_VERSION);
  	$title = ( !empty( $instance['title'] ) ) ? $instance['title'] : __( 'Similar Events','eventprime-list-widgets' );
  	$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
  	$number = ( !empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
  	if ( !$number ) {
  		$number = 5;
  	}
  	$eventId = isset( $instance['event_id'] ) ? absint( $instance['event_id'] ) : '';
  	if(!empty(event_m_get_param('event'))){
  		$eventId = event_m_get_param('event');
  	}

  	$html = '<div class="widget widget_similar_events"><div class="widget-content">';
  		$html .= '<h2 class="widget-title subheading heading-size-3">'.$title.'</h2>';
	  	if(empty($eventId)){
	  		$html .= '<div id="similarevents" class="ep-mw-wrap">';
	  			$html .= '<div class="ep-similar-events">'. __('Please Select Event For Similar Event From Widget Area','eventprime-list-widgets').'</div>';
	  		$html .= '</div>';
	  	}
	  	else{
	  		$events = $this->dao->get_similar_events($eventId);
	  		if(!empty($events)){
	  			$i = 0;
	  			$event_service = EventM_Factory::get_service('EventM_Service');
	  			$venueService = EventM_Factory::get_service('EventM_Venue_Service');
          $setting_service = EventM_Factory::get_service('EventM_Setting_Service');
          $global_settings= $setting_service->load_model_from_db();
	  			foreach ($events as $event) {
	  				$html .= '<div id="similarevents" class="ep-mw-wrap">';
		  				$eventData = $event_service->load_model_from_db($event['id']);
		  				$venue = $venueService->load_model_from_db($eventData->venue);
		  				$title = $event['title'];
		  				/* $url = $event_url = add_query_arg('event', $event['id'], get_page_link($global_settings->events_page)); */
						$url = $event_url = em_get_single_event_page_url($eventData, $global_settings);
		  				$html .= '<div class="ep-fimage">';
			  				if (!empty($eventData->cover_image_id)):
			  					$html .= '<a href="'.$url.'">'.get_the_post_thumbnail($event['id'], 'thumbnail').'</a>';
			  				else:
			  					$html .= '<a href="'.$url.'"><img src="'.esc_url(EM_BASE_FRONT_IMG_URL.'dummy_image.png').'" alt="'.__('Dummy Image','eventprime-list-widgets').'" ></a>';
			  				endif;
		  				$html .= '</div>';
		  				$html .= '<div class="ep-fdata"><div class="ep-fname"><a href="'.$url.'">'.$title.'</a></div>';
		  				$html .= '<div class="ep-fdate">'.date('M d, Y @ H:i A', $eventData->start_date).'</div>';
		  				if(!empty($venue) && !empty($venue->address)){
		  					$html .= '<div class="ep-faddress">'.$venue->address.'</div>';
		  				}

		  				if(!empty($eventData->description)){
		  					$html .= '<div class="ep-fdesc">'.$eventData->description.'</div>';
		  				}
		  				$html .= '</div>';
	  				$html .= '</div>';
	  				$i++;
	  				if($i == $number) break;
	  			}
	  		}
	  	}
  	$html .= '</div></div>';
  	echo $html;
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
  	$title = !empty( $instance['title'] ) ? $instance['title'] : '';
  	$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
  	$event_id = isset( $instance['event_id'] ) ? absint( $instance['event_id'] ) : '';
  	?>
  	<p>
  		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'eventprime-list-widgets' ); ?></label> 
  		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
  	</p>
  	<p>
  		<label for="<?php echo $this->get_field_id( 'event_id' ); ?>"><?php _e( 'Select Event (For other than event page):','eventprime-list-widgets' ); ?></label>
  		<?php
  		$event_service = EventM_Factory::get_service('EventM_Service');
  		$events = $event_service->get_upcoming_events();
  		?>
  		<select id="<?php echo $this->get_field_id('event_id'); ?>" name="<?php echo $this->get_field_name('event_id'); ?>" class="widefat">
  			<option><?php _e('Select Event','eventprime-list-widgets'); ?></option>
  			<?php
  			if (!empty($events)):
  				foreach ($events as $event):
  					if ($event->parent != 0)
  						continue;
  					?>
  					<option <?php if ($event_id == $event->id) echo 'selected'; ?> value="<?php echo $event->id ?>"><?php echo $event->name; ?></option>    
  					<?php
  				endforeach;
  			endif;?>
  		</select>
  	</p>
  	<p>
  		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of events to show:', 'eventprime-list-widgets' ); ?></label>
  		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
  	</p>
  	<?php 
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
  	$instance = array();
  	$instance['title'] = ( !empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
  	$instance['number'] = (int) $new_instance['number'];
  	$instance['event_id'] = (int) $new_instance['event_id'];

  	return $instance;
  }
}