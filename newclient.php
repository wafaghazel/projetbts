<?php
// Initialisation des variables
$mode = $_POST['mode'] ?? 'client';
$protocol = 'tcp'; // UDP supprimé, forcé à TCP
$ip = $_POST['ip'] ?? '192.168.1.100';
$port = $_POST['port'] ?? 12345;
$hexMode = isset($_POST['hex']);
$messageInput = $_POST['message'] ?? '';
$output = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mode === 'client') {
    $message = $hexMode ? hex2bin($messageInput) : $messageInput;

    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if (!$sock) {
        $output = "Erreur socket : " . socket_strerror(socket_last_error());
    } elseif (!socket_connect($sock, $ip, (int)$port)) {
        $output = "Erreur connexion : " . socket_strerror(socket_last_error($sock));
    } else {
        socket_send($sock, $message, strlen($message), 0);
        $output .= "Connecté au serveur\n";

        $buf = '';
        $bytes = socket_recv($sock, $buf, 1024, MSG_WAITALL);
        if ($bytes > 0) {
            $output .= $hexMode ? strtoupper(bin2hex($buf)) : $buf;
        } else {
            $output .= "Aucune donnée reçue.";
        }
        socket_close($sock);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Client et Serveur V5 (Web)</title>
  <style>
    body { font-family: Arial; background: #f1f1f1; padding: 20px; }
    .box { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 0 10px #ccc; }
    input, button, textarea { width: 100%; padding: 8px; margin-top: 8px; }
    label { display: block; margin-top: 10px; }
    .inline { display: inline-block; margin-right: 10px; }
  </style>
</head>
<body>
<div class="box">
  <h3>Client RFID</h3>
  <form method="post">
    <div>
      <label class="inline"><input type="radio" name="protocol" value="tcp" checked disabled> TCP</label>
      <label class="inline"><input type="checkbox" name="hex" <?= $hexMode ? 'checked' : '' ?>> Mode hexadécimal</label>
    </div>
    <div>
      <label class="inline"><input type="radio" name="mode" value="serveur" disabled> Serveur</label>
      <label class="inline"><input type="radio" name="mode" value="client" checked> Client</label>
    </div>
    <label>IP</label>
    <input type="text" name="ip" value="<?= htmlspecialchars($ip) ?>">
    <label>Port</label>
    <input type="number" name="port" value="<?= htmlspecialchars($port) ?>">
    <label>Votre message...</label>
    <input type="text" name="message" value="<?= htmlspecialchars($messageInput) ?>">
    <button type="submit">Connexion</button>
    <button type="submit">Envoi</button>
  </form>
  <label>Réponse :</label>
  <textarea readonly rows="10"><?= htmlspecialchars($output) ?></textarea>
</div>
</body>
</html>
