<?php
include 'includes/db.php';
$query = $_GET['query'];
$sql = "SELECT DISTINCT city FROM property WHERE city LIKE '%$query%' LIMIT 10";
$result = $conn->query($sql);

$locations = [];
while ($row = $result->fetch_assoc()) {
    $locations[] = $row['city'];
}
echo json_encode($locations);
?>
