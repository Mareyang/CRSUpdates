<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include "database.php";

header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : '';

// ── ADD COURSE ────────────────────────────────────────────────────────
if ($action === 'addCourse') {
    $courseCode  = mysqli_real_escape_string($connection, trim($_POST['courseCode']));
    $courseTitle = mysqli_real_escape_string($connection, trim($_POST['courseName']));
    $courseUnits = intval($_POST['units']);

    if (empty($courseCode) || empty($courseName) || $units <= 0) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }

    $check = mysqli_query($connection, "SELECT courseId FROM course WHERE courseCode = '$courseCode'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Subject code already exists.']);
        exit();
    }

    $result = mysqli_query($connection,
        "INSERT INTO course (courseCode, courseName, units)
         VALUES ('$courseCode', '$courseName', $units)");

    echo $result
        ? json_encode(['success' => true])
        : json_encode(['success' => false, 'message' => mysqli_error($connection)]);
    exit();
}

// ── EDIT COURSE ───────────────────────────────────────────────────────
if ($action === 'editCourse') {
    $courseId    = intval($_POST['courseId']);
    $courseCode  = mysqli_real_escape_string($connection, trim($_POST['courseCode']));
    $courseTitle = mysqli_real_escape_string($connection, trim($_POST['courseName']));
    $courseUnits = intval($_POST['units']);

    $result = mysqli_query($connection,
        "UPDATE course SET courseCode='$courseCode', courseName='$courseName', units=$units
         WHERE courseId=$courseId");

    echo $result
        ? json_encode(['success' => true])
        : json_encode(['success' => false, 'message' => mysqli_error($connection)]);
    exit();
}

// ── DELETE COURSE ─────────────────────────────────────────────────────
if ($action === 'deleteCourse') {
    $courseId = intval($_POST['courseId']);

    $result = mysqli_query($connection, "DELETE FROM course WHERE courseId=$courseId");

    echo $result
        ? json_encode(['success' => true])
        : json_encode(['success' => false, 'message' => mysqli_error($connection)]);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);
?>
