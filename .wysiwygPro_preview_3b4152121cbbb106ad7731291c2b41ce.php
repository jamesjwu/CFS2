<?php
if ($_GET['randomId'] != "kQ1DjpCoK_Bo4JQQSryznhCEzL1Xz7__B2IScq01SCLrRsyNuNybZBMFg_OqndjO") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
