<?php
require("CtfWeb.php");
set_time_limit(0);
$obj = new CtfWeb();
$obj->loginWEB();
while (true) {
    $obj->readChallenges();
    $obj->saveChallenges();
    sleep(15);
}