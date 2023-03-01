<?php include_once 'connection.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Test to import excel to db</title>
</head>

<body>
    <style type="text/css">
        body {
            font-family: sans-serif;
        }

        p {
            color: green;
        }
    </style>
    <h2>data</h2>

    <?php
    if (isset($_GET['berhasil'])) {
        echo "<p>" . $_GET['berhasil'] . " Data berhasil di import.</p>";
    }
    ?>
    <form method="post" enctype="multipart/form-data" action="">
        Choose File:
        <input name="employeeFile" type="file" required="required">
        <button name="import" type="submit">Import</button>
    </form>

    <br />

    <table border="1">
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Age</th>
            <th>Country</th>
        </tr>
        <?php

        $query = "SELECT * FROM employee";
        $data = mysqli_query($conn, $query);

        while ($d = mysqli_fetch_array($data)) {
        ?>
            <tr>
                <th><?php echo $d['id']; ?></th>
                <th><?php echo $d['name']; ?></th>
                <th><?php echo $d['age']; ?></th>
                <th><?php echo $d['country']; ?></th>
            </tr>
        <?php
        }
        ?>

    </table>

    <?php
    if (isset($_POST["import"])) {
        $fileName = $_FILES["employeeFile"]["name"];
        $fileExtension = explode('.', $fileName);
        $fileExtension = strtolower(end($fileExtension));

        $newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;

        $targetDirectory = "uploads/" . $newFileName;
        move_uploaded_file($_FILES["employeeFile"]["tmp_name"], $targetDirectory);

        error_reporting(0);
        ini_set('display_errors', 0);

        require "excelReader/excel_reader2.php";
        require "excelReader/SpreadsheetReader.php";


        $reader = new SpreadsheetReader($targetDirectory);
        foreach ($reader as $key => $row) {
            $name = $row[0];
            $age = $row[1];
            $country = $row[2];

            mysqli_query($conn, "INSERT INTO employee VALUES ('', '$name', '$age', '$country')");
        }

        echo "
        <script>
        alert('test');
        document.location.href = '';
        </script>
        ";
    }

    ?>


</body>

</html>