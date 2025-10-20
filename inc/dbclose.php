<?php
// تأكد أن $result موجود وقابل للتفريغ
if (isset($result) && $result instanceof mysqli_result) {
    mysqli_free_result($result);
}

// إغلاق الاتصال فقط لو كان موجود
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>

