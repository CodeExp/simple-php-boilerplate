<?php
session_start();

require_once 'app/backend/init/config.php';
require_once 'app/backend/core/Helpers.php';

spl_autoload_register("autoload");

require_once 'app/backend/init/cookie.php';
require_once 'app/backend/init/user.php';
