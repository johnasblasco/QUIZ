<?php
$xmlString = file_get_contents("students.xml");
$xmlDoc = new DOMDocument();
$xmlDoc->loadXML($xmlString);

$students = $xmlDoc->getElementsByTagName("student");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Record</title>
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Madimi+One&display=swap");
    * {
      font-family: "Madimi One", sans-serif;
      text-align: center;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    img {
      height: 20px;
      width: 20px;
    }
    table {
      width: 100%;
      border: 1px solid black;
      border-collapse: collapse;
    }
    tr:nth-child(even) {
      background-color: gray;
    }
    th,
    td {
      padding: 5px;
      border: 1px solid black;
    }
    th {
      background-color: gray;
    }

    </style>
</head>
<body>
  <h1>Quiz Record</h1>
  <a href="add.html"><input id="btn" type="button" value="+ Add New Record"></a>

<table id="basketTable">
    <thead>
        <tr>
            <th>Student Number</th>
            <th>Name</th>
            <th>Quiz 1</th>
            <th>Quiz 2</th>
            <th>Quiz 3</th>
            <th>Quiz 4</th>
            <th>Quiz 5</th>
            <th>Average Quiz</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($students as $student) {
            $studentNumber = $student->getElementsByTagName("studentNumber")->item(0)->textContent;
            $name = $student->getElementsByTagName("studentName")->item(0)->textContent;

            echo "<tr>";
            echo "<td>$studentNumber</td>";
            echo "<td>$name</td>";

            // Fetch quiz scores
            $quizzes = $student->getElementsByTagName("quiz");
            $totalScore = 0;
            $quizCount = $quizzes->length;

            for ($i = 0; $i < 5; $i++) {
                $quizScore = $quizzes->item($i)->textContent;
                echo "<td>$quizScore</td>";
                $totalScore += $quizScore;
            }

            // Calculate average score
            $averageScore = $quizCount > 0 ? $totalScore / $quizCount : 0;
            echo "<td>$averageScore</td>";

            // Action column
            echo "<td>";
            echo "<a href='edit.php?student_number=$studentNumber'><img src='pen.png'></a>"; // Fix the parameter name
            echo "<form action='delete.php' method='post' onsubmit='return confirm(`Are you sure you want to delete this record?`);'>";
            echo "<input type='hidden' name='studentNumber' value='$studentNumber'>";
            echo "<button type='submit'><img src='delete.png'></button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
