$(document).ready(function() {
    // Function to fetch and display users
    function loadUsers() {
        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: { operation: 'get_users' },
            dataType: 'json',
            success: function(response) {
                if (response.length > 0) {
                    var userList = $('#user-list');
                    userList.empty();
                    response.forEach(function(user) {
                        userList.append('<li>' + user.username + ' - ' + user.email + '</li>');
                    });
                }
            }
        });
    }

    // Load users on page load
    loadUsers();

    // Submit form to add a new user
    $('#add-user-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: $(this).serialize() + '&operation=add_user',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    loadUsers(); // Reload user list after adding a new user
                    $('#add-user-form')[0].reset(); // Clear the form
                    console.log('User created with...');
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    });

    // Add code for update and delete operations if needed
});




// 

$(document).ready(function() {
    // Function to fetch and display products
    function loadProducts() {
        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: { operation: 'get_products' },
            dataType: 'json',
            success: function(response) {
                if (response.length > 0) {
                    var productList = $('#product-list');
                    productList.empty();
                    response.forEach(function(product) {
                        productList.append('<li>' + product.id + ' - ' + product.product_name + ' - ' + product.description +' - INR ' + product.price + '.00 '+ '<button class='+'cartadd'+'> Add to Cart</button>'+'</li>');
                    });
                }
            }
        });
    }

    // Load products on page load
    loadProducts();

    // Submit form to add a new product
    $('#add-product-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: $(this).serialize() + '&operation=add_product',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    loadProducts(); // Reload product list after adding a new product
                    $('#add-product-form')[0].reset(); // Clear the form
                    console.log('product added with...')
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    });

    // Add code for update and delete operations if needed
});




// 


$(document).ready(function() {
    // Function to fetch and display cart items
    function loadCart() {
        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: { operation: 'get_cart' },
            dataType: 'json',
            success: function(response) {
                if (response.length > 0) {
                    var cartList = $('#cart-list');
                    cartList.empty();
                    response.forEach(function(cartItem) {
                        cartList.append('<li>' + cartItem.product_name + ' - INR ' + cartItem.price + '.00 - Quantity: ' + cartItem.quantity +
                            ' <button class="update-cart-item" data-id="' + cartItem.id + '">Update Quantity</button>' +
                            ' <button class="remove-from-cart" data-id="' + cartItem.id + '">Remove from Cart</button></li>');
                    });
                }
            }
        });
    }

    // Load cart items on page load
    loadCart();

    // Submit form to add a product to the cart
    $('#add-to-cart-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: $(this).serialize() + '&operation=add_to_cart',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    loadCart(); // Reload cart items after adding a product to the cart
                    $('#add-to-cart-form')[0].reset(); // Clear the form
                    console.log('item added to cart')
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    });

    // Update cart item quantity
    $(document).on('click', '.update-cart-item', function() {
        var cartItemId = $(this).data('id');
        var newQuantity = prompt('Enter new quantity:');
        if (newQuantity !== null) {
            $.ajax({
                url: 'ajax.php',
                method: 'POST',
                data: { operation: 'update_cart_item', id: cartItemId, quantity: newQuantity },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        loadCart(); // Reload cart items after updating quantity
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        }
    });

    // Remove product from the cart
    $(document).on('click', '.remove-from-cart', function() {
        var cartItemId = $(this).data('id');
        if (confirm('Are you sure you want to remove this item from the cart?')) {
            $.ajax({
                url: 'ajax.php',
                method: 'POST',
                data: { operation: 'remove_from_cart', id: cartItemId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        loadCart(); // Reload cart items after removing from cart
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        }
    });

    // Add code for update and delete operations if needed
});
