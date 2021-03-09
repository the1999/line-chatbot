<?php

function connectDB() {

    $myPDO = new PDO("pgsql:host=ec2-54-144-251-233.compute-1.amazonaws.com;dbname=dps0dpcf8a74o;user=tbftfmgczwqoqe;password=55c4b70ab2cc5a1c89844eadbc093bc623b89b4bca1d4acb397a220b74dc88fa");

}

if(connectDB()) {
    echo "connected";
} else {
    echo "failed";
}





?>