<?php
function sendMessage($replyJson, $sendInfo){
    $ch = curl_init($sendInfo["URL"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $sendInfo["AccessToken"])
        );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $replyJson);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
 }
?>