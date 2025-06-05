<?php
date_default_timezone_set("Asia/Jakarta");
ini_set('display_errors',1);
error_reporting(E_ALL);
setlocale(LC_ALL, 'en_US.UTF8');
header("Content-type: application/json;  charset=utf-8");
ini_set("max_execution_time", 30);

function mlog($nama, $teks){
    $jamtgl = date("YmdHis");
    $date   = date("Ymd");
    $teksna = $jamtgl.'= '.$teks.PHP_EOL;
    $myfile = fopen("./logs/".$date.'_'.$nama.".log", "a+") or die("Unable to open file!");
    
    fwrite($myfile, $teksna);

    fclose($myfile);
    return true;
}

function logs($teks){
    $basename   = $_SERVER["SCRIPT_FILENAME"];
    $basename   = explode('/', $basename);
    $nama   = str_replace('.php', '', end($basename));
    $bt     = debug_backtrace();
    $caller = array_shift($bt);
    $line   = $caller['line'];
    $jamtgl = date("YmdHis");
    $date   = date("Ymd");
    $green  = "\033[32m";
    $red    = "\033[31m";
    $reset  = "\033[0m";
    $teksna = $green . $jamtgl . $reset . '|' . $red . $line . $reset . '= ' . $teks . PHP_EOL;
    // $teksna = $jamtgl.'|'.$line.'= '.$teks.PHP_EOL;
    $myfile = fopen("./logs/".$date.'_'.$nama.".log", "a+") or die("Unable to open file!");
    
    fwrite($myfile, $teksna);

    fclose($myfile);
    return true;
}

function errors($teks){
    $basename   = $_SERVER["SCRIPT_FILENAME"];
    $basename   = explode('/', $basename);
    $nama   = 'error_'.str_replace('.php', '', end($basename));
    $bt     = debug_backtrace();
    $caller = array_shift($bt);
    $line   = $caller['line'];
    $jamtgl = date("YmdHis");
    $date   = date("Ymd");
    $green  = "\033[32m";
    $red    = "\033[31m";
    $reset  = "\033[0m";
    $teksna = $green . $jamtgl . $reset . '|' . $red . $line . $reset . '= ' . $teks . PHP_EOL;
    // $teksna = $jamtgl.'|'.$line.'= '.$teks.PHP_EOL;
    $myfile = fopen("./logs_error/".$date.'_'.$nama.".log", "a+") or die("Unable to open file!");
    
    fwrite($myfile, $teksna);

    fclose($myfile);
    return true;
}
?>