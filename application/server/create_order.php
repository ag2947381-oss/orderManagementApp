<?php

// Olbiga a php a ser estricto con los tipos de datos 
// ( Evita sumar letras con numeros )
declare(strict_types=1);

// recibimos los datos del metodo POST
// usamos isset para comprobar que una variable existe
// Y que no esta vacia para recivir los datos de forma segura
$orderId = isset($_POST['orderId']) ? $_POST['orderId'] : '';
$clientName = isset($_POST['clientName']) ? $_POST['clientId'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';


//recibimos los productos, Si no hay, asignamos un array vacio 
$selectProducts = isset($_POST['products']) ? $_POST['products'] : [];

// Los productos de la ferreteria con sus precios
$catalogoPrices = [
    'hammer' => 15.50,
    'drill' => 45.00,
    'screws' => 5.20,
    'nails' => 3.50
];

//retorno de funciones con declaracion
// le decimos a la funcion a devolver un numero decimal => Float
function calculateTotalWithVat(array $products, array $prices) : float {
    $subtotal = 0.0;

    // iteracion a traves del array asociativo usando foreach
    foreach ($products as $key => $details) {
        
    // vefificamos si el checbox 'active ' fue marcado enviando el valor 1
    if (isset($details['active']) && $details['active'] == '1') {

    // hacemos un casting la convercion a tipo int 
    $qty = isset($details['quantity']) ? (int)$details['quantity'] : 0;
    
    if ($qty > 0 && isset($prices[$key])) {
        $subtotal += $prices[$key] * $qty;
            }
        }
    
    }
    // aplicacion del iva del 21%
    $totalVat = $subtotal *1.21;
    return round($totalVat, 2);
    }

    //ejecucion de la funcion calculate....
    $finalPrice = calculateTotalWithVat($selectProducts, $catalogoPrices);

    // manipulamos los archivos
    $ordersFile = '../../orders/orders.json';
    $ordersArray = [];

    if (file_exists($ordersFile)){
        $fileContent = file_get_contents($ordersFile);
        if (!empty($fileContent)) {
    // la funcion json_decode analiza la cadena y la convierte en datos nativos
    // True fuerza la creacion de un array asociativo miltidimensional
        $ordersArray = json_decode($fileContent, true);
        }
    }

    // creacion de la comanda ( con un array asociativo)
    $newOrder = [
        'orderId' => $orderId,
        'clientName' => $clientName,
        'address' => $address,
        'email' => $email,
        'phone' => $phone,
        'purchasedProducts' => $selectProducts,
        'totalWithVat' => $finalPrice
    ];

    //insercion de la comanda en la estructura goblal
    $ordersArray[] = $newOrder;

    // serializacion de los datos a una cadena json
    $jsonOutput = json_encode($ordersArray, JSON_PRETTY_PRINT);

    // escritura en el fichero de almacenamiento permanente
    $saveResult = file_put_contents($ordersFile, $jsonOutput);
 
    // control de errores: fichero no encontrado o sin permisos
    if ($saveResult === false) {

    // si php no encuentra o no puede abrir order.json genera un 404
    http_response_code(404);

    echo "<!DOCTYPE html><html lang='en'><head><tittle>File error</tittle></head><body>";
    echo "<h1>404 Not Found - File Error</h1>";
    echo "<p>Error: Cannot create or write to the orders.json file.</p>";
    echo "<a href='../client/view_menu.html'><button>Back to View Orders</button></a> ";
    echo "<a href='../client/menu.html'><button>Back to Operations Menu</button></a> ";
    echo "<a href='../client/index.html'><button>Back to Home</button></a>";
    echo "</body></html>";
    exit();
}

    http_response_code(200);
    echo "<!DOCTYPE html><html lang='en'><head><title>Order Success</title></head><body>";
echo "<h1>Order Successfully Created</h1>";
echo "<p><strong>Order ID:</strong> " . htmlspecialchars($orderId) . "</p>";
echo "<p><strong>Client:</strong> " . htmlspecialchars($clientName) . "</p>";
echo "<p><strong>Total Price (including 21% VAT):</strong> €" . number_format($finalPrice, 2) . "</p>";
echo "<br>";
// Botones de navegación exigidos para retornar al menú de operaciones y al inicio[cite: 5].
echo "<a href='../client/menu.html'><button>Back to Operations Menu</button></a> ";
echo "<a href='../client/index.html'><button>Back to Home</button></a>";
echo "</body></html>";
exit();
?>