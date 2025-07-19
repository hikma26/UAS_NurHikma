<?php
include 'auth_check.php';

echo "<h3>Checking blood_requests table structure:</h3>";

// Check if table exists and show structure
$result = mysqli_query($conn, "DESCRIBE blood_requests");
if ($result) {
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error describing table: " . mysqli_error($conn);
}

echo "<br><h3>Sample data from blood_requests:</h3>";

// Try to get sample data
$sample = mysqli_query($conn, "SELECT * FROM blood_requests LIMIT 5");
if ($sample) {
    if (mysqli_num_rows($sample) > 0) {
        echo "<table border='1'>";
        $first = true;
        while ($row = mysqli_fetch_assoc($sample)) {
            if ($first) {
                echo "<tr>";
                foreach (array_keys($row) as $col) {
                    echo "<th>$col</th>";
                }
                echo "</tr>";
                $first = false;
            }
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No data in blood_requests table.";
    }
} else {
    echo "Error querying table: " . mysqli_error($conn);
}
?>
