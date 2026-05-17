<?php
include "../includes/db_connect.php";

echo "<!DOCTYPE html><html><head><title>Search/View Request</title><style>";
echo "body { font-family: 'Inter', sans-serif; background: #f4f7f6; padding: 40px; text-align: center; }";
echo ".card { background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 20px; margin: 15px auto; box-shadow: 0 4px 10px rgba(0,0,0,0.03); max-width: 600px; line-height: 1.8; text-align: left; }";
echo "span.status { color: #3498db; font-weight: 600; }";
echo "a { color: #3498db; text-decoration: none; font-weight: bold; }";
echo "a:hover { text-decoration: underline; }";
echo "</style></head><body>";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT r.request_id, r.title, r.description, r.status, u.name FROM request r JOIN users u ON r.requester_person_id = u.id WHERE r.request_id = ?");
    $stmt->bind_param("i", $id);
} else if (isset($_GET['keyword'])) {
    $keyword = "%" . $_GET['keyword'] . "%";
    $stmt = $conn->prepare("SELECT r.request_id, r.title, r.description, r.status, u.name FROM request r JOIN users u ON r.requester_person_id = u.id WHERE LOWER(r.title) LIKE LOWER(?)");
    $stmt->bind_param("s", $keyword);
} else {
    die("Invalid request.");
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card'>";
        echo "<strong>ID:</strong> #" . $row['request_id'] . "<br>";
        echo "<strong>Student:</strong> " . htmlspecialchars($row['name']) . "<br>";
        echo "<strong>Title:</strong> " . htmlspecialchars($row['title']) . "<br>";
        echo "<strong>Description:</strong> " . nl2br(htmlspecialchars($row['description'])) . "<br>";
        echo "<strong>Status:</strong> <span class='status'>" . htmlspecialchars($row['status']) . "</span><br><br>";
        echo "<a href='#' onclick='window.close();'>Close Window</a>";
        echo "</div>";
    }
} else {
    echo "<div class='card'>No requests found.</div>";
}
echo "</body></html>";
?>
