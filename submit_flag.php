<?php
session_start();
if(!isset($_SESSION['username'])) exit;

$level = $_POST['level'] ?? '';
$flag = $_POST['flag'] ?? '';

$correct_flags = [
    'OSINT_1' => 'flag{drake}',
    'OSINT_2' => 'flag{green}',
    'OSINT_3' => 'flag{monospace}',
    'OSINT_4' => 'flag{score}',
    'OSINT_5' => 'flag{ctfcomplete}',
];

if(!isset($correct_flags[$level])) exit;
if($flag !== $correct_flags[$level]) exit;

if(!isset($_SESSION['cleared'])) $_SESSION['cleared'] = [];
if(!in_array($level, $_SESSION['cleared'])) $_SESSION['cleared'][] = $level;

if(!isset($_SESSION['score'])) $_SESSION['score'] = 0;
$_SESSION['score'] += 10;

echo 'success';