<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 * In the Version 3.5 we will introducing Mage Freamwork, All of our Plugin will use this same Freamwork, This is the Beta test in the Event Plugin.
 */

add_action( 'admin_init', 'mep_re_meta_boxs' );
function mep_re_meta_boxs() {

	/**
	 * This Will create Meta Boxes For Events Custom Post Type.
	 */
	$event_re_meta_boxs = array(
		'page_nav' => __( 'Every Day Event Settings', 'mage-eventpress' ),
		'priority' => 10,
		'sections' => array(
			'section_0' => array(
				'title'       => __( 'Repeated Date & Time Settings', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(

					array(
						'id'      => 'mep_disable_ticket_time',
						'title'   => __( 'Display Time?', 'text-domain' ),
						'details' => __( 'If you want to display time please check this Yes', 'text-domain' ),
						'type'    => 'checkbox',
						'default' => '',
						'args'    => array(
							'yes' => __( 'Yes', 'text-domain' )
						),
					),


					// Meta Boxes Will Here as Array
					array(
						'id'          => 'mep_ticket_times_global',
						'title'       => __( 'Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),


					array(
						'id'          => 'mep_ticket_times_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_ticket_times_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_ticket_times_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_ticket_times_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_ticket_times_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_ticket_times_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_ticket_times_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),


					array(
						'id'       => 'mep_ticket_offdays',
						'title'    => __( 'Ticket Offdays', 'text-domain' ),
						'details'  => __( 'Please select the offday days. Ticket will be not available on the selected days', 'text-domain' ),
						'type'     => 'select2',
						'default'  => '',
						'multiple' => true,
						'args'     => array(
							'sun' => __( 'Sunday', 'text-domain' ),
							'mon' => __( 'Monday', 'text-domain' ),
							'tue' => __( 'Tuesday', 'text-domain' ),
							'wed' => __( 'Wednesday', 'text-domain' ),
							'thu' => __( 'Thursday', 'text-domain' ),
							'fri' => __( 'Friday', 'text-domain' ),
							'sat' => __( 'Saturday', 'text-domain' ),
						),
					),
					array(
						'id'          => 'mep_ticket_off_dates',
						'title'       => __( 'Ticket Off Dates List', 'text-domain' ),
						'details'     => __( 'If you want to off selling ticket on partucular dates please select them', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_off_date',
						'btn_text'    => 'Add New Off Date',
						'fields'      => array(
							array(
								'type'    => 'date',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_off_date',
								'name'    => 'OffDate',
							)
						),
					),
				)
			),
			'section_1' => array(
				'title'       => __( 'Special Date & Time 01:', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 1
					array(
						'id'        => 'mep_rec_sp_start_date',
						'title'        => __('Special Start Date 1','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date',
						'title'        => __('Special End Date 1','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time',
						'title'       => __( 'Times For Special Date Range 1', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_01_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_01_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_01_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_01_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_01_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_01_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_01_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),
			'section_2' => array(
				'title'       => __( 'Special Date & Time 02:', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 2
					array(
						'id'        => 'mep_rec_sp_start_date_2',
						'title'        => __('Special Start Date 2','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date_2',
						'title'        => __('Special End Date 2','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time_2',
						'title'       => __( 'Times For Special Date Range 2', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_02_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_02_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_02_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_02_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_02_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_02_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_02_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),
			'section_3' => array(
				'title'       => __( 'Special Date & Time 03:', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 3
					array(
						'id'        => 'mep_rec_sp_start_date_3',
						'title'        => __('Special Start Date 3','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date_3',
						'title'        => __('Special End Date 3','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time_3',
						'title'       => __( 'Times For Special Date Range 3', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_03_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_03_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_03_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_03_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_03_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_03_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_03_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),
			'section_4' => array(
				'title'       => __( 'Special Date & Time 04:', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 4
					array(
						'id'        => 'mep_rec_sp_start_date_4',
						'title'        => __('Special Start Date 4','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date_4',
						'title'        => __('Special End Date 4','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time_4',
						'title'       => __( 'Times For Special Date Range 4', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_04_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_04_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_04_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_04_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_04_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_04_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_04_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),
			'section_5' => array(
				'title'       => __( 'Special Date & Time 05:', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 5
					array(
						'id'        => 'mep_rec_sp_start_date_5',
						'title'        => __('Special Start Date 5','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date_5',
						'title'        => __('Special End Date 5','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time_5',
						'title'       => __( 'Times For Special Date Range 5', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_05_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_05_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_05_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_05_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_05_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_05_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_05_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),
			'section_6' => array(
				'title'       => __( 'Special Date & Time 06:', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 6
					array(
						'id'        => 'mep_rec_sp_start_date_6',
						'title'        => __('Special Start Date 6','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date_5',
						'title'        => __('Special End Date 6','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time_6',
						'title'       => __( 'Times For Special Date Range 6', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_06_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_06_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_06_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_06_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_06_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_06_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_06_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),
			'section_7' => array(
				'title'       => __( 'Special Date & Time 07:', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 7
					array(
						'id'        => 'mep_rec_sp_start_date_7',
						'title'        => __('Special Start Date 7','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date_7',
						'title'        => __('Special End Date 7','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time_7',
						'title'       => __( 'Times For Special Date Range 7', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_07_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_07_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_07_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_07_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_07_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_07_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_07_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),
			'section_8' => array(
				'title'       => __( 'Special Date & Time 08', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 8
					array(
						'id'        => 'mep_rec_sp_start_date_8',
						'title'        => __('Special Start Date 8','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date_8',
						'title'        => __('Special End Date 8','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time_8',
						'title'       => __( 'Times For Special Date Range 8', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_08_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_08_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_08_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_08_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_08_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_08_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_08_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),
			'section_9' => array(
				'title'       => __( 'Special Date & Time 09:', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 9
					array(
						'id'        => 'mep_rec_sp_start_date_9',
						'title'        => __('Special Start Date 9','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date_9',
						'title'        => __('Special End Date 9','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time_9',
						'title'       => __( 'Times For Special Date Range 9', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_09_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_09_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_09_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_09_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_09_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_09_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_09_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),
			'section_10' => array(
				'title'       => __( 'Special Date & Time 10:', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 10
					array(
						'id'        => 'mep_rec_sp_start_date_10',
						'title'        => __('Special Start Date 10','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date_10',
						'title'        => __('Special End Date 10','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time_10',
						'title'       => __( 'Times For Special Date Range 10', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_10_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_10_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_10_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_10_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_10_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_10_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_10_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),
			'section_11' => array(
				'title'       => __( 'Special Date & Time 11:', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 11
					array(
						'id'        => 'mep_rec_sp_start_date_11',
						'title'        => __('Special Start Date 11','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date_11',
						'title'        => __('Special End Date 11','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time_11',
						'title'       => __( 'Times For Special Date Range 11', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_11_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_11_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_11_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_11_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_11_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_11_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_11_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),
			'section_12' => array(
				'title'       => __( 'Special Date & Time 12:', 'mage-eventpress' ),
				'description' => __( '', 'mage-eventpress' ),
				'options'     => array(
					// sp sec 12
					array(
						'id'        => 'mep_rec_sp_start_date_12',
						'title'        => __('Special Start Date 12','text-domain'),
						'details'      => __('Select Start date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'        => 'mep_rec_sp_end_date_12',
						'title'        => __('Special End Date 12','text-domain'),
						'details'      => __('Select End date for Special Date Range','text-domain'),
						'date_format'  => 'dd-mm-yy',
						'placeholder'  => 'dd-mm-yy',
						'default'    => '',
						'type'        => 'datepicker',
					),
					array(
						'id'          => 'mep_rec_sp_date_time_12',
						'title'       => __( 'Times For Special Date Range 12', 'text-domain' ),
						'details'     => __( 'Please Add Ticket Times for Special Date Range', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					
					array(
						'id'          => 'mep_rec_sp_12_time_sat',
						'title'       => __( 'Saturday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							),
						),
					),
					array(
						'id'          => 'mep_rec_sp_12_time_sun',
						'title'       => __( 'Sunday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_12_time_mon',
						'title'       => __( 'Monday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'args'    => '',
								'default' => 'option_1',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_12_time_tue',
						'title'       => __( 'Tuesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_12_time_wed',
						'title'       => __( 'Wednesday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_12_time_thu',
						'title'       => __( 'Thursday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => 'option_1',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
					array(
						'id'          => 'mep_rec_sp_12_time_fri',
						'title'       => __( 'Friday Ticket Time', 'text-domain' ),
						'details'     => __( 'Please Enter Add Ticket Times', 'text-domain' ),
						'collapsible' => true,
						'type'        => 'repeatable',
						'title_field' => 'mep_ticket_time_name',
						'btn_text'    => 'Add New Time',
						'fields'      => array(
							array(
								'type'    => 'text',
								'args'    => '',
								'default' => '',
								'item_id' => 'mep_ticket_time_name',
								'name'    => 'Time Slot Label',
							),
							array(
								'type'    => 'time',
								'default' => '',
								'args'    => '',
								'item_id' => 'mep_ticket_time',
								'name'    => 'Time',
							)
						),
					),
				)
			),

		),
	);

	$events_re_meta_args = array(
		'meta_box_id'       => 'mep_everyday_event_settings_meta_boxes',
		'meta_box_title'    => __( 'Every Day Event Settings', 'mage-eventpress' ),
		'screen'            => array( 'mep_events' ),
		'context'           => 'advanced',
		'priority'          => 'high',
		'callback_args'     => array(),
		'nav_position'      => 'none',
		'item_name'         => "MagePeople",
		'item_version'      => "2.0",
		'every_day_setting' => true,
		'panels'            => array(
			'events_speaker_list_meta_boxs' => $event_re_meta_boxs
		)
	);


	new AddMetaBox( $events_re_meta_args );


}