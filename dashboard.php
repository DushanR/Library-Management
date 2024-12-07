<?php
// Include database configuration file
include 'config.php';

// Query to get book categories and their total quantities
$query = "SELECT category, SUM(total_quantity) AS total_quantity FROM books GROUP BY category";
$result = mysqli_query($conn, $query);

$categories = [];
$quantities = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row['category'];
        $quantities[] = $row['total_quantity'];
    }
} else {
    echo "Error fetching data from the database.";
}
?>

<?php
// Query to count books borrowed each month
$sql = "SELECT MONTH(Date_borrowed) AS month, COUNT(*) AS books_borrowed 
        FROM issuebook 
        GROUP BY MONTH(Date_borrowed) 
        ORDER BY month";

$result = $conn->query($sql);

// Prepare data for the chart
$books_borrowed_per_month = array_fill(0, 12, 0); // Initialize array for 12 months

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $month = $row['month'] - 1; // Adjust to 0-based index
        $books_borrowed_per_month[$month] = $row['books_borrowed'];
    }
}
?>

<?php
// Query to get the total number of books (total_quantity)
$sql_total_books = "SELECT SUM(total_quantity) AS total_books FROM books";
$result_total_books = $conn->query($sql_total_books);
$total_books = 0;
if ($result_total_books->num_rows > 0) {
    $row = $result_total_books->fetch_assoc();
    $total_books = $row['total_books'];
}

// Query to get the total number of borrowed books (total_quantity - available_quantity)
$sql_books_borrowed = "SELECT SUM(total_quantity - available_quantity) AS books_borrowed FROM books";
$result_books_borrowed = $conn->query($sql_books_borrowed);
$books_borrowed = 0;
if ($result_books_borrowed->num_rows > 0) {
    $row = $result_books_borrowed->fetch_assoc();
    $books_borrowed = $row['books_borrowed'];
}

/* // Query to get the number of overdue books (based on Due_date)
$sql_overdue_books = "SELECT COUNT(*) AS overdue_books 
                      FROM issuebook 
                      WHERE Due_date < CURDATE()";  // Due_date is earlier than today
$result_overdue_books = $conn->query($sql_overdue_books);
$overdue_books = 0;
if ($result_overdue_books->num_rows > 0) {
    $row = $result_overdue_books->fetch_assoc();
    $overdue_books = $row['overdue_books'];
}

// Query to count books borrowed per month for the chart
$sql_monthly_borrowed_books = "SELECT MONTH(Date_borrowed) AS month, COUNT(*) AS books_borrowed 
                               FROM issuebook 
                               GROUP BY MONTH(Date_borrowed) 
                               ORDER BY month";

$result_monthly_borrowed_books = $conn->query($sql_monthly_borrowed_books);

$books_borrowed_per_month = array_fill(0, 12, 0); // Initialize array for 12 months

if ($result_monthly_borrowed_books->num_rows > 0) {
    while ($row = $result_monthly_borrowed_books->fetch_assoc()) {
        $month = $row['month'] - 1; // Adjust to 0-based index
        $books_borrowed_per_month[$month] = $row['books_borrowed'];
    }
} */

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid custom-navbar">
            <a class="navbar-brand" href="#">Library Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="adminManageBooks.html">Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="adminManageUsers.html">Members</a></li>
                    <li class="nav-item"><a class="nav-link" href="adminIssueBooks.html">Issue & Return</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Add custom CSS -->
    <style>
        .custom-navbar {
            width: 90%;
            margin: 0 auto;
        }
        .row{
            margin-top: 5rem
        }
    </style>

    <!-- Dashboard -->
    <div class="container mt-5">
    <div class="row">
            <!-- Stats Cards -->
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Books</h5>
                        <p class="card-text"><?php echo $total_books; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Books Borrowed</h5>
                        <p class="card-text"><?php echo $books_borrowed; ?></p>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Overdue Books</h5>
                        <p class="card-text"><?php echo $overdue_books; ?></p>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-md-6">
                <canvas id="barChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="pieChart" width="350" height="350" style="display: block; margin: auto;"></canvas>
            </div>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const barCtx = document.getElementById('barChart').getContext('2d');

        // PHP echo the array of data into JavaScript
        const booksBorrowedData = <?php echo json_encode($books_borrowed_per_month); ?>;

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Books Borrowed',
                    data: booksBorrowedData, // Use the data from PHP
                    backgroundColor: '#003f5c'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });

        // Pass PHP arrays to JavaScript
        const categories = <?php echo json_encode($categories); ?>;
        const quantities = <?php echo json_encode($quantities); ?>;

        // Pie Chart for Book Categories
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: categories,  // Use the categories fetched from the database
                datasets: [{
                    data: quantities,  // Use the quantities fetched from the database
                    backgroundColor: ['#F66D44', '#E6F69D', '#BE61CA', '#64C2A6', '#2D87BB']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>
</html>
