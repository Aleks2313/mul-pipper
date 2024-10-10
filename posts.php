<?php

require "./.env";

// Database connection details
$host = 'localhost:3306';
$db   = 'pipper';                // Your database name
$user = 'root';                  // Your database user
$password = getenv('PASSWORD');  // Your database password
$charset = 'utf8mb4';

// Set up DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [                                                    //detailed error messaging
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Create a new PDO instance (database connection)
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Handle connection error
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Handle POST request (for new posts)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $content = $_POST['content'];

    // Sanitize input data
    $username = htmlspecialchars($username);
    $content = htmlspecialchars($content);

    // Prepare SQL query to insert new post
    $statement = $pdo->prepare("INSERT INTO pipper.posts VALUES(username, content) VALUES (?, ?)");
    $statement->execute([$username, $content]);

    echo 'Post added successfully';
    exit();
}

// Handle GET request (fetch all posts)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Fetch all posts sorted by newest first
    $stmt = $pdo->query("SELECT * FROM pipper.posts ORDER BY time DESC");
    $posts = $stmt->fetchAll();

    // Display posts dynamically
foreach ($posts as $post) {
    echo "
    <div class='post'>
        <img src='avatar.png' class='avatar' alt='Avatar'>
        <div>
            <span class='username'>{$post['username']}</span>
            <span class='time'>{$post['time']}</span>
            <div class='content'>{$post['content']}</div>
        </div>
    </div>
    ";
    }
}
?>