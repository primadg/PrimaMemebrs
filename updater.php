<?php
$message=false;
//version 2.0.3
if(file_exists($system_folder."/application/helpers/xml_helper.php"))
{
    if(!unlink($system_folder."/application/helpers/xml_helper.php"))
    {
        $message="<span style='color:red;'>
The update script couldn't automatically delete the file ".$system_folder."/application/helpers/xml_helper.php.<br/>Please delete it manually.</span>"; 
        $heading="Updating Error!";
        $title="Updating Error!";
    }
}



if($message)
{
    include($system_folder."/application/views/admin/message.php");
    exit;
}
?>
