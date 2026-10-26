<?php

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';


$usersFilePath = '../../users/users.json';

if (!file_exists($usersFilePath)){
    http_response_code(404);
    echo "<!DOCTYPE html</html> lang='en'><head><title>Error</title></head><body>";
    echo "<h1>404 Not Found</h1>";
    echo "<p>Error: Database file users.json not found.>/p>";
    echo "<a href='../client/index.html'><button>Back to Home</button></a>";
    echo "<body></body></html>";
    exit();
}

$jsonContent = file_get_contents($usersFilePath);
$usersData = json_decode($jsonContent, true);


function authenticateUser(string $inputUser, string $inputPassword, array $userList): bool {
    foreach($userList as $account) {
        
    if ($account['user'] === $inputUser) {
        if(password_verify($inputPassword, $account['password'])){
            return true;
        }
    }    
}
return false;

}

if (authenticateUser($username, $password, $usersData)) {

http_response_code(200);
header("Location: ../client/menu.html");
exit();
} else {

http_response_code(404);
echo "<!DOCTYPE html><html lang='en'><head><title>Authentication Error</title></head><body>";
echo "<h1>Authentication Error</h1>";
echo "<p>Invalid username or password provided.</p>";
echo "<a href='../client/index.html'><button>Back to Home</button></a>";
echo "<a href='../client/login.html'><button>Tru Again</button></a>";
echo "</body></html>";
exit ();
}

?>