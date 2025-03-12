$(document).ready(function() {
    let selectedProductCode;

    // Show the quantity modal when an item is clicked
    $('.add-to-order').click(function() {
        selectedProductCode = $(this).data('code');
        $('#quantityModal').css('display', 'block');
    });

    // Close the modal
    $('.close').click(function() {
        $('#quantityModal').css('display', 'none');
    });

    // Handle numeric keypad button clicks
    $('.num-btn').click(function() {
        const value = $(this).data('value');
        const currentInput = $('#quantityInput').val();
        $('#quantityInput').val(currentInput + value);
    });

    // Clear the quantity input
    $('.clear-btn').click(function() {
        $('#quantityInput').val('');
    });

    // Confirm quantity and add to order
    $('#confirmQuantity').click(function() {
        const quantity = parseInt($('#quantityInput').val());
        if (quantity > 0) {
            addItemToOrder(selectedProductCode, quantity);
            $('#quantityModal').css('display', 'none');
        } else {
            alert('Please enter a valid quantity.');
        }
    });

    // Function to add item to order
    function addItemToOrder(productCode, quantity) {
        let row = $('#orderTableBody').find(`tr[data-code="${productCode}"]`);
        const productName = $(`button[data-code="${productCode}"]`).data('name');
        const price = parseFloat($(`button[data-code="${productCode}"]`).data('price'));

        if (row.length > 0) {
            // Update existing row
            let qtyInput = row.find('.qty-input');
            let newQty = parseInt(qtyInput.val()) + quantity;
            qtyInput.val(newQty);
            row.find('.subtotal').text('₱ ' + (newQty * price).toFixed(2));
        } else {
            // Add new row
            const newRow = `
                <tr data-code="${productCode}">
                    <td>${productCode}</td>
                    <td>${productName}</td>
                    <td>₱ ${price.toFixed(2)}</td>
                    <td><input type="number" class="qty-input" value="${quantity}" min="1" /></td>
                    <td class="subtotal">₱ ${(price * quantity).toFixed(2)}</td>
                    <td><button class="btn btn-danger remove-product">Remove</button></td>
                </tr>
            `;
            $('#orderTableBody').append(newRow);
        }
        updateTotal();
    }

    // Function to update total
    function updateTotal() {
        let total = 0;
        $('#orderTableBody .subtotal').each(function() {
            total += parseFloat($(this).text().replace('₱ ', '').replace(',', ''));
        });
        const discount = parseFloat($('#discount').val()) || 0;
        total -= discount;

        const vat = total * 0.12; // Calculate VAT (12%)
        const grandTotal = total + vat;

        $('#totalValue').text('₱ ' + grandTotal.toFixed(2));
        $('#vatValue').text('₱ ' + vat.toFixed(2));
        $('#taxValue').text('₱ ' + (grandTotal - total).toFixed(2)); // Assuming tax is the same as VAT for this example
    }

    // Remove product from order
    $(document).on('click', '.remove-product', function() {
        $(this).closest('tr').remove();
        updateTotal();
    });

  // Finish Order Handler
$('.finish-order').click(function() {
    const orderItems = [];
    $('#orderTableBody tr').each(function() {
        const productCode = $(this).data('code');
        const productName = $(this).find('td:nth-child(2)').text();
        const price = parseFloat($(this).find('td:nth-child(3)').text().replace('₱ ', '').replace(',', ''));
        const quantity = parseInt($(this).find('.qty-input').val());
        orderItems.push({ productCode, productName, price, quantity });
    });

    if (orderItems.length === 0) {
        alert('No products added to the order.');
        return;
    }

    // Prepare parameters for the AJAX request
    const customerId = $('#customer_search').data('customerId'); // Assuming you have a customer ID

    // Create the data object to send
    const orderData = {
        customer_id: customerId,
        items: orderItems
    };

    // Send the order data via AJAX
    $.ajax({
        url: 'make_oder.php', // The server-side script to handle the order
        type: 'POST',
        data: JSON.stringify(orderData), // Convert the data to JSON
        contentType: 'application/json', // Set the content type to JSON
        success: function(response) {
            // Handle success response
            alert('Order processed successfully!');
            // Optionally, you can redirect or update the UI here
        },
        error: function(xhr, status, error) {
            // Handle error response
            alert('An error occurred while processing the order: ' + error);
        }
    });
});
});