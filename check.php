<?php
require_once 'qiwi.php';
$qiwi = new Qiwi($_POST['phone'], $_POST['token']);

$error = '';
$balanse = 0;

if(isset($qiwi->getBalance()["accounts"])) { // Проверка валидности
    $status = true;
    $balanse = $qiwi->getBalance()["accounts"][0]["balance"]["amount"]; // Проверка баланса
} else { // Невалидный аккаунт
    $status = false;
    $error = 'Невалидные данные!';
}

$response = array(
    "status" => $status,
    "balance" => $balanse,
    "error" => $error,
);

echo json_encode($response, JSON_FORCE_OBJECT); // Ответ