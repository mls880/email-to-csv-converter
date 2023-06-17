<!DOCTYPE html>
<html>

    <head>
        <title>Mugg & Bean Spill<br>the Beans Conversion</title>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
                max-width: 1200px;
                margin: 20px auto;
                font-size: 12px;
                font-family: Arial, sans-serif;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
                font-weight: bold;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tr:hover {
                background-color: #f5f5f5;
            }

            tr:nth-child(1) {
                color: #000;
                font-weight: bold;
            }

            textarea {
                width: 100%;
                max-width: 1200px;
                height: 200px;
                margin: 20px auto;
                font-size: 14px;
                font-family: Arial, sans-serif;
            }

            input[type=submit] {
                background-color: #4CAF50;
                color: white;
                border: none;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 14px;
                font-family: Arial, sans-serif;
                border-radius: 5px;
                cursor: pointer;
            }

            input[type=submit]:hover {
                background-color: #3e8e41;
            }

            h1 {
                text-align: center;
                font-family: Arial, sans-serif;
            }

            .holder {

                max-width: 1200px;
                margin-inline: auto;
            }
        </style>
    </head>

    <body>
        <div class="holder">
            <h1>Mugg & Bean Spill<br>the Beans Conversion</h1>
            <form method="POST">
                <label for="newSubmission">New submission data:</label><br>
                <textarea id="newSubmission" name="newSubmission" rows="25" cols="80"></textarea><br><br>
                <input type="submit" value="Submit">
            </form>

            <br>
            <?php
        // define the initial $data array
        $data = array(
            'Name and surname' => '',
            'Registered mobile number on loyalty app' => '',
            'Email address used to sign up' => '',
            'Last time newsletter was read' => '',
            'Newspaper length comment' => '',
            'I want to read more about' => array(),
            'Anything else to see in future newsletters' => '',
            'Device used to read newsletters' => '',
            'Comments' => ''
        );
        // check for new submission data in the text area field
        if (isset($_POST['newSubmission'])) {
            // parse the new submission data
            $newData = array();
            $lines = explode("\n", $_POST['newSubmission']);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) {
                    continue;
                }
                $parts = explode(':', $line);
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                if ($key == 'I want to read more about') {
                    $newData[$key][] = $value;
                } else {
                    $newData[$key] = $value;
                }
            }
            // merge the new submission data with the existing data
            $data = array_merge($data, $newData);
            // add the new data to the CSV file
            $fp = fopen('data.csv', 'a');
            $colHeadings = array(
                "Name and surname",
                "Registered mobile number on loyalty app",
                "Email address used to sign up",
                "Last time newsletter was read",
                "Newspaper length comment",
                "I want to read more about",
                "Anything else to see in future newsletters",
                "Device used to read newsletters",
                "Comments"
            );
            $fileSize = filesize('data.csv');
            if ($fileSize === 0) {
                fputcsv($fp, $colHeadings);
            }
            $row = array();
            foreach ($colHeadings as $col) {
                $row[] = is_array($data[$col]) ? implode(", ", $data[$col]) : $data[$col];
            }
            fputcsv($fp, $row);
            fclose($fp);
        }
// read the data from the CSV file into an array
$data = array();
if (($handle = fopen('data.csv', 'r')) !== false) {
    // read the first row separately
    $first_row = fgetcsv($handle, 1000, ",");
    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        $data[] = $row;
    }
    fclose($handle);
}

// reverse the array
$data = array_reverse($data);

// add the first row to the beginning of the table
array_unshift($data, $first_row);

// display the data in an HTML table
if (!empty($data)) {
    echo "<table border='1'>";
    foreach ($data as $row) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: No data found.";
}
        ?>
        </div>
    </body>

</html>
