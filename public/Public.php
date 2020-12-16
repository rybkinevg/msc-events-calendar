<?php

class MSCEC_Public
{
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
}
