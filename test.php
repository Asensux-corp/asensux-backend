<?php
$hash = password_hash('admin123', PASSWORD_BCRYPT);
var_dump($hash);
var_dump(password_verify('admin123', $hash));
