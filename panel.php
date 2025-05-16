<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$file = "keys.json";

if (!file_exists($file)) {
    file_put_contents($file, "[]");
}

if (isset($_POST['generate'])) {
    $new_key = strtoupper(bin2hex(random_bytes(4)));
    $expiry = $_POST['expiry'];
    
    $keys = json_decode(file_get_contents($file), true);
    $keys[] = ["key" => $new_key, "expiry" => $expiry];
    file_put_contents($file, json_encode($keys, JSON_PRETTY_PRINT));
}

$keys = json_decode(file_get_contents($file), true);
?>
<h2>Key Generator Panel</h2>
<form method="POST">
    <label>Expiry (YYYY-MM-DD HH:MM):</label>
    <input type="datetime-local" name="expiry" required>
    <button name="generate">Generate Key</button>
</form>

<h3>All Generated Keys</h3>
<table border="1">
    <tr><th>Key</th><th>Expiry</th></tr>
    <?php foreach ($keys as $k): ?>
    <tr>
        <td><?= htmlspecialchars($k['key']) ?></td>
        <td><?= htmlspecialchars($k['expiry']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
