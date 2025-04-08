<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'mlm_binary');
define('DB_USER', 'root');
define('DB_PASS', '');

// Registration Ticket Prices (in Rupiah)
define('TICKET_PRICE_REGULAR', 100000);
define('TICKET_PRICE_STOKIS', 90000);

// Package Prices (in Rupiah)
define('PACKAGE_BASIC', 300000);
define('PACKAGE_SILVER', 600000);
define('PACKAGE_GOLD', 1000000);
define('PACKAGE_PLATINUM', 1500000);

// Bonus Percentages
define('BONUS_SPONSOR', 0.10); // 10%
define('BONUS_LEVEL_1', 0.07); // 7%
define('BONUS_LEVEL_2', 0.05); // 5%
define('BONUS_LEVEL_3', 0.03); // 3%
define('BONUS_LEVEL_4', 0.02); // 2%
define('BONUS_LEVEL_5', 0.01); // 1%
define('BONUS_PAIRING', 0.05); // 5%

// Daily Pairing Bonus Cap (in Rupiah)
define('DAILY_PAIRING_CAP', 10000000);

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session Configuration
ini_set('session.cookie_httponly', 1);
session_start();