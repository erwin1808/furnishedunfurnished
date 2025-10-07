<?php
include "../includes/db.php";

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing image ID']);
    exit;
}

$id = intval($_GET['id']);

// Get image path from DB
$stmt = $conn->prepare("SELECT image_path, property_code FROM property_images WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$image = $result->fetch_assoc();
$stmt->close();

if (!$image) {
    echo json_encode(['success' => false, 'message' => 'Image not found']);
    exit;
}

// Delete from DB
$stmt = $conn->prepare("DELETE FROM property_images WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    // Delete physical file
    if (file_exists($image['image_path'])) {
        unlink($image['image_path']);
    }
    echo json_encode(['success' => true, 'property_code' => $image['property_code']]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}
$stmt->close();
$conn->close();
