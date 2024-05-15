<?php

if (! $user->isLoggedIn())
{
     Redirect::to('index.php');
}

$data = $user->data();


