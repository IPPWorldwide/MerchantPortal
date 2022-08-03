<?php
include("base.php");
setcookie('ipp_type', null, -1, '/');
setcookie('ipp_user_id', null, -1, '/');
setcookie('ipp_user_session_id', null, -1, '/');
header("location: /");
exit;