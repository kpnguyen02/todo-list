<!DOCTYPE html>
<html>
<head>
    <title>ToDo List</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: white; 
            padding: 20px;
        }

        /* Styles for the form */
        form {
            background-color: #545871;
            padding: 20px;
            border-radius: 10px;
        }
        form label {
            color: white;
        }
        form input[type="text"], form input[type="submit"]
        {
            border: none;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        form input[type="submit"]
        {
            background-color: #ECD0CD;
            color: white;
            cursor: pointer;
        }

        /* Style for to-do list items */
        .todo-item {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        /* Alternating background colors for the to-do list items */
        .todo-item:nth-child(even)
        {
            color: white;
            background-color: #545871;
        }
        .todo-item:nth-child(odd)
        {
            color: white;
            background-color: #ECD0CD;
        }

        /* Style for the remove button  */
        .removeButton {
            color: white;
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h2>ToDo List Items</h2>
    <?php
    // Connect to the database
    $conn = mysqli_connect('localhost', 'root', '', 'todolist');

    // To check  the connection 
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Checks if a new item has been added
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];

        // Insert the new item into the database
        $sql = "INSERT INTO todoitems (Title, Description) VALUES ('$title', '$description')";
        mysqli_query($conn, $sql);
    }

    // Check if an item was removed
    if (isset($_GET['remove'])) {
        $itemNum = $_GET['remove'];

        // Deletes the item from the database
        $sql = "DELETE FROM todoitems WHERE ItemNum = $itemNum";
        mysqli_query($conn, $sql);
    }

    // Retrieves the ToDo List items
    $sql = "SELECT * FROM todoitems";
    $result = mysqli_query($conn, $sql);

    // Displays the ToDo List items
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $itemNum = $row['ItemNum'];
            $title = $row['Title'];
            $description = $row['Description'];
            echo "<p class='todo-item'>$title: $description <span class='removeButton' onclick=\"window.location.href='index.php?remove=$itemNum'\">X</span></p>";
        }
    } else {
        echo "No to do list items exist yet.";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>

    <!-- HTML Form styling -->
    <h2>Add Item</h2>
    <form method="POST" action="index.php">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required maxlength="20"><br><br>
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" required maxlength="50"><br><br>
        <input type="submit" value="Add">
    </form>
</body>
</html>
