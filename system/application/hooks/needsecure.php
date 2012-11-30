<?php


function pre_load_hook()
{
    pre_config();
}


function display_hook()
{
    $CI = &get_instance();
    echo replace_lang($CI->output->get_output());
}


function post_config()
{
    if (!defined("NEEDSECURE_FILE_PROTECT_URL"))
    {//this checks disables hook when codeigniter is loaded through /protect.php
        save_config();
    }
}

?>
