<?php
date_default_timezone_set('UTC');

$file = "./file.txt";
$orig = file_get_contents($file);
$plaintext = htmlentities($orig);

$shortopts  = "";
$shortopts .= "p:";

$longopts  = array(
    "password:",
);
$options = getopt($shortopts, $longopts);
$key = $options["p"];

$filename = date("d-m-Y") . ".enc";

$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
$iv = openssl_random_pseudo_bytes($ivlen);
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );

$fp = fopen('./files/'.$filename, 'wb');
fwrite($fp, $ciphertext);
fclose($fp);