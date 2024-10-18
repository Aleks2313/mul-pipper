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
    $pdo = new PDO($dsn, $user, $password, $options); // Use $password here
} catch (\PDOException $e) {
    // Handle connection error
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $content = $_POST['content'];
    $time = date('Y-m-d H:i:s'); // Set current time
    $avatar = $_POST['avatar']; // This should be the avatar data (e.g., a file path or base64 encoded image)

    // Sanitize input data
    $username = htmlspecialchars($username);
    $content = htmlspecialchars($content);
    // For avatar, make sure to handle it properly based on how you're sending it (e.g., file upload, base64, etc.)

    // Prepare SQL query to insert new post
    $statement = $pdo->prepare("INSERT INTO posts (username, content, time, avatar) VALUES (?, ?, ?, ?)");
    $statement->execute([$username, $content, $time, $avatar]);

    // Return a success message as JSON
    echo json_encode(['message' => 'Post added successfully']);
    exit();
}

// Handle GET request (fetch all posts)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Fetch all posts sorted by newest first
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY time DESC");
    $posts = $stmt->fetchAll();

    // Return posts as JSON
    echo json_encode($posts);
    exit();
}

// Handle GET request (fetch all posts)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Fetch all posts sorted by newest first
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY time DESC");
    $posts = $stmt->fetchAll();

    // Display posts dynamically
    foreach ($posts as $post) {
        echo "
        <div class='post'>
            <img src='{$post['avatar']}' class='avatar' alt='Avatar'>
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