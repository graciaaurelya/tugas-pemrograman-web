<?php
 
declare(strict_types=1);
 
require_once './Transaction.php';
 
session_start();
 
$_SESSION['balance'] ??= 0.0;
$_SESSION['transactions'] ??= [];