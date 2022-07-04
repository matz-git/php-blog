<?php 
    $shortopts  = "";
    $shortopts .= "g:";
    $longopts  = array(
        "generate:",
    );
    $options = getopt($shortopts, $longopts);
    $key = $options["g"];

    echo password_hash($key, PASSWORD_DEFAULT) . "\n"; 
?>
