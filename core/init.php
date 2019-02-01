<?php
/**
 * Created by PhpStorm.
 * User: AbdeAMNR
 * Date: 18/02/2017
 * Time: 12:16
 */

$hostAdress = 'localhost';
$userName = 'root';
$passWord = '12345';
$datebaseName = 'e_commerce_pfe_2017';

$db = mysqli_connect($hostAdress, $userName, $passWord, $datebaseName);
if (mysqli_connect_errno()) {
    echo 'Database connection failed with the following errors: ' . mysqli_connect_error();
    die();
}
require_once realpath($_SERVER["DOCUMENT_ROOT"]) . '/amnrStore2017/config.php';
require_once BASEURL . 'helpers/helpers.php';

