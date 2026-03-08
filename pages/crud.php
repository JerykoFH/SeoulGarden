<?php
session_start();
include('../config/db.php');

// Handle delete
if (isset($_GET['delete'])) {
    $table = $_GET['table'];
    $id = $_GET['delete'];
    $primary_key = $_GET['pk'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE $primary_key = ?");
        $stmt->execute([$id]);
        
        $_SESSION['message'] = "Record deleted successfully";
        $_SESSION['msg_type'] = "success";
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
    
    header("location: $table.php");
    exit();
}

if (isset($_POST['save'])) {
    $table = $_GET['table'];
    $columns = [];
    $values = [];
    
    // Prepare data
    foreach ($_POST as $key => $value) {
        if ($key != 'save') {
            $columns[] = $key;
            $values[] = sanitize($value);
        }
    }
    
    // Build query
    $columnList = implode(', ', $columns);
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    
    try {
        $stmt = $pdo->prepare("INSERT INTO $table ($columnList) VALUES ($placeholders)");
        $stmt->execute($values);
        
        $_SESSION['message'] = "Record created successfully";
        $_SESSION['msg_type'] = "success";
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
    
    header("location: $table.php");
    exit();
}

if (isset($_POST['update'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];
    $primary_key = $_GET['pk'];
    $updates = [];
    $values = [];
    
    // Prepare data
    foreach ($_POST as $key => $value) {
        if ($key != 'update') {
            $updates[] = "$key = ?";
            $values[] = sanitize($value);
        }
    }
    $values[] = $id;
    
    // Build query
    $updateList = implode(', ', $updates);
    
    try {
        $stmt = $pdo->prepare("UPDATE $table SET $updateList WHERE $primary_key = ?");
        $stmt->execute($values);
        
        $_SESSION['message'] = "Record updated successfully";
        $_SESSION['msg_type'] = "success";
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }
    
    header("location: $table.php");
    exit();
}
?>