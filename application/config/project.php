<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// UPDATE/IMPORT
$config["access_key"] = "asecretkey";
$config["import_data_path"] = __DIR__."/../../data/";

$config["start_researchers_amount"] = 4;
$config["start_researchers_level_num"] = 1;

// ROUNDS
$config["update_interval"] = 1;

// REGISTRATION CONFIRMATION EMAIL
$config["send_regc_email"] = FALSE;
$config["regc_email_from_addr"] = "ulifeci@shrt.ws";
$config["regc_email_from_name"] = "ULifeCI";
$config["regc_email_subject"] = "ULife - Bestätigung deiner Registrierung";
$config["regc_email_text"] = "Bitte benutze folgenden Link um dich freizuschalten: ";

// START VALUES
$config["start_value_wood"] = 1000;
$config["start_value_stones"] = 1000;
$config["start_value_food"] = 1000;
$config["start_value_coins"] = 1000;
$config["start_value_citizen"] = 1000;
$config["start_value_experience"] = 1000;