<?php

$Manifest = [];

$Manifest['ID'] = 'HCI__MAP__API';
$Manifest['Version'] = '20180503';
$Manifest['Title'] = 'HCI / MAP / API';
$Manifest['Description'] = '';
$Manifest['InstanceSensitive'] = true; // deprecated, all modules are considered to be instance sensitive
$Manifest['ExecutionPriority'] = 4.1525344634;   // Integral part -> group ; Fractional part -> timestamp
$Manifest['Shared'] = 'HCI__MAP__API';
$Manifest['Group'] = 'HCI__MAP__API';
$Manifest['VirtualPath'] = false;
$Manifest['Tag'] = [];
$Manifest['Tag']['Box'] = null; // 'div' is default
$Manifest['Tag']['Title'] = null;
$Manifest['Tag']['Content'] = null;
$Manifest['Tag']['Status'] = null;
$Manifest['Attributes'] = [];
$Manifest['Attributes']['Box'] = []; // array: ['onclick'=>'alert("something");', ...];
$Manifest['Attributes']['Title'] = [];
$Manifest['Attributes']['Content'] = [];
$Manifest['Attributes']['Status'] = [];
$Manifest['Install'] = [];
$Manifest['Install']['DB'] = []; // Module database tables (should be executed within transaction)
// $Manifest['Install']['DB'][0] = [];
// $Manifest['Install']['DB'][0]['Name'] = "Module__HCI__MAP__API__Table1";
// $Manifest['Install']['DB'][0]['Create'] = "create table Module__HCI__MAP__API__Table1 (ID int auto_increment primary key);";
// $Manifest['Install']['DB'][0]['Insert'] = "insert into Module__HCI__MAP__API__Table1 values (1);";
$Manifest['Uninstall'] = [];
$Manifest['Uninstall']['DB'] = [];
// $Manifest['Uninstall']['DB'][0] = "drop table Module__HCI__MAP__API__Table1;";

return $Manifest;