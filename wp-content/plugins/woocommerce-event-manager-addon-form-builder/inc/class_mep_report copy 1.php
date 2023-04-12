<?php
if (!defined('ABSPATH')) {
    die;
}


if (!class_exists('WbbmReport')) {
    class WbbmReport
    {
        protected $text_domain = '';
        protected $post_type = '';
        protected $post_slug = '';
        public $page_for = '';
        public $this_page = '';
        public $currency = '';

        public function __construct($post_type, $post_slug, $text_domain)
        {
            session_start();
            $this->post_type = $post_type;
            $this->post_slug = $post_slug;
            $this->text_domain = $text_domain;
            $this->currency = '$';
            $this->this_page = get_admin_url() . 'edit.php?post_type=' . $this->post_type . '&page=' . $this->post_slug;

            add_action('admin_menu', array($this, 'wbbm_report_page'));

            $this->page_for = isset($_GET['page_for']) ? $_GET['page_for'] : 'sales';

            // Shortcode
            add_shortcode( 'order-report-pdf', array($this, 'mep_order_report_pdf_callback') );

            // Ajax Handler
            add_action('wp_ajax_wbbm_get_bus_details', array($this, 'wbbm_get_bus_details'));
            add_action('wp_ajax_nopriv_wbbm_get_bus_details', array($this, 'wbbm_get_bus_details'));

            // Tab assign
            add_action('wp_ajax_wbtm_tab_assign', array($this, 'wbtm_tab_assign_callback'));
            add_action('wp_ajax_nopriv_wbtm_tab_assign', array($this, 'wbtm_tab_assign_callback'));

            // Order wise details
            add_action('wp_ajax_wbbm_get_order_details', array($this, 'wbbm_get_order_details_callback'));
            add_action('wp_ajax_nopriv_wbbm_get_order_details', array($this, 'wbbm_get_order_details_callback'));

            // PDF
            add_action('wp_ajax_mep_report_generate_pdf', array($this,'mep_report_generate_pdf_callback'));
            add_action('wp_ajax_nopriv_mep_report_generate_pdf', array($this,'mep_report_generate_pdf_callback'));

        }

        public function wbbm_report_page()
        {
            add_submenu_page('edit.php?post_type=' . $this->post_type, $this->lang('Reports'), $this->lang('Reports'), 'manage_options', $this->post_slug, array($this, 'wbbm_reports_entry_point'));
        }

        /*
         * Page Entry Point
        */
        public function wbbm_reports_entry_point()
        {

            // if (isset($_GET['wbbm_csv_export'])) {
            //     $this->wbbm_export_csv_callback();
            // }
            // if (isset($_GET['wbbm_detail_export_csv'])) {
            //     $bus_id = $_GET['detail_bus_id'];
            //     $this->wbbm_detail_export_csv($bus_id);
            // }

            // unset($_SESSION['filter_where']);

            if (isset($_GET['wbbm_order_wise_csv_export'])) {
                $this->wbbm_order_wise_export_csv_callback();
            }

            $this->start_div('wbbm_page_wrapper'); // Main Wrap Start

            // Tab
            // $this->section_tab();
            // Tab END

            // Content
            $this->section_content();
            // Content END

            $this->end_div(); // Main Wrap End
        }

        /*
         * Page Heading
        */
        public function section_heading($heading)
        {
            echo '<h1>' . strtoupper($this->lang($heading)) . '</h1>';
        }

        public function current_tab()
        {
            return isset($_SESSION['current_tab']) ? $_SESSION['current_tab'] : 'three';
        }

        public function wbtm_tab_assign_callback()
        {
            unset($_SESSION['filter_where']);
            $tab = $_POST['tab_no'];
            if ($tab) {
                $_SESSION['current_tab'] = $tab;
            } else {
                $_SESSION['current_tab'] = 'three';
            }
        }

        public function section_tab()
        {
            ?>

            <div class="wbtm-page-top">
                <div class="wbtm-page-top-inner">
                    <h3>
                        <?php _e('Select Report', 'addon-bus--ticket-booking-with-seat-pro'); ?>
                    </h3>
                    <ul class="wbtm_tab_link_wrap">
                        <!-- <li class="clickme">
                            <button data-tag="sells_details_report" data-tab-no="one"
                                    class="wbtm_tab_link wbtm_btn_primary <?php //echo $this->current_tab() == 'one' ? 'wbtm_tab_active' : ''
                        ?>"><?php //_e('Sells details Report', 'addon-bus--ticket-booking-with-seat-pro')
                        ?></button>
                        </li> -->
                        <li class="clickme">
                            <button data-tag="order_wise_details_report" data-tab-no="three"
                                    class="wbtm_tab_link wbtm_btn_primary  <?php echo $this->current_tab() == 'three' ? 'wbtm_tab_active' : '' ?>"><?php _e('Order wise details Report', 'addon-bus--ticket-booking-with-seat-pro') ?></button>
                        </li>
                    </ul>
                </div>
            </div>

            <?php
        }

        // Get Filter Field
        public function get_filter_field($from_date, $to_date)
        {
            switch ($this->page_for) {
                default :
                    $filter_fields = $this->filter_sales_fields($from_date, $to_date);
            }

            return $filter_fields;
        }

        // Get Content Data
        public function get_content_data(): array
        {
            switch ($this->page_for) {
                default :
                    $content_data = $this->content_sales();
            }

            return $content_data;
        }

        /*
         * Section Filter
        */
        public function section_filter($from_date, $to_date)
        {
            $this->start_div('wbbm_page_filter_wrapper'); // Wrapper Start
            $this->start_div('wbbm_page_filter_inner'); // Inner Start
            $this->start_div('wbbm_page_filter_top'); // Top Start

            $filter_active = '';
            if (isset($_GET['filter_by'])) {
                $filter_active = $_GET['filter_by'];
            }

            echo '<a href="' . $this->this_page . '" class="wbbm_btn_new reset-filter">' . $this->lang('Reset all filter') . '</a>';
            $this->end_div(); // Top End

            $this->start_div('wbbm_page_filter_bottom'); // Bottom Start
            ?>
            <label class="sec-label"><?php echo $this->lang('Filter By') ?></label>
            <form action="<?php echo get_admin_url(); ?>edit.php?post_type=wbbm_bus&page=wbbm-reports" method="GET">
                <input type="hidden" name="post_type" value="<?php echo $this->post_type ?>">
                <input type="hidden" name="page" value="<?php echo $this->post_slug ?>">
                <div class="wbbm-form-inner">

                    <?php $this->get_filter_field($from_date, $to_date); ?>

                    <input type="submit" class="wbbm_btn_new" name="submit"
                           value="<?php echo $this->lang('Filter'); ?>">
                </div>
            </form>
            <?php
            $this->end_div(); // Bottom End
            $this->end_div(); // Inner End
            $this->end_div(); // Content End
        }

        /*
         * Section Content
        */
        public function section_content()
        {
            ?>

            <div style="clear: both;"></div>
            <div id="container">
                <!-- <div class="wbtm_content_item <?php //echo $this->current_tab() == 'one' ? 'active' : 'hide' ?>"
                     id="sells_details_report">
                    <?php //$this->content_tab_one(); ?>
                </div> -->

                <div class="wbtm_content_item <?php echo $this->current_tab() == 'three' ? 'active' : 'hide' ?>"
                     id="order_wise_details_report">
                    <?php $this->content_tab_three(); ?>
                </div>
            </div>

            <?php
        }

        protected function content_tab_one()
        {
            // Heading
            $this->section_heading('Sells Details Report');
            // Heading END

            // Filter
            $this->section_filter('one_from_date', 'one_to_date');
            // Filter END

            $this->start_div('wbbm_page_content_wrapper'); // Filter Start
            $get_content_data = $this->get_content_data();
            $head = $get_content_data['head'];
            $body = $get_content_data['body'];

            if (isset($_SESSION['filter_text'])) : ?>
                <div style="text-align:right">
                    <form action="" method="GET">
                        <!-- <button id="wbbm_export_csv" class="wbbm_btn_new">Export CSV</button> -->
                        <input type="hidden" name="post_type" value="<?php echo $this->post_type ?>">
                        <input type="hidden" name="page" value="<?php echo $this->post_slug ?>">
                        <input style="background: #47a96e;font-weight: 700;" class="wbbm_btn_new" type="submit"
                               name="wbbm_csv_export"
                               value="Export CSV">
                    </form>
                </div>
            <?php endif;
            ?>

            <div id="wbbm_report_table_main">
                <?php echo isset($_SESSION['filter_text']) ? '<p class="wbbm-report-heading">' . $_SESSION['filter_text'] . '</p>' : ''; ?>
                <table class="wbbm-main-table">
                    <thead>
                    <tr>
                        <?php
                        switch ($this->page_for) {
                            default :
                                foreach ($head as $th) {
                                    echo '<th>' . $th . '</th>';
                                }
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($head) && !empty($body)) {
                        switch ($this->page_for) {
                            default :
                                echo $body;
                                break;
                        }
                    } else {
                        printf('<p class="wbbm_no_data_found">%s</p>', (isset($_GET['submit']) ? $this->lang("No data found!") : ""));
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <?php
            $this->end_div(); // Content End
        }

        protected function content_tab_three()
        {
            // Heading
            $this->section_heading('Order wise Details Report');
            // Heading END

            // Filter
            $this->section_filter('three_from_date', 'three_to_date');
            // Filter END

            $this->start_div('wbbm_page_content_wrapper'); // Filter Start

            // Query
            $filter_where = [];

            // if ($_GET['ticket_no']) {
            //     $filter_where[] = array(
            //         'key' => 'ea_ticket_no',
            //         'compare' => '=',
            //         'value' => $_GET['ticket_no']
            //     );
            // }

            // if ($_GET['order_no']) {
            //     if(!empty($filter_where)) {
            //         array_push($filter_where, array(
            //             'key' => 'ea_order_id',
            //             'compare' => '=',
            //             'value' => $_GET['order_no']
            //         ));
            //     } else {
            //         $filter_where[] = array(
            //             'key' => 'ea_order_id',
            //             'compare' => '=',
            //             'value' => $_GET['order_no']
            //         );
            //     }

            // }

            // if ($_GET['name']) {
            //     if(!empty($filter_where)) {
            //         array_push($filter_where, array(
            //             'key' => 'ea_name',
            //             'compare' => '=',
            //             'value' => $_GET['name']
            //         ));
            //     } else {
            //         $filter_where[] = array(
            //             'key' => 'ea_name',
            //             'compare' => '=',
            //             'value' => $_GET['name']
            //         );
            //     }

            // }

            // // Phone
            // if ($_GET['phone']) {
            //     if(!empty($filter_where)) {
            //         array_push($filter_where, array(
            //             'key' => 'ea_phone',
            //             'compare' => '=',
            //             'value' => $_GET['phone']
            //         ));
            //     } else {
            //         $filter_where[] = array(
            //             'key' => 'ea_phone',
            //             'compare' => '=',
            //             'value' => $_GET['phone']
            //         );
            //     }

            // }

            // // Email
            // if ($_GET['email']) {
            //     if(!empty($filter_where)) {
            //         array_push($filter_where, array(
            //             'key' => 'ea_email',
            //             'compare' => '=',
            //             'value' => $_GET['email']
            //         ));
            //     } else {
            //         $filter_where[] = array(
            //             'key' => 'ea_email',
            //             'compare' => '=',
            //             'value' => $_GET['email']
            //         );
            //     }
            // }

            // Event
            if ($_GET['event_id']) {
                if(!empty($filter_where)) {
                    array_push($filter_where, array(
                        'key' => 'ea_event_id',
                        'compare' => '=',
                        'value' => $_GET['event_id']
                    ));
                } else {
                    $filter_where[] = array(
                        'key' => 'ea_event_id',
                        'compare' => '=',
                        'value' => $_GET['event_id']
                    );
                }
            }

            // Event Date
            $mep_everyday_dates = isset($_GET['mep_everyday_dates']) ? $_GET['mep_everyday_dates'] : null;
            $time_slot_name = isset($_GET['time_slot_name']) ? $_GET['time_slot_name'] : null;
            if ($_GET['mep_everyday_dates']) {

                if($time_slot_name) {
                    $event_datetime_filter['relation'] = 'AND';
                    $event_datetime_filter[] = array(
                        'key' => 'ea_event_date',
                        'compare' => 'LIKE',
                        'value' => $_GET['mep_everyday_dates']
                    );
                    $event_datetime_filter[] = array(
                        'key' => 'ea_time_slot',
                        'compare' => '=',
                        'value' => $time_slot_name
                    );
                } else {
                    $event_datetime_filter = array(
                        'key' => 'ea_event_date',
                        'compare' => 'LIKE',
                        'value' => $_GET['mep_everyday_dates']
                    );
                }


                if(!empty($filter_where)) {
                    array_push(
                        $filter_where,
                        $event_datetime_filter
                    );
                } else {
                    $filter_where[] = $event_datetime_filter;
                }
            }

            $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : null;
            $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : null;
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'DESC';

            $date_query = array();

            if ($from_date != '') {
                $f_date = date('Y-m-d', strtotime($from_date));
                $date_query['after'] = date('Y-m-d H:i:s', strtotime($f_date . ' 12:01 am'));                

                // $query_where = "AND booking_date BETWEEN '$f_date' AND '$t_date'";

                // $filter_text = 'From ' . date('Y-m-d', strtotime($f_date)) . ' To ' . date('Y-m-d', strtotime($t_date));
            }

            if ($to_date != '') {
                $t_date = date('Y-m-d H:i:s', strtotime($to_date . ' 11:59 pm'));
                $date_query['before'] = $t_date;
            }

            // echo '<pre>'; print_r($date_query);

            // Check in
            // if ($_GET['mep_attendee_checkin']) {
            //     if(!empty($filter_where)) {
            //         array_push($filter_where, array(
            //             'key' => 'mep_checkin',
            //             'compare' => '=',
            //             'value' => $_GET['mep_attendee_checkin']
            //         ));
            //     } else {
            //         $filter_where[] = array(
            //             'key' => 'mep_checkin',
            //             'compare' => '=',
            //             'value' => $_GET['mep_attendee_checkin']
            //         );
            //     }
            // }

            // echo '<pre>'; print_r($filter_where); die;

            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'ea_order_status',
                    'value' => array('completed', 'processing'),
                    'compare' => 'IN'
                ),
                $filter_where
            );

            $args = array(
                'post_type' => 'mep_events_attendees',
                'posts_per_page' => -1,
                'order' => $sort,
                'orderby'           => 'meta_value',
                'meta_key'          => 'ea_event_date',                
                'date_query' => $date_query,
                'meta_query' => $meta_query
            );

            if ($filter_where) {
                $_SESSION['filter_where'] = $filter_where;
            } else {
                unset($_SESSION['filter_where']);
            }

            // Date query
            if ($date_query) {
                $_SESSION['date_query'] = $date_query;
            } else {
                unset($_SESSION['date_query']);
            }

            $res = new WP_Query($args);



            // echo $res->request;

            $table_data = '';
            if ($res && $filter_where) :
                $all_orders = [];
                while ($res->have_posts()) {
                    $res->the_post();
                    $all_orders[] = array(
                        'id' => get_post_meta(get_the_ID(), 'ea_order_id', true),
                        'amount' => (is_numeric(get_post_meta(get_the_ID(), 'ea_ticket_price', true)) ? get_post_meta(get_the_ID(), 'ea_ticket_price', true) : 0),
                        'attendee_id' => get_the_ID()
                    );
                }
                wp_reset_postdata();


// echo '<pre>';
// print_r($res);
// echo '</pre>';

                $i = 0;
                $total_order = 0;
                $total_ticket = 0;
                $total_amount = 0;
                while ($res->have_posts()) : $res->the_post();
                    $id = get_the_ID();
                    $order = wc_get_order($this->wbbm_post_meta($id, 'ea_order_id'));

                    $current_order = $this->wbbm_post_meta($id, 'ea_order_id');
                    $prev_order = (isset($prev_order) ? $prev_order : $current_order);

                    $amount = 0;
                    $count = 0;
                    $j = 0;
                    $seat = array();
                    $ea_name = array();

                    if ($current_order != $prev_order || $i == 0) {
                        foreach ($all_orders as $o) {
                            if ($current_order == $o['id']) {
                                $amount += (float)$o['amount'];
                                $count  += $j+1;
                                $seat[]     = $this->wbbm_post_meta($o['attendee_id'], 'ea_ticket_type');
                                $ea_name[]  = $this->wbbm_post_meta($o['attendee_id'], 'ea_name');
                            }
                        }
                        if($seat) {
                            $seat_count_arr = array_count_values($seat);
                            $seat_count = [];
                            foreach($seat_count_arr as $s => $c) {
                                $seat_count[] = $s.'('.$c.')';
                            }
                        }

// echo '<pre>';
// print_r($ea_name);
// echo '</pre>';
                        $table_data .= '<tr>';
                        $table_data .= '<td>#' . $current_order . '</td>';
                        $table_data .= '<td>' . ($order ? $order->get_formatted_billing_full_name() : "") . '</td>';
                        $table_data .= '<td>' . implode(', ', $seat_count) . '</td>';
                       $table_data .= '<td>' . implode(', ', $ea_name) . '</td>';
                        $table_data .= '<td>' . $count . '</td>';
                        $table_data .= '<td>' . get_the_title($this->wbbm_post_meta($id, 'ea_event_id')) . '</td>';
                        $table_data .= '<td>' . get_the_date() . '</td>';
                        $table_data .= '<td>' . get_mep_datetime(get_post_meta($id, 'ea_event_date', true), 'date-time-text') . '</td>';
                        $table_data .= '<td>' . wc_price($amount) . '</td>';
                        $table_data .= '<td>' . $this->wbbm_post_meta($id, 'ea_order_status') . '</td>';
                        $table_data .= '<td class="wbbm_order_detail--report" data-order-id="' . $current_order . '"><img class="wbbm_report_loading" src="' . plugin_dir_url(__FILE__) . '../' . 'img/loading.gif' . '"/> <div class="action-btn-wrap"><button class="wbbm_detail_inside">' . $this->lang("Details Inside") . '</button></div></td>';
                        $table_data .= '</tr>';

                        $total_order++;
                    }

                    $total_ticket++;
                    $total_amount += $this->wbbm_post_meta($id, 'ea_ticket_price');

                    $j++;
                    $prev_order = $current_order;
                    $i++;
                endwhile; endif;
            wp_reset_postdata(); ?>

            <div class="wbbm-table-top">
                <div class="left">
                    <div class="item">
                        <strong><?php echo $this->lang('Number of Order') ?>:</strong>
                        <span><?php echo ($total_order < 10) ? str_pad($total_order, 1, '0', STR_PAD_LEFT) : $total_order; ?></span>
                    </div>
                    <div class="item">
                        <strong><?php echo $this->lang('Total Ticket') ?>:</strong>
                        <span><?php echo ($total_ticket < 10) ? str_pad($total_ticket, 1, '0', STR_PAD_LEFT) : $total_ticket; ?></span>
                    </div>
                    <div class="item">
                        <strong><?php echo $this->lang('Sold Amount') ?>:</strong>
                        <span><?php echo ($total_amount) ? wc_price($total_amount) : 0.00; ?></span>
                    </div>
                </div>
                <?php 
                if (isset($_SESSION['filter_where'])) : 

                    $default_args = array(
                        'action' => 'mep_report_generate_pdf'
                    );

                    $args       = wp_parse_args( $default_args );
                    $build_url  = http_build_query( $args );
                    $download_pdf_url  = wp_nonce_url( admin_url( "admin-ajax.php?" . $build_url ), $args['action'] );
                ?>
                <div class="right">
                    <!-- PDF Download buton hook -->
                    <?php do_action('mep_report_pdf_export', $download_pdf_url, 'Download PDF'); ?>

                    <form action="" method="GET">
                        <input type="hidden" name="post_type" value="<?php echo $this->post_type ?>">
                        <input type="hidden" name="page" value="<?php echo $this->post_slug ?>">
                        <input style="background: #47a96e;font-weight: 700;border-radius:0" class="wbbm_btn_new" type="submit"
                            name="wbbm_order_wise_csv_export"
                            value="&#8595; Export CSV">
                    </form>
                </div>
                <?php endif;?>
            </div>

            <div id="wbbm_report_table_main">
                <?php echo isset($_SESSION['filter_text']) ? '<p class="wbbm-report-heading">' . $_SESSION['filter_text'] . '</p>' : ''; ?>
                <table class="wbbm-main-table-order-wise">
                    <thead>
                    <tr>
                        <th>Order no</th>
                        <th>Billing Name</th>
                        <th>Ticket</th>
                        <th>Attendee Name</th>
                        <th>Qty</th>
                        <th>Event Name</th>
                        <th>Order Date</th>
                        <th>
                        <form method='get'>
                        <?php 
                            $sort = isset($_GET['sort']) && $_GET['sort'] == 'DESC' ? 'ASC' : 'DESC'; 
                            $sort_icon = $sort == 'DESC' ? '<span class="dashicons dashicons-arrow-up"></span>' : '<span class="dashicons dashicons-arrow-down"></span>';
                        ?>
                            <input type='hidden' name='post_type' value='<?php echo $_GET['post_type'] ?>'/>
                            <input type='hidden' name='page' value='<?php echo $_GET['page'] ?>'/>
                            <input type='hidden' name='from_date' value='<?php echo $_GET['from_date'] ?>'/>
                            <input type='hidden' name='to_date' value='<?php echo $_GET['to_date'] ?>'/>
                            <input type='hidden' name='event_id' value='<?php echo $_GET['event_id'] ?>'/>
                            <input type='hidden' name='filter_key' value='<?php echo $_GET['filter_key'] ?>'/>
                            <input type='hidden' name='recurring_date' value='<?php echo $_GET['recurring_date'] ?>'/>
                            <input type='hidden' name='submit' value='<?php echo $_GET['submit'] ?>'/>
                            <input type='hidden' name='sort' value='<?php echo $sort; ?>'/>
                            <button type='submit' style='background: transparent;border: 0;display: inline-block;padding: 0;margin: 0 0 0 0;cursor: pointer;'>Event Date <?php echo $sort_icon; ?></button>
                        </form>                                                
                        </th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php echo $table_data; ?>
                    </tbody>
                </table>
            </div>

            <?php
            $this->end_div(); // Content End
        }

        // Filter fields for Sales
        public function filter_sales_fields($fd, $td)
        {
            $filter_active = 'filter_active';

            $from_date = null;
            if (isset($_GET['from_date'])) {
                $from_date = $_GET['from_date'] != '' ? $filter_active : null;
            }

            $to_date = null;
            if (isset($_GET['to_date'])) {
                $to_date = $_GET['to_date'] != '' ? $filter_active : null;
            }

            // // Ticket No
            // $ticket_no = null;
            // if (isset($_GET['ticket_no'])) {
            //     $ticket_no = $_GET['ticket_no'] != '' ? $_GET['ticket_no'] : null;
            // }

            // // Order No
            // $order_no = null;
            // if (isset($_GET['order_no'])) {
            //     $order_no = $_GET['order_no'] != '' ? $_GET['order_no'] : null;
            // }

            // // Name
            // $name = null;
            // if (isset($_GET['name'])) {
            //     $name = $_GET['name'] != '' ? $_GET['name'] : null;
            // }

            // // Phone
            // $phone = null;
            // if (isset($_GET['phone'])) {
            //     $phone = $_GET['phone'] != '' ? $_GET['phone'] : null;
            // }

            // // email
            // $email = null;
            // if (isset($_GET['email'])) {
            //     $email = $_GET['email'] != '' ? $_GET['email'] : null;
            // }

            // Event
            $event_id = null;
            if (isset($_GET['event_id'])) {
                $event_id = $_GET['event_id'] != '' ? $_GET['event_id'] : null;
            }

            ?>
            
            <!-- <div class="wbbm-field-group">
                <label for="three_ticket_no"><?php // echo $this->lang('Ticket No') ?></label>
                <input type="text"
                       id="three_ticket_no"
                       name="ticket_no"
                       value="<?php // echo $ticket_no; ?>"
                >
            </div>

            <div class="wbbm-field-group">
                <label for="three_order_no"><?php // echo $this->lang('Order No') ?></label>
                <input type="text"
                       id="three_order_no"
                       name="order_no"
                       value="<?php // echo $order_no; ?>"
                >
            </div>

            <div class="wbbm-field-group">
                <label for="three_name"><?php // echo $this->lang('Name') ?></label>
                <input type="text"
                       id="three_name"
                       name="name"
                       value="<?php // echo $name; ?>"
                >
            </div>

            <div class="wbbm-field-group">
                <label for="three_phone"><?php // echo $this->lang('Phone') ?></label>
                <input type="text"
                       id="three_phone"
                       name="phone"
                       value="<?php // echo $phone; ?>"
                >
            </div>

            <div class="wbbm-field-group">
                <label for="three_email"><?php // echo $this->lang('Email') ?></label>
                <input type="text"
                       id="three_email"
                       name="email"
                       value="<?php // echo $email; ?>"
                >
            </div> -->

            <div class="wbbm-field-group">
                <label for="<?php echo $fd ?>"><?php echo $this->lang('From Date') ?></label>
                <input class="from_date <?php echo($from_date ? $filter_active : '') ?>" type="text"
                       id="<?php echo $fd ?>"
                       name="from_date"
                       value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : '' ?>"
                       placeholder="yyyy-mm-dd">
            </div>

            <div class="wbbm-field-group">
                <label for="<?php echo $td ?>"><?php echo $this->lang('To Date') ?></label>
                <input class="to_date <?php echo($to_date ? $filter_active : '') ?>" type="text" id="<?php echo $td ?>"
                       name="to_date"
                       value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : '' ?>" placeholder="yyyy-mm-dd">
            </div>
            
            <ul class="event_filter_wrap">
                <li>
                    <div class='event_filter'>
                    <select name="event_id" id="mep_event_id" class="select2" required>
                        <option value="0"><?php _e('Select Event', 'mep-form-builder'); ?></option>
                        <?php
                        $args = array(
                            'post_type' => 'mep_events',
                            'posts_per_page' => -1
                        );
                        $loop = new WP_Query($args);
                        $events_query = $loop->posts;
                        foreach ($events_query as $event) {
                        ?>
                            <option value="<?php echo $event->ID; ?>" <?php if ($event_id == $event->ID) {
                                                                            echo 'selected';
                                                                        } ?>><?php echo get_the_title($event->ID); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    </div>
                    <div class='attendee_key_filter' style="display: none;">
                        <input type="text" name='filter_key' value='' id='attendee_filter_key'>
                    </div>
                </li>
                <li id='filter_attitional_btn'>
                    <input type="hidden" id='mep_everyday_ticket_time' name='mep_attendee_list_filter_event_date' value='<?php echo $event_date; ?>'>
                </li>
                <?php //do_action('mep_attendee_list_filter_form_before_btn'); ?>
            </ul>

            <?php
            $this->three_tab_js_dependancy();
        }

        // Content for Sales
        public function content_sales(): array
        {
            $res = $this->filter_query();
            $i = 0;
            $t = 0;

            $html = '';
            if ($res) {

                foreach ($res as $id => $total) {

                    $html .= '<tr>';
                    $html .= '<td>' . get_post_meta($id, 'wbtm_bus_no', true) . '</td>';
                    $html .= '<td>' . get_the_title($id) . '</td>';
                    $html .= '<td>' . $this->currency . number_format($total, 2) . '</td>';
                    $html .= '<td class="wbbm_bus_detail--report" data-bus-id="' . $id . '"><img class="wbbm_report_loading" src="' . plugin_dir_url(__FILE__) . '../' . 'img/loading.gif' . '"/> <div class="action-btn-wrap"><button class="wbbm_detail_inside">' . $this->lang("Details Inside") . '</button> <form action="" method="GET"><input type="hidden" name="post_type" value="' . $this->post_type . '"><input type="hidden" name="page" value="' . $this->post_slug . '"><input type="hidden" name="detail_bus_id" value="' . $id . '"><button type="submit" name="wbbm_detail_export_csv">' . $this->lang("Export Csv") . '</button></form></div></td>';
                    $html .= '</tr>';

                    $t += $total;
                    $i++;
                }
                // Grand total row
                $html .= '<tr>';
                $html .= '<td colspan="2">' . $this->lang('Grand Total') . '</td>';
                $html .= '<td colspan="2">' . $this->currency . number_format($t, 2) . '</td>';
                $html .= '</tr>';

            } else {
                $html .= '<tr>';
                $html .= '<td colspan="2">' . $this->lang('Grand Total:') . '</td>';
                $html .= '<td colspan="2">' . $this->currency . '0</td>';
                $html .= '</tr>';
            }


            return array(
                'head' => array(
                    $this->lang('Coach No'),
                    $this->lang('Bus Name'),
                    $this->lang('Amount'),
                    $this->lang('Action'),
                ),
                'body' => $html
            );
        }

        /*
         * DB Query
        */
        public function filter_query()
        {
            $query_where = '';
            $filter_text = '';
            $c_date = date('Y-m-d');

            $query_string = $_GET;
            $from_date = isset($query_string['from_date']) ? $query_string['from_date'] : '';
            $to_date = isset($query_string['to_date']) ? $query_string['to_date'] : '';
            $bus_id = isset($query_string['bus_id']) ? $query_string['bus_id'] : '';
            $boarding_point = isset($query_string['boarding_point']) ? $query_string['boarding_point'] : '';
            $dropping_point = isset($query_string['dropping_point']) ? $query_string['dropping_point'] : '';

            $filter_meta_query = array();

            if ($from_date != '') {
                $f_date = date('Y-m-d', strtotime($from_date));
                $filter_meta_query[] = array(
                    'key' => 'wbtm_booking_date',
                    'compare' => ">=",
                    'value' => date('Y-m-d H:i a', strtotime($f_date . ' 12:01 am')),
                );
                $query_where = "AND booking_date BETWEEN '$f_date' AND '$c_date'";

                $filter_text = 'From ' . date('Y-m-d', strtotime($from_date)) . ' To ' . $c_date;
            }


            if ($from_date != '' && $to_date != '') {
                $f_date = date('Y-m-d', strtotime($from_date));
                $t_date = date('Y-m-d', strtotime($to_date . ' 23:59:59'));
                $filter_meta_query = array(
                    array(
                        'key' => 'wbtm_booking_date',
                        'compare' => ">=",
                        'value' => date('Y-m-d H:i a', strtotime($f_date . ' 12:01 am')),
                    ),
                    array(
                        'key' => 'wbtm_booking_date',
                        'compare' => '<=',
                        'value' => date('Y-m-d H:i a', strtotime($t_date . ' 11:59 pm'))
                    ),
                );

                $query_where = "AND booking_date BETWEEN '$f_date' AND '$t_date'";

                $filter_text = 'From ' . date('Y-m-d', strtotime($f_date)) . ' To ' . date('Y-m-d', strtotime($t_date));
            }

            if ($bus_id != '') {
                $query_where .= " AND bus_id = '$bus_id'";

                if (!empty($filter_meta_query)) {
                    array_push($filter_meta_query,
                        array(
                            'key' => 'wbtm_bus_id',
                            'compare' => '=',
                            'value' => $bus_id
                        )
                    );
                } else {
                    $filter_meta_query[] = array(
                        'key' => 'wbtm_bus_id',
                        'compare' => '=',
                        'value' => $bus_id
                    );
                }

                $filter_text .= ' Under ' . get_the_title($bus_id);
            }

            if ($boarding_point != '') {
                $query_where .= " AND boarding_point = '$boarding_point'";
                if (!empty($filter_meta_query)) {
                    array_push($filter_meta_query,
                        array(
                            'key' => 'wbtm_boarding_point',
                            'compare' => '=',
                            'value' => $boarding_point
                        )
                    );
                } else {
                    $filter_meta_query[] = array(
                        'key' => 'wbtm_boarding_point',
                        'compare' => '=',
                        'value' => $boarding_point
                    );
                }
            }

            if ($dropping_point != '') {
                $query_where .= " AND droping_point = '$dropping_point'";
                if (!empty($filter_meta_query)) {
                    array_push($filter_meta_query,
                        array(
                            'key' => 'wbtm_droping_point',
                            'compare' => '=',
                            'value' => $dropping_point
                        )
                    );
                } else {
                    $filter_meta_query[] = array(
                        'key' => 'wbtm_droping_point',
                        'compare' => '=',
                        'value' => $dropping_point
                    );
                }
            }

            if (!empty($filter_meta_query)) {
                $_SESSION['filter_text'] = $this->lang('Showing Data') . ' ' . $filter_text;
            } else {
                unset($_SESSION['filter_text']);
            }


            if (isset($query_string['filter_by'])) {
                switch (strtolower($query_string['filter_by'])) {
                    case 'last_year' :
                        $f_date = date("Y-m-d", strtotime("last year January 1st"));
                        $t_date = date("Y-m-d", strtotime("last year December 31st"));
                        $filter_text = $this->lang('Last Year\'s');
                        break;
                    case 'this_year' :
                        $f_date = date('Y') . '-01-01';
                        $t_date = date('Y') . '-12-31';
                        $filter_text = $this->lang('This Year\'s');
                        break;
                    case 'last_month' :
                        $f_date = date("Y-m-d", strtotime("first day of previous month"));
                        $f_date = date("Y-m-d", strtotime($f_date . ' 23:59:59'));
                        $t_date = date("Y-m-d", strtotime("last day of previous month"));
                        $t_date = date("Y-m-d", strtotime($t_date . ' 23:59:59'));
                        $filter_text = $this->lang('Last Month\'s');
                        break;
                    case 'this_month' :
                        $f_date = date("Y-m-d", strtotime("first day of this month"));
                        $t_date = date("Y-m-d", strtotime("last day of this month"));
                        $filter_text = $this->lang('This Month\'s');
                        break;
                    case 'last_week' :
                        $f_date = date("Y-m-d", strtotime("-7 days"));
                        $t_date = date("Y-m-d", strtotime("yesterday"));
                        $filter_text = $this->lang('Last Week\'s');
                        break;
                }

                $filter_meta_query = array(
                    array(
                        'key' => 'wbtm_booking_date',
                        'compare' => '>=',
                        'value' => date('Y-m-d H:i a', strtotime($f_date . ' 12:01 am')),
                        // 'type' => 'DATE'
                    ),
                    array(
                        'key' => 'wbtm_booking_date',
                        'compare' => '<=',
                        'value' => date('Y-m-d H:i a', strtotime($t_date . ' 11:59 pm'))
                        // 'type' => 'DATE'
                    ),
                );

                $query_where = "AND booking_date >= '$f_date' AND booking_date <= '$t_date'";

                if ($filter_text != '') {
                    $_SESSION['filter_text'] = $this->lang('Showing') . ' ' . $filter_text . ' Data';
                } else {
                    unset($_SESSION['filter_text']);
                }
            }

            // echo $query_where;
            if ($filter_meta_query != '') {
                $_SESSION['filter_where'] = $filter_meta_query;
            } else {
                unset($_SESSION['filter_where']);
            }

            // Final Query
            if ($filter_meta_query) {

                // Bus lists
                $bus_lists = $this->bus_lists();
                // Bus lists END

                // Main Query
                $meta_query = array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wbtm_status',
                        'compare' => 'IN',
                        'value' => array(1, 2)
                    ),
                    $filter_meta_query
                );

                $args = array(
                    'post_type' => 'mep_events_attendees',
                    'posts_per_page' => -1,
                    'order' => 'DESC',
                    'meta_query' => $meta_query
                );

                $res = new WP_Query($args);

                $all_buses = array();
                while ($res->have_posts()) {
                    $res->the_post();
                    $all_buses[] = array(
                        'id' => get_post_meta(get_the_ID(), 'wbtm_bus_id', true),
                        'amount' => get_post_meta(get_the_ID(), 'wbtm_bus_fare', true),
                        'booking' => get_post_meta(get_the_ID(), 'wbtm_booking_date', true),
                    );
                }
                wp_reset_postdata();
                // Main Query END

                $final = array();
                if ($bus_lists) {
                    foreach ($bus_lists as $bus) {
                        $amount = 0;
                        foreach ($all_buses as $data) {
                            if ($bus == $data['id']) {
                                $amount += (float)$data['amount'];
                                $final[$bus] = $amount;
                            }
                        }
                    }
                }

            }

            // echo '<pre>'; print_r($final); die;

            if (isset($final)) {
                return $final;
            } else {
                return null;
            }
        }

        public function filter_day_items(): array
        {
            return array(
                array(
                    'value' => 7,
                    'name' => $this->lang('7 Days'),
                ),
                array(
                    'value' => 10,
                    'name' => $this->lang('10 Days'),
                ),
                array(
                    'value' => 15,
                    'name' => $this->lang('15 Days'),
                ),
                array(
                    'value' => 30,
                    'name' => $this->lang('30 Days'),
                ),
                array(
                    'value' => 120,
                    'name' => $this->lang('120 Days'),
                ),

            );
        }

        public function buses()
        {
            $args = array(
                'post_type' => 'wbtm_bus',
                'posts_per_page' => -1
            );

            return $args;
        }

        /*
         * Language
         * */
        public function lang($text): string
        {
            $text = __($text, $this->text_domain);

            return $text;
        }

        /*
         * Start Div
        */
        public function start_div($className = null, $id = null)
        {
            echo '<div ' . ($className ? "class=$className" : "") . ' ' . ($id ? "id=$id" : "") . '>';
        }

        /*
         * End Div
        */
        public function end_div()
        {
            echo '</div>';
        }

        // Ajax
        public function wbbm_get_bus_details()
        {
            $bus_id = $_POST['bus_id'];
            $filter_where = $_SESSION['filter_where'];


            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'wbtm_status',
                    'compare' => 'IN',
                    'value' => array(1, 2)
                ),
                array(
                    'key' => 'wbtm_bus_id',
                    'compare' => '=',
                    'value' => $bus_id
                ),
                $filter_where
            );

            $args = array(
                'post_type' => 'mep_events_attendees',
                'posts_per_page' => -1,
                'order' => 'DESC',
                'meta_query' => $meta_query
            );

            $res = new WP_Query($args);
            // ***********

            $html = '';
            if ($res) {
                $html .= '<tr class="wbbm_report_detail"><td colspan="4">';
                $html .= '<table><thead><tr>';
                $html .= '<th>Order No</th><th>Booking date</th><th>Name</th><th>Seat</th><th>Boarding</th><th>Dropping</th><th>Price</th><th>Status</th>';
                $html .= '</tr></thead>';
                $html .= '<tbody>';
                while ($res->have_posts()) {
                    $res->the_post();
                    $id = get_the_ID();
                    $order = wc_get_order($this->wbbm_post_meta($id, 'wbtm_order_id'));
                    $html .= '<tr>';
                    $html .= '<td>#' . $this->wbbm_post_meta($id, 'wbtm_order_id') . '</td><td>' . $this->wbbm_post_meta($id, 'wbtm_booking_date') . '</td><td>' . ucfirst($this->wbbm_post_meta($id, 'wbtm_user_name')) . '</td><td>' . ucfirst($this->wbbm_post_meta($id, 'wbtm_seat')) . '</td><td>' . $this->wbbm_post_meta($id, 'wbtm_boarding_point') . '</td><td>' . $this->wbbm_post_meta($id, 'wbtm_droping_point') . '</td>';

                    $html .= '<td>' . $this->currency . (($this->wbbm_post_meta($id, 'wbtm_bus_fare') > 0) ? $this->wbbm_post_meta($id, 'wbtm_bus_fare') : 0) . '</td>';
                    $html .= '<td>' . ($order ? $order->get_status() : "") . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
                $html .= '</td></tr>';
            }
            wp_reset_postdata();

            echo $html;
            exit;
        }

        public function wbbm_get_order_details_callback()
        {
            $order_id = $_POST['order_id'];
            $filter_where = $_SESSION['filter_where'];

            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'ea_order_status',
                    'value' => array('completed', 'processing'),
                    'compare' => 'IN'
                ),
                array(
                    'key' => 'ea_order_id',
                    'value' => $order_id,
                    'compare' => '='
                ),
                $filter_where
            );

            $args = array(
                'post_type' => 'mep_events_attendees',
                'posts_per_page' => -1,
                'order' => 'DESC',
                'meta_query' => $meta_query
            );

            $res = new WP_Query($args);

            $html = '';
            if ($res) {
                ob_start();              
                ?>
        <tr class="wbbm_report_detail">
            <td colspan="10">
            <style>
.report-action-btns a {
    display: inline-block;
    text-decoration: none;
    margin: 0 3px;
}
            </style>
                <table>
                    <thead>
                        <tr>
                            <th>Ticket No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Seat</th>
                            <th>Price</th>    
                            <?php do_action('mep_attendee_list_heading'); ?>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                while ($res->have_posts()) {
                    $res->the_post();
                    $id = get_the_ID();
                    $order = wc_get_order($this->wbbm_post_meta($id, 'wbtm_order_id'));
                    ?>
                    <tr>
                        <td><?php echo $this->wbbm_post_meta($id, 'ea_ticket_no'); ?></td>
                        <td><?php echo ucfirst($this->wbbm_post_meta($id, 'ea_name')); ?></td>
                        <td><?php echo $this->wbbm_post_meta($id, 'ea_email'); ?></td>
                        <td><?php echo $this->wbbm_post_meta($id, 'ea_phone'); ?></td>
                        <td><?php echo ucfirst($this->wbbm_post_meta($id, 'ea_ticket_type')); ?></td>
                        <td><?php echo  wc_price(($this->wbbm_post_meta($id, 'ea_ticket_price') > 0) ? $this->wbbm_post_meta($id, 'ea_ticket_price') : 0); ?></td>
                        <?php do_action('mep_attendee_list_item', $id); ?>                   
                        <td>
                        <div class='report-action-btns'>
                        <a href="<?php echo get_the_permalink($id); ?>" title='View' target='_blank'><span class="dashicons dashicons-visibility"></span></a>
                        <a href="<?php echo get_admin_url() . "post.php?post=$id&action=edit"; ?>"  title='Edit' target='_blank'><span class="dashicons dashicons-edit-large"></span></a>
                        <?php do_action('mep_attendee_list_item_action_middile', $id); ?>
                        <!-- <span  title='Delete' class="mep_del_attendee" data-id=<?php echo $id; ?>><span class="dashicons dashicons-no"></span></span> -->
                        <?php do_action('mep_attendee_list_item_action_after', $id); ?>                    
                        </div>
                        </td>
                    </tr>
                    <?php
                  }
                }
                wp_reset_postdata();
                ?>
                </tbody>
                </table>
            </td>
        </tr>
            <?php
            echo ob_get_clean();
            exit;
        }

        protected function wbbm_post_meta($id, $key)
        {
            if ($id && $key) {
                return get_post_meta($id, $key, true);
            } else {
                return false;
            }
        }

        // Export CSV
        public function wbbm_export_csv_callback()
        {
            $filter_meta_query = $_SESSION['filter_where'];
            $filter_text = $_SESSION['filter_text'];
            $msg = false;

            // Bus Lists
            $bus_lists = $this->bus_lists();
            // Bus lists END

            // Main Query
            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'wbtm_status',
                    'compare' => 'IN',
                    'value' => array(1, 2)
                ),
                $filter_meta_query
            );

            $args = array(
                'post_type' => 'mep_events_attendees',
                'posts_per_page' => -1,
                'order' => 'DESC',
                'meta_query' => $meta_query
            );

            $res = new WP_Query($args);
            $all_buses = array();
            while ($res->have_posts()) {
                $res->the_post();
                $all_buses[] = array(
                    'id' => get_post_meta(get_the_ID(), 'wbtm_bus_id', true),
                    'amount' => get_post_meta(get_the_ID(), 'wbtm_bus_fare', true),
                );
            }
            wp_reset_postdata();
            // Main Query END

            $data_rows = array();
            if ($all_buses) {
                $domain = $filter_text ? $filter_text : '';
                $filename = 'Report_' . $domain . '_' . time() . '.csv';

                $header_row = array(
                    'Coach no',
                    'Bus Name',
                    'Amount'
                );

                $g_total = 0;
                $final = array();
                if ($bus_lists) {
                    foreach ($bus_lists as $bus) {
                        $amount = 0;
                        foreach ($all_buses as $data) {
                            if ($bus == $data['id']) {
                                $amount += (float)$data['amount'];
                                $final[$bus] = $amount;

                                $g_total += (float)$data['amount'];
                            }
                        }
                    }
                }

                if ($final) {
                    foreach ($final as $id => $amount) {
                        $data_rows[] = array(
                            get_post_meta($id, 'wbtm_bus_no', true),
                            get_the_title($id),
                            $amount
                        );
                    }
                }

                if ($data_rows) {
                    array_push($data_rows, array('Total', '', $g_total));
                    $this->csv($header_row, $data_rows, $filename);
                }
                $msg = true;
            }
        }

        public function wbbm_order_wise_export_csv_callback()
        {
            $filter_where = $_SESSION['filter_where'];
            $date_query = isset($_SESSION['date_query']) ? $_SESSION['date_query'] : array();
            $msg = false;

            // Main Query
            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'ea_order_status',
                    'value' => array('completed', 'processing'),
                    'compare' => 'IN'
                ),
                $filter_where
            );

            $args = array(
                'post_type' => 'mep_events_attendees',
                'posts_per_page' => -1,
                'order' => 'DESC',
                'date_query' => $date_query,
                'meta_query' => $meta_query
            );

            $res = new WP_Query($args);

            $header_row = array(
                'Order no',
                'Billing Name',
                'Ticket',
                'Qty',
                'Event Name',
                'Event Date',
                'Price',
                'Status',
            );

            $data_rows = array();
            if ($res && $filter_where) :
                $all_orders = [];
                while ($res->have_posts()) {
                    $res->the_post();
                    $all_orders[] = array(
                        'id' => get_post_meta(get_the_ID(), 'ea_order_id', true),
                        'amount' => (is_numeric(get_post_meta(get_the_ID(), 'ea_ticket_price', true)) ? get_post_meta(get_the_ID(), 'ea_ticket_price', true) : 0),
                        'attendee_id' => get_the_ID()
                    );
                }
                wp_reset_postdata();

                $i = 0;
                $total_order = 0;
                $total_ticket = 0;
                $total_amount = 0;
                while ($res->have_posts()) : $res->the_post();
                    $id = get_the_ID();
                    $order = wc_get_order($this->wbbm_post_meta($id, 'ea_order_id'));

                    $current_order = $this->wbbm_post_meta($id, 'ea_order_id');
                    $prev_order = (isset($prev_order) ? $prev_order : $current_order);

                    $amount = 0;
                    $count = 0;
                    $j = 0;
                    $seat = array();

                    if ($current_order != $prev_order || $i == 0) {
                        foreach ($all_orders as $o) {
                            if ($current_order == $o['id']) {
                                $amount += (float)$o['amount'];
                                $count  += $j+1;
                                $seat[] = $this->wbbm_post_meta($o['attendee_id'], 'ea_ticket_type');
                            }
                        }
                        if($seat) {
                            $seat_count_arr = array_count_values($seat);
                            $seat_count = [];
                            foreach($seat_count_arr as $s => $c) {
                                $seat_count[] = $s.'('.$c.')';
                            }
                        }

                        $data_rows[] = array(
                            $current_order,
                            ($order ? $order->get_formatted_billing_full_name() : ""),
                            implode(', ', $seat_count),
                            $count,
                            get_the_title($this->wbbm_post_meta($id, 'ea_event_id')),
                            get_mep_datetime(get_post_meta($id, 'ea_event_date', true), 'date-time-text'),
                            $amount,
                            $this->wbbm_post_meta($id, 'ea_order_status'),
                        );

                    }

                    $j++;
                    $prev_order = $current_order;
                    $i++;
                endwhile; endif;
            wp_reset_postdata();


            if ($data_rows) {
                $domain = isset($filter_text) ? $filter_text : '';
                $filename = 'Report_' . $domain . '_' . time() . '.csv';

                // $g_total = 0;
                // array_push($data_rows, array('Total', '', $g_total));

                $this->csv($header_row, $data_rows, $filename);
                $msg = true;
            }
        }

        public function wbbm_detail_export_csv($bus_id)
        {
            $filter_where = $_SESSION['filter_where'];
            $filter_text = $_SESSION['filter_text'];
            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'wbtm_status',
                    'compare' => 'IN',
                    'value' => array(1, 2)
                ),
                array(
                    'key' => 'wbtm_bus_id',
                    'compare' => '=',
                    'value' => $bus_id
                ),
                $filter_where
            );

            $args = array(
                'post_type' => 'mep_events_attendees',
                'posts_per_page' => -1,
                'order' => 'DESC',
                'meta_query' => $meta_query
            );

            $res = new WP_Query($args);
            // ***********

            if ($bus_id) {

                if ($res) {
                    $domain = $filter_text ? str_replace(' ', '_', $filter_text) : '';
                    $filename = 'Report_' . $domain . '_' . time() . '.csv';

                    $header_row = array(
                        'Order no',
                        'Booking Date',
                        'Name',
                        'Type',
                        'Boarding',
                        'Dropping',
                        'Price',
                        'Status',
                    );

                    $g_total = 0;
                    $data_rows = array();
                    while ($res->have_posts()) {
                        $res->the_post();
                        $id = get_the_ID();
                        $order = wc_get_order($this->wbbm_post_meta($id, 'wbtm_order_id'));
                        $data_rows[] = array(
                            $this->wbbm_post_meta($id, 'wbtm_order_id'),
                            $this->wbbm_post_meta($id, 'wbtm_booking_date'),
                            ucfirst($this->wbbm_post_meta($id, 'wbtm_user_name')),
                            ucfirst($this->wbbm_post_meta($id, 'wbtm_seat')),
                            $this->wbbm_post_meta($id, 'wbtm_boarding_point'),
                            $this->wbbm_post_meta($id, 'wbtm_droping_point'),
                            $this->wbbm_post_meta($id, 'wbtm_bus_fare'),
                            ($order ? $order->get_status() : "")
                        );

                        $g_total += $this->wbbm_post_meta($id, 'wbtm_bus_fare');
                    }

                    if ($data_rows) {
                        array_push($data_rows, array('', '', '', '', '', '', 'Total', $g_total));
                        $this->csv($header_row, $data_rows, $filename);
                    }
                }
            }
        }

        public function wbbm_order_wise_detail_export_csv($order_id)
        {
            $filter_where = $_SESSION['filter_where'];
            $filter_text = isset($_SESSION['filter_text']) ? $_SESSION['filter_text'] : '';

            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'wbtm_status',
                    'compare' => 'IN',
                    'value' => array(1, 2)
                ),
                array(
                    'key' => 'wbtm_order_id',
                    'compare' => '=',
                    'value' => $order_id
                ),
                $filter_where
            );

            $args = array(
                'post_type' => 'mep_events_attendees',
                'posts_per_page' => -1,
                'order' => 'DESC',
                'meta_query' => $meta_query
            );

            $res = new WP_Query($args);

            // ***********

            if ($order_id) {
                if ($res) {
                    $domain = $filter_text ? str_replace(' ', '_', $filter_text) : '';
                    $filename = 'Report_' . $domain . '_' . time() . '.csv';

                    $header_row = array(
                        'Name',
                        'Email',
                        'Phone',
                        'Seat',
                        'Price'
                    );

                    $g_total = 0;
                    $data_rows = array();
                    while ($res->have_posts()) {
                        $res->the_post();
                        $id = get_the_ID();
                        $order = wc_get_order($this->wbbm_post_meta($id, 'wbtm_order_id'));
                        $data_rows[] = array(
                            ucfirst($this->wbbm_post_meta($id, 'wbtm_user_name')),
                            $this->wbbm_post_meta($id, 'wbtm_user_email'),
                            $this->wbbm_post_meta($id, 'wbtm_user_phone'),
                            $this->wbbm_post_meta($id, 'wbtm_seat'),
                            $this->wbbm_post_meta($id, 'wbtm_seat'),
                            $this->currency . (($this->wbbm_post_meta($id, 'wbtm_bus_fare') > 0) ? $this->wbbm_post_meta($id, 'wbtm_bus_fare') : 0)
                        );

//                        $g_total += $this->wbbm_post_meta($id, 'wbtm_bus_fare');
                    }

                    wp_reset_postdata();

                    if ($data_rows) {
//                        array_push($data_rows, array('', '', '', '', '', '', 'Total', $g_total));
                        $this->csv($header_row, $data_rows, $filename);
                    }
                }
            }
        }

        public function csv($header_row, $data_rows, $filename)
        {
            $fh = fopen('php://output', 'w');
            ob_clean(); // clean slate
            fprintf($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Description: File Transfer');
            header('Content-type: text/csv');
            header("Content-Disposition: attachment; filename={$filename}");
            header('Expires: 0');
            header('Pragma: public');
            fputcsv($fh, $header_row);
            foreach ($data_rows as $data_row) {
                fputcsv($fh, $data_row);
            }
            ob_flush(); // dump buffer
            fclose($fh);
            die();
        }

        public function bus_lists()
        {
            $bus_lists = array();
            $bus_args = array(
                'post_type' => 'wbtm_bus',
                'posts_per_page' => -1,
            );

            $bus_res = new WP_Query($bus_args);
            if ($bus_res) {
                while ($bus_res->have_posts()) {
                    $bus_res->the_post();
                    $bus_lists[] = get_the_ID();
                }
            }
            wp_reset_postdata();

            return $bus_lists;
        }

        public function three_tab_js_dependancy() {
            ?>

            <script>
                (function($) {
                    'use strict';
                    $(document).ready(function($) {
                        <?php do_action('mep_fb_attendee_list_script'); ?>
                    });
                })(jQuery);
            </script>

            <?php
        }

        public function mep_report_generate_pdf_callback() 
        {
            $file_name = __('Event_sales_report_','addon-bus--ticket-booking-with-seat-pro').time().'.pdf';
            $html   = $this->get_current_data_pdf();
            // echo $html;die;

            if( function_exists('mep_get_mpdf_support_version') ) {
                if(mep_get_mpdf_support_version() > 1.0){
                    $mpdf = new \Mpdf\Mpdf();
                }else{
                    $mpdf = new mPDF();
                }
                $mpdf->allow_charset_conversion=true;  // Set by default to TRUE
                $mpdf->autoScriptToLang = true;
                $mpdf->baseScript = 1;
                $mpdf->autoVietnamese = true;
                $mpdf->autoArabic = true;
                $mpdf->autoLangToFont = true;
    
                header('Content-Type: application/pdf');
    
                $mpdf->WriteHTML($html);
                $mpdf->Output($file_name, 'D');
                exit;
            }
        }

        public function get_current_data_pdf(){
            $filter_where = $_SESSION['filter_where'];

            $shortcode = '[order-report-pdf]';

            ob_start();
            echo do_shortcode($shortcode);
            return ob_get_clean();
        }

        public function mep_order_report_pdf_callback($attr)
        {   $filter_where = $_SESSION['filter_where'];

            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'ea_order_status',
                    'value' => array('completed', 'processing'),
                    'compare' => 'IN'
                ),
                $filter_where
            );

            $args = array(
                'post_type' => 'mep_events_attendees',
                'posts_per_page' => -1,
                'order' => 'DESC',
                'meta_query' => $meta_query
            );

            $res = new WP_Query($args);

            $table_data = '';
            if ($res && $filter_where) :
                $all_orders = [];
                while ($res->have_posts()) {
                    $res->the_post();
                    $all_orders[] = array(
                        'id' => get_post_meta(get_the_ID(), 'ea_order_id', true),
                        'amount' => (is_numeric(get_post_meta(get_the_ID(), 'ea_ticket_price', true)) ? get_post_meta(get_the_ID(), 'ea_ticket_price', true) : 0),
                        'attendee_id' => get_the_ID()
                    );
                }
                wp_reset_postdata();

                $i = 0;
                $total_order = 0;
                $total_ticket = 0;
                $total_amount = 0;
                while ($res->have_posts()) : $res->the_post();
                    $id = get_the_ID();
                    $order = wc_get_order($this->wbbm_post_meta($id, 'ea_order_id'));

                    $current_order = $this->wbbm_post_meta($id, 'ea_order_id');
                    $prev_order = (isset($prev_order) ? $prev_order : $current_order);

                    $amount = 0;
                    $count = 0;
                    $j = 0;
                    $seat = array();

                    if ($current_order != $prev_order || $i == 0) {
                        foreach ($all_orders as $o) {
                            if ($current_order == $o['id']) {
                                $amount += (float)$o['amount'];
                                $count  += $j+1;
                                $seat[] = $this->wbbm_post_meta($o['attendee_id'], 'ea_ticket_type');
                            }
                        }
                        if($seat) {
                            $seat_count_arr = array_count_values($seat);
                            $seat_count = [];
                            foreach($seat_count_arr as $s => $c) {
                                $seat_count[] = $s.'('.$c.')';
                            }
                        }

                        $table_data .= '<tr>';
                        $table_data .= '<td>#' . $current_order . '</td>';
                        $table_data .= '<td>' . ($order ? $order->get_formatted_billing_full_name() : "") . '</td>';
                        $table_data .= '<td>' . implode(', ', $seat_count) . '</td>';
                        $table_data .= '<td>' . $count . '</td>';
                        $table_data .= '<td>' . get_the_title($this->wbbm_post_meta($id, 'ea_event_id')) . '</td>';
                        $table_data .= '<td>' . get_mep_datetime(get_post_meta($id, 'ea_event_date', true), 'date-time-text') . '</td>';
                        $table_data .= '<td>' . wc_price($amount) . '</td>';
                        $table_data .= '<td>' . $this->wbbm_post_meta($id, 'ea_order_status') . '</td>';
                        $table_data .= '</tr>';

                        $total_order++;
                    }

                    $total_ticket++;
                    $total_amount += $this->wbbm_post_meta($id, 'ea_ticket_price');

                    $j++;
                    $prev_order = $current_order;
                    $i++;
                endwhile; endif;
            wp_reset_postdata(); ?>

            <div class="mep-report-header--pdf">
                <span><?php echo $this->lang('Event Sales Report') ?></span>
            </div>
            <br>
            <br>

            <div class="wbbm-table-top--pdf">
                <div class="right">
                    <div class="item">
                        <strong><?php echo $this->lang('Number of Order') ?>:</strong>
                        <span><?php echo ($total_order < 10) ? str_pad($total_order, 1, '0', STR_PAD_LEFT) : $total_order; ?></span>
                    </div>
                    <div class="item">
                        <strong><?php echo $this->lang('Total Ticket') ?>:</strong>
                        <span><?php echo ($total_ticket < 10) ? str_pad($total_ticket, 1, '0', STR_PAD_LEFT) : $total_ticket; ?></span>
                    </div>
                    <div class="item">
                        <strong><?php echo $this->lang('Sold Amount') ?>:</strong>
                        <span><?php echo ($total_amount) ? wc_price($total_amount) : 0.00; ?></span>
                    </div>
                </div>
            </div>

            <div id="wbbm_report_table_main--pdf">
                <table class="wbbm-main-table-order-wise--pdf">
                    <thead>
                    <tr>
                        <th>Order no</th>
                        <th>Billing Name</th>
                        <th>Ticket</th>
                        <th>Qty</th>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php echo $table_data; ?>
                    </tbody>
                </table>
            </div>
            <?php

            $this->report_pdf_style();
        }

        protected function report_pdf_style()
        {
            ?>

            <style>
                #wbbm_report_table_main--pdf {
                    width: 100%;
                }
                .wbbm-main-table-order-wise--pdf {
                    width: 100%;
                }
                .wbbm-main-table-order-wise--pdf tr th {
                    text-align: left;
                }
                .wbbm-main-table-order-wise--pdf tr th,
                .wbbm-main-table-order-wise--pdf tr td {
                    font-size: 14px;
                    line-height: 1em;
                    font-family: arial, sans-serif;
                }
                .wbbm-main-table-order-wise--pdf tr td {
                    font-size: 13px;
                }
                #wbbm_report_table_main--pdf table thead tr {
                    background: #95A5A6;
                }
                #wbbm_report_table_main--pdf table tbody tr:nth-child(even) {
                    background: #dcdbdb;
                }
                .wbbm-table-top--pdf {
                    overflow: hidden;
                }
                .wbbm-table-top--pdf .left {
                    float: left;
                    width: 20%;
                }
                .wbbm-table-top--pdf .right .item {
                    background: #C7FDFD;
                    padding: 6px 1px 1px;
                    border-radius: 3px;
                    float: left;
                    margin-right: 2%;
                    width: 33%;
                    box-sizing: border-box;
                    text-align: center;
                }
                .wbbm-table-top--pdf .right .item span {
                    color: #f44336;
                }
                .wbbm-table-top--pdf .right .item:last-child {
                    margin-right: 0;
                }
                .mep-report-header--pdf {
                    text-align: center;
                }
                .mep-report-header--pdf span {
                    margin-bottom: 30px;
                    text-transform: uppercase;
                    border-bottom: 2px solid #000;
                    color: #000;
                    display: inline-block;
                    font-weight: 700;
                    padding-bottom: 30px;
                }
                .wbbm-table-top--pdf .right {
                    color: #3a3a3a;
                    width: 100%;
                    font-size: 13px;
                }
            </style>

            <?php
        }

    }

    new WbbmReport('mep_events', 'mep-reports', 'mep-form-builder');
}