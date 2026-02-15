<?php
/**
 * Logout and redirect to login
 * Use this if you want to test the login page
 */

session_start();
session_destroy();
header('Location: http://localhost/hrm-php/auth/login');
exit;