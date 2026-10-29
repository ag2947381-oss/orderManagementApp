<?php
declare(strict_types=1);

$ordersFile = '../../orders/orders.json';

if (!file_exists($ordersFile)) {
    http_response_code(404);
    echo "<!DOCTYPE html><html lang='en'><head><title>File Error</title></head><body>";
    echo "<h1>404 Not Found</h1>";
    echo "<p>Error: The orders database file (orders.json) could not be found.</p>";
    echo "<a href='../client/view_menu.html'><button>Back to View Menu</button></a> ";
    echo "<a href='../client/menu.html'><button>Back to Operations Menu</button></a> ";
    echo "<a href='../client/index.html'><button>Back to Home</button></a>";
    echo "</body></html>";
    exit();
}

//Leemos el fichero Json
$fileContent = file_get_contents($ordersFile);

if (empty($fileContent)) {
    $ordersArray = [];
} else {

$ordersArray = json_decode($fileContent, true);
}

http_response_code(200);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Store - All Orders</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
    </head>
    <body>
        <header>
            <h1>🛠️ Hardware Store Management System</h1>
            <h2>List of All Orders</h2>
        </header>

        <main>
            <?php if (empty($ordersArray)) : ?>
                <p>No orders have been placed yet. </p>
                <?php else: ?>
                    // Generamos la tabla html exigida
                    <table>
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Client Name</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Total (inc. VAT)</th>
                            </tr>
                        </thead>
                    <tbody>
                    <?php 
                    foreach ($ordersArray as $order):
                     ?>
                     
                     <tr>
                        <td><?php echo htmlspecialchars((String)$order['orderId']); ?></td>
                        <td><?php echo htmlspecialchars((string)$order['clientName']); ?></td>
                            <td><?php echo htmlspecialchars((string)$order['address']); ?></td>
                            <td><?php echo htmlspecialchars((string)$order['email']); ?></td>
                            <td><?php echo htmlspecialchars((string)$order['phone']); ?></td>
                            <td>€<?php echo number_format((float)$order['totalWithVat'], 2); ?></td>
                     </tr>
                     <?php endforeach; ?>
                    </tbody>
                    </table>
                    <?php endif; ?>


        </main>
    </body>
</html>