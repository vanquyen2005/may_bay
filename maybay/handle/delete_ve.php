<?php
require_once('../functions/db_connection.php');
$conn = getDbConnection();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM ve WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header('Location: ../admin/ve.php');
        exit;
    } else {
        echo "Lỗi khi xóa vé: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>
