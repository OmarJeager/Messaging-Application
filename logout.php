<?php 
session_start();

session_unset();
session_destroy();

header(header:exit("go out "));
exit;