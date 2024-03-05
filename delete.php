<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["studentNumber"])) {
    $studentNumber = $_POST["studentNumber"];

    // Load the XML file
    $xml = simplexml_load_file("students.xml");

    if ($xml === false) {
        die('Error: Cannot load XML file');
    }

    $studentFound = false;

    // Loop through each student to find the one with the matching studentNumber
    foreach ($xml->student as $student) {
        if ($student->studentNumber == $studentNumber) {
            // Remove the student node
            $dom = dom_import_simplexml($student);
            $dom->parentNode->removeChild($dom);
            $studentFound = true;
            break;
        }
    }

    if (!$studentFound) {
        die('Error: Student not found');
    }

    // Save the changes back to the XML file
    $xml->asXML("students.xml");

    // Redirect back to the index.php page after deleting the record
    header("Location: index.php");
    exit();
} else {
    // If the form is not submitted or required data is missing, redirect to index.php
    header("Location: index.php");
    exit();
}
?>
