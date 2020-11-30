<?php

class MSCEC_Public
{

    // Путь до файла
    private $dir = MSCEC_DIR . 'public/';

    //
    public function get_custom_templates($template)
    {
        if (is_archive('events')) {
            if ($new_template = $this->dir . 'templates/archive-events.php')
                $template = $new_template;
        }
        if (is_singular('events')) {
            if ($new_template = $this->dir . 'templates/single-events.php')
                $template = $new_template;
        }

        return $template;
    }
}
