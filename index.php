<?php
header('Content-Type: text/html;charset=utf-8');
require_once "public.php";
@session_start();
require  './Framework/Framework.class.php';
Framework::run();