<?php

//CONNEXION A LA BDD
$pdo = new PDO('mysql:host=localhost;dbname=gestiontel;charset=utf8','wafa','wafa');

//ANALYSER L'URL
$url=$_SERVER['PATH_INFO'] ?? '/';
//echo $url;
$tab_url=explode('/',$url);
//echo "<pre>";print_r($tab_url);echo "</pre>";

//EXTRAIRE LA METHODE
$methode=$_SERVER['REQUEST_METHOD'];
//echo $methode;

// GET -> recuperer des donnees de la bdd

// POST -> ajouter des donnees dans la bdd

// PUT -> modifier des donnees de la bdd

// TABLE CIBLE
$table = $tab_url[1];  // ?? '';

// ID (si présent)
$id = isset($tab_url[2]) ? intval($tab_url[2]) : null; // if else sur la meme ligne, très condensé

//DEFINIR LA CLE PRIMAIRE
$primarykey = "id_" . $table;

// FONCTIONS CRUD SIMPLIFIÉES
if ($methode == 'GET') {
    //$requete = "SELECT * FROM $table WHERE $primarykey = ?";
    //echo $requete;
    $stmt = $id ? $pdo->prepare("SELECT * FROM $table WHERE $primarykey = ?") : $pdo->prepare("SELECT * FROM $table");
    $stmt->execute($id ? [$id] : []);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($methode == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $cols = implode(',', array_keys($data));
    $vals = implode(',', array_fill(0, count($data), '?'));
    $stmt = $pdo->prepare("INSERT INTO $table ($cols) VALUES ($vals)");
    echo json_encode(['success' => $stmt->execute(array_values($data))]);
}

if ($methode == 'PUT' && $id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $updates = implode('=?, ', array_keys($data)) . '=?';
    $stmt = $pdo->prepare("UPDATE $table SET $updates WHERE $primarykey = ?");
    echo json_encode(['success' => $stmt->execute([...array_values($data), $id])]);
}

if ($methode == 'DELETE' && $id) {
    $stmt = $pdo->prepare("DELETE FROM $table WHERE $primarykey = ?");
    $stmt->execute($id ? [$id] : []);
    echo json_encode(['success' => $stmt->execute([$id])]);
}


?>
