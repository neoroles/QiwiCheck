<?php
require_once 'qiwi.php';
$qiwi = new Qiwi($_POST['phone'], $_POST['token']);

$error = $last_payment = '';
$balanse = 0;

if(isset($qiwi->getBalance()["accounts"])) { // Проверка валидности
    $status = true;
    $balanse = $qiwi->getBalance()["accounts"][0]["balance"]["amount"]; // Проверка баланса

    // Последняя транзакция
    $last_payment = $qiwi->getPaymentsHistory(['rows' => '1']);
    $last_payment['data'][0]['date'] ? $last_payment = date_format(date_create($last_payment['data'][0]['date']), 'Y.m.d H:i') : $last_payment = '';
} else { // Невалидный аккаунт
    $status = false;
    $error = 'Невалидные данные!';
}

$response = array(
    "status" => $status,
    "balance" => $balanse,
    "last_payment" => $last_payment,
    "error" => $error,
);

echo json_encode($response, JSON_FORCE_OBJECT); // Ответ