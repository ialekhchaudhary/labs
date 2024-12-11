<?php
session_start();
session_destroy();
header("Location: index.php?message=You are now logged out!");
exit();
