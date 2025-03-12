<?php
include('config/config.php');

if (isset($_GET['q'])) {
    $searchTerm = $_GET['q'];
    $stmt = $mysqli->prepare("SELECT * FROM rpos_products WHERE prod_name LIKE ? OR prod_code LIKE ? LIMIT 10");
    $searchTerm = "%$searchTerm%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = '';
    $products = '';

    while ($row = $result->fetch_assoc()) {
        $suggestions .= "<div class='suggestion-item' onclick='selectSuggestion(\"" . htmlspecialchars($row['prod_name']) . "\")'>" . htmlspecialchars($row['prod_name']) . "</div>";
        
        // Prepare product table rows
        $products .= "<tr>
                        <td><img src='../admin/assets/img/products/" . ($row['prod_img'] ? $row['prod_img'] : 'default.jpg') . "' height='150' width='125' class='img-thumbnail'></td>
                        <td>" . htmlspecialchars($row['prod_code']) . "</td>
                        <td>" . htmlspecialchars($row['prod_name']) . "</td>
                        <td> ₱" . number_format((float)str_replace(',', '', $row['prod_price']), 2) . "</td>
                        <td>" . htmlspecialchars($row['prod_stock']) . " pcs</td>
                        <td>
                            " . ($row['prod_stock'] > 0 ? 
                                "<form action='make_order.php' method='GET' class='d-flex justify-content-center'>
                                    <input type='hidden' name='prod_id' value='" . $row['prod_id'] . "'>
                                    <input type='hidden' name='prod_name' value='" . $row['prod_name'] . "'>
                                    <input type='hidden' name='prod_price' value='" . $row['prod_price'] . "'>
                                    <input type='hidden' name='quantity' value='1'> <!-- Fixed quantity -->
                                    <button type='submit' class='btn btn-danger'>
                                        <i class='fas fa-cart-plus'></i>
                                        Order Now
                                    </button>
                                </form>" : 
                                "<button class='btn btn-warning' disabled>
                                    <i class='fas fa-times'></i>
                                    Out of Stock
                                </button>") . "
                        </td>
                    </tr>";
    }

    echo json_encode(['suggestions' => $suggestions, 'products' => $products]);
}
?>
