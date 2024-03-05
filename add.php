<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $xml = simplexml_load_file("students.xml");

    // Create a new student node
    $student = $xml->addChild("student");

    // Add data to the student node
    $student->addChild("studentNumber", $_POST["studentNumber"]);
    $student->addChild("studentName", $_POST["studentName"]);

    // Create the quizzes node for the student
    $quizzes = $student->addChild("quizzes");
    $quizzes->addChild("quiz", $_POST["quiz1"]);
    $quizzes->addChild("quiz", $_POST["quiz2"]);
    $quizzes->addChild("quiz", $_POST["quiz3"]);
    $quizzes->addChild("quiz", $_POST["quiz4"]);
    $quizzes->addChild("quiz", $_POST["quiz5"]);

    // Save the changes back to the XML file
    $xml->asXML("students.xml");

    header("Location: index.php"); // Redirect after adding
    exit();
} else {
    // If the form is not submitted, redirect to index.php
    header("Location: index.php");
    exit();
}
?>
