<?php
require_once 'qiwi.php';
header('Content-Type: application/json');

$qiwi = new Qiwi('', $_POST['token']);
$response = [];

if(isset($_POST['token']) && strlen($_POST['token']) == 32) {
    if($account = $qiwi->getAccount()) {
        $response['status'] = true;
        $response['block'] = $account['contractInfo']['blocked'] ?? false;
        if(isset($_POST['check']['registration'])) $response['registration'] = $account['authInfo']['registrationDate'];
        if(isset($_POST['check']['lastIP'])) $response['lastIP'] = $account['authInfo']['ip'];

        $qiwi = new Qiwi($account['authInfo']['personId'], $_POST['token']);
        $response['balance'] = $qiwi->getBalance()["accounts"][0]["balance"]["amount"];
        if(isset($_POST['check']['turnover'])) {
            $limits = $qiwi->actualLimits(['types[0]' => 'TURNOVER']);
            $response['turnover'] = $limits['limits']['RU'][0]['spent'];
        }
    } else {
        $response['status'] = false;
        $response['error'] = 'Невалидные данные!';
    }
} else {
    $response['status'] = false;
    $response['error'] = 'Невалидные данные!';
}

echo json_encode($response);