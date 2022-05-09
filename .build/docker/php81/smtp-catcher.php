#! /usr/bin/php
<?php

# create a filename for the emlx file
list($ms, $time) = explode(' ', microtime());
$dir = '/code/var/emails/';
$filename = 'email-' . date('Y-m-d h.i.s,', $time) . substr($ms, 2, 3) . '.emlx';

# write the email contents to the file
$fp = fopen('php://stdin', 'rb');
$email = '';

while (!feof($fp)) {
    $email .= fgets($fp);
}

if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

file_put_contents($dir . $filename, strlen($email) . "\n");
file_put_contents($dir . $filename, $email, FILE_APPEND);
