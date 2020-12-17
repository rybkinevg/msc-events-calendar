<?php

class MSCEC_Public
{
    // Название плагина
    private $plugin_name;

    // Актуальная версия плагина
    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function get_custom_templates($template)
    {
        if (is_archive('events')) {
            if ($new_template = MSCEC_DIR . 'public/templates/archive-events.php')
                $template = $new_template;
        }
        if (is_singular('events')) {
            if ($new_template = MSCEC_DIR . 'public/templates/single-events.php')
                $template = $new_template;
        }

        return $template;
    }

    public function events_filter_callback()
    {
        check_ajax_referer('events_filter_nonce', 'nonce');

        $args = [
            'post_type' => 'events',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ];

        if (isset($_GET['date'])) {

            $date = date("Y-m-d", strtotime($_GET['date']));

            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = '_time_start';
            $args['meta_query'][] = [
                'key' => 'date',
                'value' => $date,
                'compare' => '='
            ];
        }

        if (isset($_GET['events_title']) && !empty($_GET['events_title'])) {

            $args['orderby'] = 'relevance';
            $args['s'] = $_GET['events_title'];
        }

        if (isset($_GET['events_type']) && !empty($_GET['events_type'])) {

            $args['meta_query'][] = [
                'relation'     => 'AND',
            ];

            $args['meta_query']['events-date'] = [
                'key' => '_date',
                'compare' => 'EXISTS',
                'type' => 'DATE'
            ];

            $args['meta_query']['events-time'] = [
                'key' => '_time_start',
                'compare' => 'EXISTS',
                'type' => 'TIME'
            ];

            $args['meta_query']['events-type'] = [
                'key' => '_type',
                'value' => $_GET['events_type'],
                'compare' => '='
            ];

            $args['orderby'] = [
                'events-date' => 'DESC',
                'events-time' => 'ASC'
            ];
        }

        if (isset($_GET['events_organizer']) && !empty($_GET['events_organizer'])) {

            $args['meta_query'][] = [
                'relation'     => 'AND',
            ];

            $args['meta_query']['events-date'] = [
                'key' => '_date',
                'compare' => 'EXISTS',
                'type' => 'DATE'
            ];

            $args['meta_query']['events-time'] = [
                'key' => '_time_start',
                'compare' => 'EXISTS',
                'type' => 'TIME'
            ];

            $args['meta_query']['events-organizer'] = [
                'key' => 'organizer',
                'value' => $_GET['events_organizer'],
                'compare' => '='
            ];

            $args['orderby'] = [
                'events-date' => 'DESC',
                'events-time' => 'ASC'
            ];
        }

        $query = new WP_Query($args);

        include(MSCEC_DIR . 'public/templates/loop.php');

        wp_die();
    }

    public function get_events_array()
    {
        $events = [];

        $args = [
            'post_type' => 'events',
            'posts_per_page' => -1
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $date = carbon_get_post_meta(get_the_ID(), 'date');
                if ($date) {
                    if (!in_array($date, $events)) {
                        array_push($events, $date);
                    }
                }
            }
        }

        wp_reset_postdata();

        return $events;
    }

    // Регистрирует стили
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            MSCEC_URL . 'public/assets/css/style.css',
            [],
            $this->version,
            'all'
        );

        wp_enqueue_style(
            'air-datepicker',
            MSCEC_URL . 'public/assets/libs/air-datepicker/datepicker.min.css',
            [],
            $this->version,
            'all'
        );
    }

    // Регистрирует скрипты
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'air-datepicker',
            MSCEC_URL . 'public/assets/libs/air-datepicker/datepicker.min.js',
            ['jquery'],
            $this->version,
            true
        );

        wp_enqueue_script(
            "$this->plugin_name",
            MSCEC_URL . 'public/assets/js/script.js',
            ['jquery'],
            $this->version,
            true
        );

        if (is_archive('events') || is_singular('events')) {
            wp_localize_script(
                "$this->plugin_name",
                'ajax',
                [
                    'url' => admin_url('admin-ajax.php'),
                    'dates' => $this->get_events_array()
                ]
            );
        }
    }
}
