<?php
if (!$user->isLoggedIn()) {
    Redirect::to("/login.php");
}
