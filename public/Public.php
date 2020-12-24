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
        if (is_post_type_archive('events')) {
            if ($new_template = MSCEC_DIR . 'public/templates/archive-events.php')
                $template = $new_template;
        }
        if (is_singular('events')) {
            if ($new_template = MSCEC_DIR . 'public/templates/single-events.php')
                $template = $new_template;
        }

        return $template;
    }

    public function events_calendar_callback()
    {
        check_ajax_referer('events_calendar_nonce', 'nonce');

        if (isset($_GET['date']) && !empty($_GET['date'])) {

            $date = date("Y-m-d", strtotime($_GET['date']));

            $args = [
                'post_type' => 'events',
                'posts_per_page' => 5,
                'post_status' => 'publish',
                'order' => 'ASC',
                'orderby' => 'events-time',
                'paged' => get_query_var('paged', 1),
                'meta_query' => [
                    'events-date' => [
                        'key' => 'date',
                        'value' => $date,
                        'compare' => '='
                    ],
                    'events-time' => [
                        'key' => '_time_start',
                        'compare' => 'EXISTS',
                        'type' => 'TIME'
                    ]
                ]
            ];

            include(MSCEC_DIR . 'public/templates/loop.php');
        }

        wp_die();
    }

    public function events_filter_callback()
    {
        check_ajax_referer('events_filter_nonce', 'nonce');

        $args = [
            'post_type' => 'events',
            'posts_per_page' => 5,
            'post_status' => 'publish',
            'paged' => get_query_var('paged', 1)
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

        if (isset($_GET['events_date']) && !empty($_GET['events_date'])) {

            $date = date("Y-m-d", strtotime($_GET['events_date']));

            $args['meta_query'][] = [
                'key' => 'date',
                'value' => $date,
                'compare' => '='
            ];
        }

        if (isset($_GET['events_title']) && !empty($_GET['events_title'])) {

            $args['orderby'] = 'relevance';
            $args['s'] = esc_html($_GET['events_title']);
        }

        if (isset($_GET['events_type']) && !empty($_GET['events_type'])) {

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

        include(MSCEC_DIR . 'public/templates/loop.php');

        wp_die();
    }

    public function get_events_array()
    {
        $events = [];

        $args = [
            'post_type' => 'events',
            'posts_per_page' => -1,
            'post_status' => 'publish'
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

    static function get_organizator_name($organizator_slug)
    {
        $name = '';

        $args = [
            'post_type' => 'events_organizers',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'name' => $organizator_slug
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {

                $query->the_post();

                $name = carbon_get_post_meta(get_the_ID(), 'name');
            }
        } else {
            $name = 'Не выбран';
        }

        wp_reset_postdata();

        return $name;
    }

    static function get_organizator_link($organizator_slug)
    {
        $link = '';

        $args = [
            'post_type' => 'events_organizers',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'name' => $organizator_slug
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {

                $query->the_post();

                $link = carbon_get_post_meta(get_the_ID(), 'link') ? carbon_get_post_meta(get_the_ID(), 'link') : '#';
            }
        } else {
            $link = '#';
        }

        wp_reset_postdata();

        return $link;
    }

    function true_load_posts()
    {
        $args = unserialize(stripslashes($_POST['query']));
        $args['paged'] = $_POST['page'] + 1;

        foreach ($args as $key => $value) {
            if (empty($args[$key])) {
                unset($args[$key]);
            }
        }

        include(MSCEC_DIR . 'public/templates/loop.php');

        wp_die();
    }

    // Регистрирует стили
    public function enqueue_styles()
    {
        if (is_archive('events') || is_singular('events')) {

            wp_enqueue_style(
                'choices',
                MSCEC_URL . 'public/assets/libs/choices/choices.min.css',
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

            wp_enqueue_style(
                $this->plugin_name,
                MSCEC_URL . 'public/assets/css/style.css',
                [],
                $this->version,
                'all'
            );
        }
    }

    // Регистрирует скрипты
    public function enqueue_scripts()
    {
        if (is_archive('events') || is_singular('events')) {

            wp_enqueue_script(
                'choices',
                MSCEC_URL . 'public/assets/libs/choices/choices.min.js',
                ['jquery'],
                $this->version,
                true
            );

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
