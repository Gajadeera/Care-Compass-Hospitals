<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 0);
    session_start();
}
require __DIR__ . '/../routes/router.php';
