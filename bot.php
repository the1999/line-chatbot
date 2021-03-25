<?php
require_once ('vendor/autoload.php');
require_once ('connect.php');
require_once ('sendMessages.php');

$LINEData = file_get_contents('php://input');
$jsonData = json_decode($LINEData,true);
$replyToken = $jsonData["events"][0]["replyToken"];
$text = $jsonData["events"][0]["message"]["text"];
$massageArray = (explode(",",$text));
$detail = (explode(".",$text));
$delete = (explode("/",$text));
$has_date = preg_match("/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/",$massageArray[1]);
$has_message_detail = !empty($detail[1]);
$has_message_delete = !empty($delete[1]);

if ($text == "ดูรายการหน่อย"){
    $message = '{
           "type" : "text",
           "text" : "รายการเมนู(พิมพ์ตัวเลขเพื่อดำเนินการ) \n1.บันทึกการลา \n2.ดูข้อมูลการลา \n3.ยกเลิกการลา"
    }';
    $replyText = json_decode($message);
}
else if ($text == "บันทึกการลา") {
    $message = '{
        "type" : "text",
        "text" : "คุณต้องการลาวันที่เท่าไหร่  \n ตัวอย่างเช่น \nใส่IDของคุณ,2021-01-01,ไปหาหมอ"
    }';
    $replyText = json_decode($message);
}
else if ($text == "ยกเลิกการลา") {
    $message = '{
        "type" : "text",
        "text" : "คุณต้องการยกเลิกการลาวันไหน  \n ตัวอย่างเช่น \nใส่ไอดีของคุณ/ไอดีที่ต้องการลา"
    }';
    $replyText = json_decode($message);
}
else if ($has_date) {
    $sql_query1 = "INSERT INTO tb_leave(user_id,d_date,d_detail) VALUES ('".$user_id=$massageArray[0]."','".$d_date=$massageArray[1]."','".$d_detail=$massageArray[2]."')";
    $myPDO->query($sql_query1);
        $message = '{
            "type" : "text",
            "text" : "บันทึกเรียบร้อย"
        }';
        $replyText = json_decode($message);
    }
else if ($text == "ดูข้อมูลการลา") {
    $sql_query2 = "SELECT * FROM tb_user";
    $myPDO->query($sql_query2);    
    $test = "";
    foreach ($myPDO->query($sql_query2) as $row) {
        $test .= $row["d_id"].'.'.$row["d_name"]."\n";
    } 
    echo $test;
  
    $replyText["type"] = "text";
    $replyText["text"] = "รายชื่อพนักงาน\n $test";
}
else if ($has_message_detail) {
    $sql_query3 = "SELECT * FROM tb_leave WHERE user_id=".$detail[0];
    $myPDO->query($sql_query3); 
    $test1 = "";
    foreach ($myPDO->query($sql_query3) as $row) {
        $test1 .= '['.$row["d_id"].']'.$row["d_date"].','.$row["d_detail"]."\n";
    } 
    echo $test1;

    $replyText["type"] = "text";
    $replyText["text"] = "$test1";
} 
else if($has_message_delete) { 
        $sql_query4 = "DELETE FROM tb_leave WHERE user_id=$delete[0] AND d_id=$delete[1]";
        $myPDO->query($sql_query4); 
        $message = '{
            "type" : "text",
            "text" : "ยกเลิกการลาเรียบร้อย"
        }';
        $replyText = json_decode($message);
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
