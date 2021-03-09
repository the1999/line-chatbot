<?php
require_once ('vendor/autoload.php');
// require_once ('connect.php');
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

try {
    $myPDO = new PDO("pgsql:host=ec2-52-44-31-100.compute-1.amazonaws.com;dbname=d9gbnhqsslvumi;user=cvjvcfygarrxpt;password=0f79bcb74f6de687639e6a9f73aa0cbd4ea4f21bfaecea939bc1afc3b9db0621");
    
    $sql = "SELECT * FROM tb_leave";

    foreach($myPDO->query($sql) as $row) {
        print "<br/>";
        print $row["d_date"].$row["d_detail"].'<br/>';
  
$val = (explode(",",$text));

if ($text == "ดูรายการหน่อย"){
    $message = '{
           "type" : "text",
           "text" : "รายการเมนู(พิมพ์ตัวเลขเพื่อดำเนินการ) \n1.บันทึกการลา \n2.ดูข้อมูลการลาทั้งหมด \n3.ยกเลิกการลา"
    }';
    $replyText = json_decode($message);
}

else if ($text == "1") {
    $message = '{
        "type" : "text",
        "text" : "คุณต้องการลาวันที่เท่าไหร่  \n ตัวอย่างเช่น 2021-01-01,ไปหาหมอ"
    }';
    $replyText = json_decode($message);
} 

else if (preg_match("/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/",$val[0])) {
    $sql1 = "INSERT INTO tb_leave (d_date, d_detail) VALUES ('".$val[0]."',".$val[1].")";
    $myPDO->query($sql1);
        $message = '{
            "type" : "text",
            "text" : "บันทึกเรียบร้อย"
        }';
        $replyText = json_decode($message);   
} 
else if ($text == "ดูรายชื่อพนักงาน") {
    $sql_query2 = "SELECT * FROM tb_user";
    $myPDO->query($sql_query2);
    foreach($myPDO->query($sql) as $row) {
        $ID1 = [1]['d_id'];
        $ID2 = [2]['d_id'];   
        $ID3 = [3]['d_id']; 
        $ID4 = [4]['d_id']; 
        $d_name1 = [1]['d_name'];
        $d_name2 = [2]['d_name'];
        $d_name3 = [3]['d_name'];
        $d_name4 = [4]['d_name'];
        $row1 = $row1.$d_name1;
        $row2 = $row2.$d_name2;
        $row3 = $row3.$d_name3;
        $row4 = $row4.$d_name4;
        }
    $replyText["type"] = "text";
    $replyText["text"] = "$row1.$row2.$row3.$row4";
}

} 
} catch(PDOException $e) {
    echo $e->getMessage();
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
