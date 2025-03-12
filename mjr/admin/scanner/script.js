function onScanSuccess(decodeText, decodeResult) {
    const tableBody = document.getElementById("scan-results").getElementsByTagName("tbody")[0];
    const newRow = tableBody.insertRow();
    const codeCell = newRow.insertCell(0);
    const timestampCell = newRow.insertCell(1);

    codeCell.textContent = decodeText;
    timestampCell.textContent = new Date().toLocaleString();

    // Make an AJAX request to get product details using the scanned product code
    fetch(`get_product_info.php?prod_code=${encodeURIComponent(decodeText)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) { // Check if the response indicates success
                // Display product details in the modal
                const productInfo = `
                    <strong>Product Name:</strong> ${data.product.prod_name}<br>
                    <strong>Price:</strong> ${data.product.prod_price}<br>
                    <strong>Description:</strong> ${data.product.prod_desc}<br>
                    <strong>Stock:</strong> ${data.product.prod_stock}<br>
                    <strong>Barcode:</strong> ${data.product.prod_barcode}
                `;
                document.getElementById("productInfo").innerHTML = productInfo;
                modal.style.display = "block"; // Show the modal
            } else {
                alert(data.message); // Show error message if product not found
            }
        })
        .catch(error => {
            console.error('Error fetching product:', error);
            alert('Failed to fetch product details. Please try again.');
        });
}