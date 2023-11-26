<?php
// Include database connection code here

$servername = "localhost";
$username = "root";
$password = "password";
$database = "jsapi";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8 (optional but recommended)
$conn->set_charset("utf8");


// Function to get all users
function getUsers() {
    global $conn;
    $users = array();

    $sql = "SELECT id, username, email FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    return $users;
}

// Function to add a new user
function addUser($username, $email) {
    global $conn;

    $sql = "INSERT INTO users (username, email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    // Check for errors in the prepared statement
    if (!$stmt) {
        return ['status' => 'error', 'message' => $conn->error];
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("ss", $username, $email);
    $result = $stmt->execute();

    // Check for errors in the execution
    if (!$result) {
        return ['status' => 'error', 'message' => $stmt->error];
    }

    $stmt->close();

    return ['status' => 'success'];
}

// Function to update a user
function updateUser($id, $username, $email) {
    global $conn;

    $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return ['status' => 'error', 'message' => $conn->error];
    }

    $stmt->bind_param("ssi", $username, $email, $id);
    $result = $stmt->execute();

    if (!$result) {
        return ['status' => 'error', 'message' => $stmt->error];
    }

    $stmt->close();

    return ['status' => 'success'];
}

// Function to delete a user
function deleteUser($id) {
    global $conn;

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return ['status' => 'error', 'message' => $conn->error];
    }

    $stmt->bind_param("i", $id);
    $result = $stmt->execute();

    if (!$result) {
        return ['status' => 'error', 'message' => $stmt->error];
    }

    $stmt->close();

    return ['status' => 'success'];
}



// Include database connection code here

// Function to get all products
function getProducts() {
    global $conn;
    $products = array();

    $sql = "SELECT id, product_name, description, price FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    return $products;
}

// Function to add a new product
function addProduct($productName, $description, $price) {
    global $conn;

    $sql = "INSERT INTO products (product_name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return ['status' => 'error', 'message' => $conn->error];
    }

    $stmt->bind_param("ssd", $productName, $description, $price);
    $result = $stmt->execute();

    if (!$result) {
        return ['status' => 'error', 'message' => $stmt->error];
    }

    $stmt->close();

    return ['status' => 'success'];
}

// Function to update a product
function updateProduct($id, $productName, $price) {
    global $conn;

    $sql = "UPDATE products SET product_name = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return ['status' => 'error', 'message' => $conn->error];
    }

    $stmt->bind_param("sdi", $productName, $price, $id);
    $result = $stmt->execute();

    if (!$result) {
        return ['status' => 'error', 'message' => $stmt->error];
    }

    $stmt->close();

    return ['status' => 'success'];
}

// Function to delete a product
function deleteProduct($id) {
    global $conn;

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return ['status' => 'error', 'message' => $conn->error];
    }

    $stmt->bind_param("i", $id);
    $result = $stmt->execute();

    if (!$result) {
        return ['status' => 'error', 'message' => $stmt->error];
    }

    $stmt->close();

    return ['status' => 'success'];
}

// cart ##############

// Function to get user's cart
function getCart($userId) {
    global $conn;

    $cartItems = array();

    $sql = "SELECT c.id, p.product_name, p.price, c.quantity
            FROM cart c
            INNER JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return ['status' => 'error', 'message' => $conn->error];
    }
    $userId =1;
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }
    }

    $stmt->close();

    return $cartItems;
}

// Function to add a product to the user's cart
function addToCart($userId, $productId, $quantity) {
    global $conn;
    $userId=1;
    // Check if the product is already in the cart
    $checkSql = "SELECT id FROM cart WHERE user_id = ? AND product_id = ?";
    $checkStmt = $conn->prepare($checkSql);

    if (!$checkStmt) {
        return ['status' => 'error', 'message' => $conn->error];
    }

    $checkStmt->bind_param("ii", $userId, $productId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Product already in the cart, update the quantity
        $updateSql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $updateStmt = $conn->prepare($updateSql);

        if (!$updateStmt) {
            return ['status' => 'error', 'message' => $conn->error];
        }

        $updateStmt->bind_param("iii", $quantity, $userId, $productId);
        $result = $updateStmt->execute();

        $updateStmt->close();
    } else {
        // Product not in the cart, add a new entry
        $insertSql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);

        if (!$insertStmt) {
            return ['status' => 'error', 'message' => $conn->error];
        }

        $insertStmt->bind_param("iii", $userId, $productId, $quantity);
        $result = $insertStmt->execute();

        $insertStmt->close();
    }

    return $result ? ['status' => 'success'] : ['status' => 'error', 'message' => $conn->error];
}

// Function to update cart item quantity
function updateCartItem($cartItemId, $quantity) {
    global $conn;

    $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return ['status' => 'error', 'message' => $conn->error];
    }

    $stmt->bind_param("ii", $quantity, $cartItemId);
    $result = $stmt->execute();

    $stmt->close();

    return $result ? ['status' => 'success'] : ['status' => 'error', 'message' => $conn->error];
}

// Function to remove a product from the cart
function removeFromCart($cartItemId) {
    global $conn;

    $sql = "DELETE FROM cart WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return ['status' => 'error', 'message' => $conn->error];
    }

    $stmt->bind_param("i", $cartItemId);
    $result = $stmt->execute();

    $stmt->close();

    return $result ? ['status' => 'success'] : ['status' => 'error', 'message' => $conn->error];
}
?>
