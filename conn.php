<?php
session_start();
    $host   = 'lsql100.infinityfree.com';
    $db     = 'if0_41661234_pcrs';
    $user   = 'if0_41661234';
    $pass   = 'Watermelon109';

    try{
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e){
        die("Connection failed: " . $e->getMessage());
    }
?>