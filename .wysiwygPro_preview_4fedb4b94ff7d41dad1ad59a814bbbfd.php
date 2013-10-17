<?php
if ($_GET['randomId'] != "fnW5TdEbe6bPpyQ9VrjTMyHpugK_zscvB4hBYaSGniq_aJ8ArXCXJ8eQSUcLknqb") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
