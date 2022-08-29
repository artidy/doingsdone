<?php
session_start();
require_once("helpers.php");

unset($_SESSION['user']);
redirectTo("/");
