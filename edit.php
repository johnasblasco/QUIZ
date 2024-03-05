<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        h2 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="email"],
        input[type="hidden"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        button {
            margin-top: 10px;
            width: 100%;
            padding: 10px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background-color: #cf0606;
        }
    </style>
</head>
<body>
<?php
$xml = simplexml_load_file('students.xml');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $originalStudentNumber = $_POST['original_student_number'];
    $editedStudentNumber = $_POST['edited_student_number'];
    $name = $_POST['name'];

    $studentFound = false;
    foreach ($xml->student as $student) {
        if (strcasecmp($student->studentNumber, $originalStudentNumber) === 0) {
            $studentFound = true;
            $student->studentNumber = $editedStudentNumber;
            $student->studentName = $name;

            // Update quiz scores
            $i = 1;
            while (isset($_POST["quiz_$i"])) {
                $quizScore = (int)$_POST["quiz_$i"];
                $student->quizzes->quiz[$i - 1] = $quizScore;
                $i++;
            }

            // Save the updated XML file
            $xml->asXML('students.xml');
            break;
        }
    }

    if (!$studentFound) {
        echo "Student not found.";
    } else {
        header("Location: index.php");
    }
}

$originalStudentNumber = isset($_GET['student_number']) ? $_GET['student_number'] : '';

if ($xml) {
    $studentFound = false;
    foreach ($xml->student as $student) {
        if (strcasecmp($student->studentNumber, $originalStudentNumber) === 0) {
            $studentFound = true;
            $studentNumber = htmlspecialchars($student->studentNumber);
            $name = htmlspecialchars($student->studentName);

            echo "<h2>Edit Student</h2>";
            echo "<form action='edit.php' method='post'>";
            echo "<input type='hidden' name='original_student_number' value='$studentNumber'>";
            echo "Student Number: <input type='text' name='edited_student_number' value='$studentNumber' required><br><br>";
            echo "Name: <input type='text' name='name' value='$name' required><br><br>";

            // Display quiz scores for editing
            $i = 1;
            foreach ($student->quizzes->quiz as $quiz) {
                $quizScore = htmlspecialchars($quiz);
                echo "Score of Quiz $i: <input type='text' name='quiz_$i' value='$quizScore' required><br><br>";
                $i++;
            }

            // Button for submission
            echo "<input type='submit' value='Update'>";
            echo "<a href='index.php'><button>Back to Home</button></a>";
            echo "</form>";
            break;
        }
    }

    if (!$studentFound) {
        echo "Student not found.";
    }
} else {
    echo "Error loading XML file.";
}
?>




</body>
</html>
