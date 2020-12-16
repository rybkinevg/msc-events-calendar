<?php

get_header();

$args = [
    'post_type' => 'events',
    'posts_per_page' => -1,
];

$query = new WP_Query($args);

?>

<div class="mscec-container">
    <div class="wpsec-controll">
        <input id="wpsec-datepicker" type='text' class='wpsec-datepicker' autocomplete="off" data-inline="true" />
    </div>
    <div class="wpsec-events">
        <ul class="wpsec-list">
            <?php include(MSCEC_DIR . 'public/templates/loop.php') ?>
        </ul>
    </div>
</div>

<?php get_footer(); ?>