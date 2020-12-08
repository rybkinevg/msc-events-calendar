<?php

class MSCEC_Deactivator
{
    public static function deactivate()
    {
        unregister_post_type('events');
        unregister_post_type('events_organizers');
        flush_rewrite_rules();
    }
}
