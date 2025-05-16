<?php
$ip = $_POST['ip'] ?? '192.168.1.116';
$port = $_POST['port'] ?? 9090;
$hexInput = $_POST['hex'] ?? '';
$output = '';

if (!empty($hexInput)) {
    $message = hex2bin($hexInput);

    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if (!$sock) {
        $output = "Erreur socket : " . socket_strerror(socket_last_error());
    } elseif (!socket_connect($sock, $ip, (int)$port)) {
        $output = "Erreur connexion : " . socket_strerror(socket_last_error($sock));
    } else {
        socket_send($sock, $message, strlen($message), 0);
        $output = "Connecté au serveur\n";

        // Réception boucle simple
        while (true) {
            $buf = '';
            $bytes = @socket_recv($sock, $buf, 1024, MSG_DONTWAIT);
            if ($bytes > 0) {
                $output .= strtoupper(bin2hex($buf)) . "\n";
            }
            usleep(200000); // 200 ms pour ne pas surcharger
            if (connection_aborted()) break;
        }

        socket_close($sock);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Client RFID TCP</title>
  <style>
    body { font-family: Arial; background: #f0f8ff; padding: 20px; }
    textarea { width: 100%; height: 300px; margin-top: 10px; font-family: monospace; }
    input, button { margin: 5px 0; padding: 8px; }
    .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
  </style>
</head>
<body>
<div class="container">
  <h2>Client RFID TCP</h2>
  <form method="post">
    <label>IP :</label>
    <input type="text" name="ip" value="<?= htmlspecialchars($ip) ?>"><br>
    <label>Port :</label>
    <input type="number" name="port" value="<?= htmlspecialchars($port) ?>"><br>
    <label>Trame hexadécimale :</label>
    <input type="text" name="hex" value="<?= htmlspecialchars($hexInput) ?>"><br>
    <button type="submit">Connexion + Envoi</button>
  </form>

  <label>Trames reçues :</label>
  <textarea readonly><?= htmlspecialchars($output) ?></textarea>
</div>
</body>
</html>
