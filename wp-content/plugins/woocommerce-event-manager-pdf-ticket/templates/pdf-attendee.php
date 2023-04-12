<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendee List</title>
    <style>
        .header {
            height: 60px;
        }
        .header .col {
            width: 33.33%;
            float: left;
            text-align: center;
        }
        .header .col:first-child {
            text-align: initial;
        }
        .header .col:last-child {
            text-align: right;
        }
        .content {
            clear: both;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            height: 50px;
        }
        table, th, td {
            border: 1px solid black;
        }
        table th,table td {
            text-align: center;
        }
        table tbody tr:nth-child(odd) {
            background: #96dfff;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="col">
            <strong><?php _e('Event name','mage-eventpress-pdf') ?>:</strong> <?php echo (get_the_title($event_id) ? : null); ?>
        </div>
        <div class="col">
            <strong><?php _e('Venue','mage-eventpress-pdf') ?>:</strong> <?php echo (get_post_meta($event_id, 'mep_location_venue', true) ? : null); ?>
        </div>
        <div class="col">
            <strong>Date<?php _e('Venue','mage-eventpress-pdf') ?>:</strong> <?php echo (get_post_meta($event_id, 'event_start_datetime', true) ? date('Y-m-d g:i a', strtotime(get_post_meta($event_id, 'event_start_datetime', true))) : null); ?>
        </div>
    </div>
    <div class="content">
        <table>
            <thead>
            <tr>
                <th><?php _e('Ticket No','mage-eventpress-pdf') ?></th>
                <th><?php _e('Order ID','mage-eventpress-pdf') ?></th>
                <th><?php _e('Event Name','mage-eventpress-pdf') ?></th>
                <th><?php _e('Ticket','mage-eventpress-pdf') ?></th>
                <th><?php _e('Full Name','mage-eventpress-pdf') ?></th>
                <th><?php _e('Email','mage-eventpress-pdf') ?></th>
                <th><?php _e('Phone','mage-eventpress-pdf') ?></th>
                <th><?php _e('Address','mage-eventpress-pdf') ?></th>
                <th><?php _e('Tee Size','mage-eventpress-pdf') ?></th>
                <th><?php _e('Order Status','mage-eventpress-pdf') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $i) : ?>
                <tr>
                    <td><?php echo $i[0] ?></td>
                    <td><?php echo $i[1] ?></td>
                    <td><?php echo $i[2] ?></td>
                    <td><?php echo $i[3] ?></td>
                    <td><?php echo $i[4] ?></td>
                    <td><?php echo $i[5] ?></td>
                    <td><?php echo $i[6] ?></td>
                    <td><?php echo $i[7] ?></td>
                    <td><?php echo $i[8] ?></td>
                    <td><?php echo (count($i) == 11) ? $i[10] : $i[9] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>