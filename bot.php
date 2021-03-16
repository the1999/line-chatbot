<?php
require_once ('vendor/autoload.php');
require_once ('connect.php');
$LINEData = file_get_contents('php://input');
$jsonData = json_decode($LINEData,true);

$replyToken = $jsonData["events"][0]["replyToken"];
$userID = $jsonData["events"][0]["source"]["userId"];
$text = $jsonData["events"][0]["message"]["text"];
$date = $jsonData["events"][0]["date"];

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


$val = (explode(",",$text));

if ($text == "ดูรายการหน่อย"){
    $message = '{
           "type" : "text",
           "text" : "รายการเมนู(พิมพ์ตัวเลขเพื่อดำเนินการ) \n1.บันทึกการลา \n2.ดูข้อมูลการลาทั้งหมด \n3.ยกเลิกการลา"
    }';
    $replyText = json_decode($message);
}

else if ($text == "1.บันทึกการลา") {
    $message = '{
        "type" : "text",
        "text" : "คุณต้องการลาวันที่เท่าไหร่  \n ตัวอย่างเช่น ใส่IDของคุณ,2021-01-01,ไปหาหมอ"
    }';
    $replyText = json_decode($message);
} 

else if (preg_match("/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/",$val[1])) {
    $sql_query1 = "INSERT INTO tb_leave(user_id,d_date,d_detail) VALUES ('".$val[0]."','".$val[1]."','".$val[2]."')";
    $myPDO->query($sql_query1);
    $message = '{
        "type" : "text",
        "text" : "บันทึกเรียบร้อย"
    }';
    $replyText = json_decode($message);
    }

else if ($text == "2.ดูข้อมูลการลา") {
    $sql_query2 = "SELECT * FROM tb_user";
    $myPDO->query($sql_query2);    
    $test = "";
    foreach ($myPDO->query($sql_query2) as $row) {
        
        $test .= $row["d_id"].$row["d_name"];
        echo '\n';
    } 
    echo $test;
  
    $replyText["type"] = "text";
    $replyText["text"] = "$test";

}

$lineData['URL'] = "https://api.line.me/v2/bot/message/reply";
$lineData['AccessToken'] = "t6aLTUxPu8V6uO+Mk51mAbUhzXglRM0SXXbxb4SVhFp+04unUqFmNz34MWQyQTao/SQJy+euTHs/s35Y45+N7B+p4PMLoHm63lrTwScrVyqhrQlKqY3BzU/tASZMxYO9X1khaUIMHKCxgER1V1W3AAdB04t89/1O/w1cDnyilFU=";

$replyJson["replyToken"] = $replyToken;
$replyJson["messages"][0] = $replyText;

$encodeJson = json_encode($replyJson);

$ms = sendMessage($encodeJson,$lineData);
echo $ms;
http_response_code(200);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
