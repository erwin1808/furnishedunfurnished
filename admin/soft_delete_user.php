<?php
// delete_user.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id']; // Get the user ID from the POST data

    include 'includes/db.php';
    include 'includes/functions.php';

    // Soft delete: Set is_deleted to 1
    $query = "DELETE FROM users WHERE userid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'User soft-deleted successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to soft delete the user.'
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
