<?php
require_once ('bot.php');

try {
    $myPDO = new PDO("pgsql:host=ec2-52-44-31-100.compute-1.amazonaws.com;dbname=d9gbnhqsslvumi;user=cvjvcfygarrxpt;password=0f79bcb74f6de687639e6a9f73aa0cbd4ea4f21bfaecea939bc1afc3b9db0621");
    $val = (explode(",",$text));
    if (preg_match("/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/",$val)) {
        $sql_query1 = "INSERT INTO tb_leave (d_date, d_detail) VALUES ('".$val[0]."','".$val[1]."')";
        $myPDO->query($sql_query1);
            $message = '{
                "type" : "text",
                "text" : "บันทึกเรียบร้อย"
            }';
            $replyText = json_decode($message);   
    } 
    $sql = "SELECT * FROM tb_leave";
    foreach($myPDO->query($sql) as $row) {
        print "<br/>";
        print $row["d_date"].$row["d_detail"].'<br/>';
    }
} catch(PDOException $e) {
    echo $e->getMessage();
}


?>