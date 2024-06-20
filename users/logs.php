function log_user_action($conn, $user_id, $action, $changed_by) {
    $stmt = $conn->prepare("INSERT INTO user_logs (user_id, action, changed_by) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $action, $changed_by);
    $stmt->execute();
    $stmt->close();
}
