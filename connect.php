<?php

try {
    $myPDO = new PDO("pgsql:host=ec2-52-44-31-100.compute-1.amazonaws.com;dbname=d9gbnhqsslvumi;user=cvjvcfygarrxpt;password=0f79bcb74f6de687639e6a9f73aa0cbd4ea4f21bfaecea939bc1afc3b9db0621");
    // $sql1 = "INSERT INTO tb_leave(user_id,d_date,d_detail) VALUES (1,'2021-03-15','ไปหาหมอ')";
    // $myPDO->query($sql1);
    // $sql = "SELECT * FROM tb_leave WHERE user_id=2";
    // foreach($myPDO->query($sql) as $row) {
    //     print "<br/>";
    //     print $row["user_id"].$row["d_date"].$row["d_detail"].'<br/>';
    // }
    $sql1 = "SELECT COUNT(*) FROM tb_leave";
    $myPDO->query($sql1);
    foreach($myPDO->query($sql) as $row) {
        print "<br/>";
        print $row["user_id"].$row["d_date"].$row["d_detail"].'<br/>';
    }



} catch(PDOException $e) {
    echo $e->getMessage();
}


?>