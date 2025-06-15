<?php
require 'db.php';

$q = $_GET['q'] ?? '';

if ($q) {
    $q = $conn->real_escape_string($q);
    $sql = "SELECT * FROM imagens WHERE nome_original LIKE '%$q%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM imagens ORDER BY id DESC";
}

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<p>" . htmlspecialchars($row['nome_original']) . "</p>";
}
?>
