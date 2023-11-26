<?php



// Include CRUD functions
include "crud_functions.php";

// Check if the request is an AJAX request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // Check the requested operation
    if (isset($_POST['operation'])) {
        switch ($_POST['operation']) {
            case 'get_users':
                $users = getUsers();
                echo json_encode($users);
                break;

            case 'add_user':
                if (isset($_POST['username']) && isset($_POST['email'])) {
                    $response = addUser($_POST['username'], $_POST['email']);
                    echo json_encode($response);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
                }
                break;

            case 'update_user':
                if (isset($_POST['id']) && isset($_POST['username']) && isset($_POST['email'])) {
                    $response = updateUser($_POST['id'], $_POST['username'], $_POST['email']);
                    echo json_encode($response);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
                }
                break;

            case 'delete_user':
                if (isset($_POST['id'])) {
                    $response = deleteUser($_POST['id']);
                    echo json_encode($response);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
                }
                break;

                            case 'get_products':
                $products = getProducts();
                echo json_encode($products);
                break;

            case 'add_product':
                if (isset($_POST['product_name'])  && isset($_POST['description']) && isset($_POST['price'])) {
                    $response = addProduct($_POST['product_name'], $_POST['description'],$_POST['price']);
                    echo json_encode($response);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
                }
                break;

            case 'update_product':
                if (isset($_POST['id']) && isset($_POST['product_name']) && isset($_POST['price'])) {
                    $response = updateProduct($_POST['id'], $_POST['product_name'], $_POST['price']);
                    echo json_encode($response);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
                }
                break;

            case 'delete_product':
                if (isset($_POST['id'])) {
                    $response = deleteProduct($_POST['id']);
                    echo json_encode($response);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
                }
                break;

             case 'get_cart':
                // Assuming you have a way to identify the current user (e.g., session)
                $userId = 1; // Replace with the actual user ID
                $cart = getCart($userId);
                echo json_encode($cart);
                break;

            case 'add_to_cart':
                // Assuming you have a way to identify the current user (e.g., session)
                $userId = 1; // Replace with the actual user ID
                if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
                    $response = addToCart($userId, $_POST['product_id'], $_POST['quantity']);
                    echo json_encode($response);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
                }
                break;

            case 'update_cart_item':
                if (isset($_POST['id']) && isset($_POST['quantity'])) {
                    $response = updateCartItem($_POST['id'], $_POST['quantity']);
                    echo json_encode($response);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
                }
                break;

            case 'remove_from_cart':
                if (isset($_POST['id'])) {
                    $response = removeFromCart($_POST['id']);
                    echo json_encode($response);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
                }
                break;

            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid operation']);
        }
    }

}


