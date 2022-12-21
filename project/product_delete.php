<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    // delete query
    $query_order_list = "SELECT * FROM order_detail WHERE product_id = ?";
    $stmt_order_list = $con->prepare($query_order_list);
    $stmt_order_list->bindParam(1, $id);
    $stmt_order_list->execute();
    $num = $stmt_order_list->rowCount();
    if ($num > 0) {
        header('Location: customer_read.php?action=cancel');
    } else {
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            // redirect to read records page and
            // tell the user record was deleted
            $query_image = "SELECT image FROM products WHERE id = ?";
            $stmt_image = $con->prepare($query_image);
            $stmt_image->bindParam(1, $id);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->execute();
            unlink($row[$image]);
            
            header('Location: product_read.php?action=deleted');

        } else {
            die('Unable to delete record.');
        }
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
