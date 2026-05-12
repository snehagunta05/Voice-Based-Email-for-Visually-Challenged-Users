<?php
// backend.php
header("Content-Type: application/json");

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "voice_email";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die(json_encode(["response" => "Database connection failed."]));
}

// Get command from frontend
$data = json_decode(file_get_contents("php://input"), true);
$command = strtolower($data["command"]);

// Simple command handling
$response = "";

if (strpos($command, "compose") !== false) {
    $response = "Please dictate your email content.";
} elseif (strpos($command, "inbox") !== false) {
    $result = $conn->query("SELECT subject FROM inbox LIMIT 5");
    $emails = [];
    while ($row = $result->fetch_assoc()) {
        $emails[] = $row["subject"];
    }
    $response = "Your inbox contains: " . implode(", ", $emails);
} elseif (strpos($command, "delete") !== false) {
    $conn->query("DELETE FROM inbox LIMIT 1");
    $response = "Deleted the latest email.";
} else {
    $response = "Command not recognized. Try saying Inbox, Compose, or Delete.";
}

echo json_encode(["response" => $response]);
?>
