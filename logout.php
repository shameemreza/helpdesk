<?php

setcookie('user', '', time() - 999999, '/');
header('Location: ./');
die();