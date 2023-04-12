<?php
// Template Name: Billet
?>
<style>
    .pdf-main-body {
        max-width: 680px;
        width: 680px;
        margin: 0 auto;
        padding: 50px 0;
    }

    .pdf-header-part {
        display: block;
        overflow: hidden;
    }
    .logo-part {
        width: 130px;
        float: left;
        display: block;
        overflow: hidden;
    }
    .header-right-part {
        float: left;
        width: 520px;
        display: block;
        overflow: hidden;
        padding-left: 20px;
        vertical-align: top;
    }
    img {
        max-width: 100%;
        width: auto;
        height: auto;
        max-height: 100%;
    }
    .header-right-col {
        display: block;
        overflow: hidden;
    }
    .right-col {
        width: 50%;
        float: left;
        text-align: left;
    }
    .right-col h2 {
    font-size: 30px;
}
    .right-col ul {
        padding: 0;
        margin: 0;
        list-style: none;
        color:#333;
    }
    .right-col ul li{
        color:#333;
    }
    .header-title {
        vertical-align: text-bottom;
    }

    .header-title h2 {
        padding: 0;
        margin: 0;
    }

    .right-col.left {
        text-align: center;
    }

    .pdf-ticket-body-content {
        font-size: 12px;
        overflow: hidden;
        border-bottom: 1px solid #ddd;
        margin-bottom: 40px;
    }

    .ticket-info ul {
        padding: 0;
        margin: 0;
        font-size: 16px;
        list-style: none;
    }

    .ticket-info ul li {
        margin: 20px 0;
        color:#333;
    }

    .ticket-body-footer {
        display: block;
        overflow: hidden;
        margin: 30px 0;
    }

    .footer-logo {
        width: 100px;
        float: left;
    }

    .footer-content {
        width: 550px;
        float: left;
        padding-left: 20px;
    }

    .footer-content p {
        padding: 0;
        margin: 0 0 12px 0;
        font-size: 13px;
    }

    .ticket-printable-part {
        border: 2px solid #333;
        padding: 10px;
    }

    .print-title {
        text-align: center;
    }

    .print-title h3 {
        padding: 0;
        margin: 0;
        overflow: hidden;
    }

    .print-content {
        overflow: hidden;
        display: block;
    }

    .print-content-col {
        width: 50%;
        float: left;
        padding: 30px 0;
    }

    .print-content-col ul {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .print-content-col ul li {
        display: block;
        margin: 40px 0 0px 0;
        color:#333;
    }
</style>



<div class="pdf-main-body">
    <div class="pdf-header-part">
        <div class="logo-part">                
        <?php do_action('mep_pdf_logo'); ?>   </div>
        <div class="header-right-part">
            <div class="header-right-col">
                <div class="right-col left">
                    <h2>E-BILLET</h2>
                </div>
                <div class="right-col">
                    <ul>
                            <li>Distillerie de Biercée</li>
                            <li>Rue de la Roquette 36B</li>
                            <li>B-6532 Ragnies</li>
                            <li>+32 (0) 494 502 501</li>
                        </ul>                    
                </div>
            </div>
            <div class="header-title">
                <h2><?php do_action('mep_pdf_event_ticket_type',$ticket_id); ?> <?php do_action('mep_pdf_event_ticket_price',$ticket_id);  ?></h2>
            </div>
        </div>
    </div>

    <div class="pdf-ticket-body-content">
        <div class="ticket-info">
            <ul>
                <li>Valable le <?php do_action('mep_pdf_start_date',$ticket_id); ?> <?php do_action('mep_pdf_start_time',$ticket_id);  ?></li>
                <li>Acheteur: <?php do_action('mep_event_pdf_attendee_name',$ticket_id); ?></li>
                <li>Numéro ticket: <?php do_action('mep_pdf_event_ticket_no',$ticket_id); ?></li>
                <li>Date d'achat: <?php echo get_the_date($ticket_id).' '.get_the_time($ticket_id); ?></li>
            </ul>
            <p>Ce ticket est valable uniquement pour la date et l'horaire indiqué. </p>
            <p>Imprimez ce ticket, il vous sera demandé à l'entrée de la distillerie. Excellente visite et bonne dégustation!</p>
            <p>Il est impératif de vous présenter 15 minutes avant l'heure indiquée.</p>
        </div>
        <div class="ticket-body-footer">
            <div class="footer-logo">

                <?php do_action('mep_qr_code', get_the_permalink($ticket_id),$ticket_id); ?>
            </div>
            <div class="footer-content">
                <p>Envie de boire un verre ou de déguster des plats de notre terroir? </p>
                <p>La Grange des Légendes vous accueillera dans les murs de la Distillerie avec grand
                    plaisir</p>
                <p>Pour tout renseignement & réservation :</p>
                <p>La Grange des Légendes +32 (0)71 40 49 59 +32 (0)470 532 012</p>
            </div>
        </div>
    </div>


    <div class="ticket-printable-part">
        <div class="print-title">
            <h3>Bon pour une dégustation & souvenir</h3>
        </div>
        <div class="print-content">
            <div class="print-content-col left">
                <ul>
                    <li><?php do_action('mep_pdf_event_ticket_type',$ticket_id); ?> <?php do_action('mep_pdf_event_ticket_price',$ticket_id);  ?></li>
                    <li>Valable le <?php do_action('mep_pdf_start_date',$ticket_id); ?> <?php do_action('mep_pdf_start_time',$ticket_id);  ?></li>
                    <li>Numéro ticket: <?php do_action('mep_pdf_event_ticket_no',$ticket_id); ?></li>
                </ul>
            </div>
            <div class="print-content-col right">
            <?php do_action('mep_qr_code', get_the_permalink($ticket_id),$ticket_id); ?>
            </div>
        </div>
    </div>
</div>