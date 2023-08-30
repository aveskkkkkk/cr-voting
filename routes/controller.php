<?php
// Establish a database connection
$connection = mysqli_connect('localhost', 'username', 'password', 'dbname');

// Handle CRUD Operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create
    if (isset($_POST['add_candidate'])) {
        $candidateName = $_POST['candidate_name'];
        $insertQuery = "INSERT INTO candidates (name) VALUES ('$candidateName')";
        mysqli_query($connection, $insertQuery);
    }

    // Vote
    if (isset($_POST['vote'])) {
        $candidateId = $_POST['candidate_id'];
        $updateVotesQuery = "UPDATE candidates SET votes = votes + 1 WHERE id = $candidateId";
        mysqli_query($connection, $updateVotesQuery);
    }
}

// Delete Candidate
if (isset($_GET['delete'])) {
    $candidateId = $_GET['delete'];
    $deleteQuery = "DELETE FROM candidates WHERE id = $candidateId";
    mysqli_query($connection, $deleteQuery);
}

$query = "SELECT id, name, votes FROM candidates";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/vote.css">
    <title>Class Representatives Voting</title>
</head>
<body>
    <h1>Class Representatives Voting</h1>

    <!-- Add Candidate Form -->
    <h2>Add Candidate</h2>
    <form action="" method="POST">
        <input type="text" name="candidate_name" placeholder="Candidate Name" required>
        <button type="submit" name="add_candidate">Add Candidate</button>
    </form>

    <!-- Candidate List -->
    <h2>Candidates</h2>
    <ul>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li>';
            echo $row['name'] . ' - Votes: ' . $row['votes'];
            echo ' <a href="?delete=' . $row['id'] . '">Delete</a>';
            echo '</li>';
        }
        ?>
    </ul>

    <!-- Voting Form -->
    <h2>Vote</h2>
    <form action="" method="POST">
        <?php
        mysqli_data_seek($result, 0); // Reset result set pointer
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<label>';
            echo '<input type="radio" name="candidate_id" value="' . $row['id'] . '">';
            echo $row['name'];
            echo '</label><br>';
        }
        ?>
        <button type="submit" name="vote">Vote</button>
    </form>
</body>
</html>

<?php
// Close the database connection
mysqli_close($connection);
?>