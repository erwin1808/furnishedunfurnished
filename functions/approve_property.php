<?php
include '../includes/db.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $updateQuery = "UPDATE property SET is_approve = 1 WHERE intake_id = $id";
    
    if (mysqli_query($conn, $updateQuery)) {
        echo json_encode(['status' => 'success', 'message' => 'Property approved successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to approve property.']);
    }
}
?>
