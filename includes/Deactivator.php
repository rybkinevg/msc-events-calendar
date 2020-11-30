<?php

class MSCEC_Deactivator
{
    public static function deactivate()
    {
        unregister_post_type('alcohol1');
        flush_rewrite_rules();
    }
}
