<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include "database.php";

    if (isset($_POST['submitCourseAdd'])) {
        $courseCode  = mysqli_real_escape_string($connection, trim($_POST['courseCode']));
        $courseName  = mysqli_real_escape_string($connection, trim($_POST['courseName']));
        $units       = intval($_POST['units']);
        $programId   = intval($_POST['programId']);

        $result = mysqli_query($connection,
            "INSERT INTO course (courseCode, courseName, units, programId) VALUES ('$courseCode', '$courseName', $units, $programId)");

        if ($result) {
            echo '<script>alert("Course saved successfully!"); window.location.href="admin.php";</script>';
        } else {
            echo '<script>alert("Error: ' . mysqli_error($connection) . '"); window.history.back();</script>';
        }
        exit();
    }

    if (isset($_POST['action']) && $_POST['action'] === 'deleteClassroom') {
        header('Content-Type: application/json');
        $roomId = intval($_POST['roomId']);
        $result = mysqli_query($connection, "DELETE FROM classroom WHERE roomId=$roomId");
        echo $result ? json_encode(['success' => true]) : json_encode(['success' => false, 'message' => mysqli_error($connection)]);
        exit();
    }

    if (isset($_POST['action']) && $_POST['action'] === 'editClassroom') {
        header('Content-Type: application/json');
        $roomId     = intval($_POST['roomId']);
        $roomName   = mysqli_real_escape_string($connection, trim($_POST['roomName']));
        $roomFloor  = mysqli_real_escape_string($connection, trim($_POST['roomFloor']));
        $capacity   = intval($_POST['capacity']);
        $roomStatus = mysqli_real_escape_string($connection, trim($_POST['roomStatus']));
        $result = mysqli_query($connection,
            "UPDATE classroom SET roomName='$roomName', roomFloor='$roomFloor', capacity=$capacity, roomStatus='$roomStatus' WHERE roomId=$roomId");
        echo $result ? json_encode(['success' => true]) : json_encode(['success' => false, 'message' => mysqli_error($connection)]);
        exit();
    }

    if (isset($_POST['submitClassroomAdd'])) {
        $roomName   = mysqli_real_escape_string($connection, trim($_POST['roomName']));
        $roomFloor  = mysqli_real_escape_string($connection, trim($_POST['roomFloor']));
        $capacity   = intval($_POST['capacity']);
        $roomStatus = mysqli_real_escape_string($connection, trim($_POST['roomStatus']));

        $sql = "INSERT INTO classroom (roomName, roomFloor, capacity, roomStatus) VALUES ('$roomName', '$roomFloor', $capacity, '$roomStatus')";
        $result = mysqli_query($connection, $sql);

        if ($result) {
            echo '<script>alert("Classroom saved successfully!"); window.location.href="admin.php";</script>';
        } else {
            echo '<script>alert("Error: ' . mysqli_error($connection) . '"); window.history.back();</script>';
        }
        exit();
    }

    if(isset($_POST['submitClassroom'])) {
        $employeeNumber = $_SESSION['employeeNumber'];

        $sql = "SELECT professorId
                FROM professor
                WHERE employeeNumber = '$employeeNumber'";

        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);

        $professorId = $row['professorId'];
        $classroom = $_POST['classroom'];
        $requester = $professorId;
        $purpose = $_POST['reservePurpose'];
        $date = $_POST['reserveDate'];
        $start_time = $_POST['startTime'];
        $end_time = $_POST['endTime'];
        $status = "Pending";
        $approvedBy = NULL;

            if ($start_time >= $end_time) {
            echo "<script>alert('End Time must be later than Start Time.');</script>";
            exit();
        }

      $queryClassroom = "INSERT INTO reservation (reservationId, roomId, requestedByStudent, requestedByProfessor,purpose,reservationDate,startTime,endTime,status,approvedBy) VALUES (null,'$classroom', NULL, '$requester', '$purpose', '$date', '$start_time', '$end_time', 'Pending', NULL)";
      $resultClassroom = mysqli_query($connection, $queryClassroom);

      echo '<script>alert("Priority Request Submitted!");</script>';
      echo '<script>window.location.href="professor.php";</script>';
    }

    if(isset($_POST['submitStudentClassroom'])) {
        $studentNumber = $_SESSION['studentNumber'];

        $sql = "SELECT studentId
                FROM student
                WHERE studentNumber = '$studentNumber'";

        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);

        $studentId = $row['studentId'];
        $classroom = $_POST['classroom'];
        $requester = $studentId;
        $purpose = $_POST['reservePurpose'];
        $date = $_POST['reserveDate'];
        $start_time = $_POST['startTime'];
        $end_time = $_POST['endTime'];
        $status = "Pending";
        $approvedBy = NULL;

            if ($start_time >= $end_time) {
            echo "<script>alert('End Time must be later than Start Time.');</script>";
            exit();
        }

      $queryStudentClassroom = "INSERT INTO reservation (reservationId, roomId, requestedByStudent, requestedByProfessor,purpose,reservationDate,startTime,endTime,status,approvedBy) VALUES (null,'$classroom', '$requester', NULL, '$purpose', '$date', '$start_time', '$end_time', 'Pending', NULL)";
      $resultStudentClassroom = mysqli_query($connection, $queryStudentClassroom);

      echo '<script>alert("Priority Request Submitted!");</script>';
      echo '<script>window.location.href="student.php";</script>';
    }


    // if(isset($_POST['submitStudentClassroom'])) {
    //     session_start();
    //             $studentNumber = $_SESSION['studentNumber'];

    //     $sql = "SELECT studentId
    //             FROM student
    //             WHERE studentNumber = '$studentNumber'";

    //     $result = mysqli_query($connection, $sql);
    //     $row = mysqli_fetch_assoc($result);

    //     $studentId = $row['studentId'];
    //     $classroom = $_POST['classroom'];
    //     $requester = $studentId;
    //     $purpose = $_POST['reservePurpose'];
    //     $date = $_POST['reserveDate'];
    //     $start_time = $_POST['startTime'];
    //     $end_time = $_POST['endTime'];
    //     $status = "Pending";
    //     $approvedBy = NULL;

    //         if ($start_time >= $end_time) {
    //         echo "<script>alert('End Time must be later than Start Time.');</script>";
    //         exit();
    //     }

    //   $queryStudentClassroom = "INSERT INTO reservation (reservationId, roomId, requestedByStudent, requestedByProfessor,purpose,reservationDate,startTime,endTime,status,approvedBy) VALUES (null,'$classroom', '$requester', NULL, '$purpose', '$date', '$start_time', '$end_time', 'Pending', NULL)";
    //   $resultStudentClassroom = mysqli_query($connection, $queryStudentClassroom);

    //   echo '<script>alert("Priority Request Submitted!");</script>';
    //   echo '<script>window.location.href="student.php";</script>';
    // }

    
    
    if (isset($_POST['submitStudent'])) {

    
    $studentNumber = trim($_POST['studentNumber']);
    $password      = $_POST['password']; // You can hash this using password_hash() for better security
    $firstName     = trim($_POST['firstName']);
    $middleName    = !empty($_POST['middleName']) ? trim($_POST['middleName']) : NULL;
    $lastName      = trim($_POST['lastName']);
    $sectionId     = $_POST['sectionId'];

    
    if (empty($studentNumber) || empty($password) || empty($firstName) || empty($lastName) || empty($sectionId)) {
        echo "<script>alert('All required fields must be filled out.');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }
   
    $sql = "INSERT INTO student (studentNumber, password, firstName, middleName, lastName, sectionId) 
            VALUES ('$studentNumber', '$password', '$firstName', " . ($middleName ? "'$middleName'" : "NULL") . ", '$lastName', '$sectionId')";

    $result = mysqli_query($connection, $sql);

    if ($result) {
        echo "<script>alert('Student Registration Successful!');</script>";
        echo "<script>window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error creating student registration.');</script>";
        echo "<script>window.history.back();</script>";
    }
}

if (isset($_POST['submitProfessor'])) {
    $username    = mysqli_real_escape_string($connection, trim($_POST['username']));
    $password    = mysqli_real_escape_string($connection, $_POST['password']);
    $firstName   = mysqli_real_escape_string($connection, trim($_POST['firstName']));
    $middleName  = !empty($_POST['middleName']) ? "'" . mysqli_real_escape_string($connection, trim($_POST['middleName'])) . "'" : "NULL";
    $lastName    = mysqli_real_escape_string($connection, trim($_POST['lastName']));
    $profType  = mysqli_real_escape_string($connection, $_POST['profTypeId']);

    // if (empty($username) || empty($password) || empty($firstName) || empty($lastName) || empty($profTypeId)) {
    //     echo "<script>alert('All required fields must be filled out.');</script>";
    //     echo "<script>window.history.back();</script>";
    //     exit();
    // }

    // Use username as employeeNumber placeholder until admin assigns one
    $sql = "INSERT INTO professor (employeeNumber, password, firstName, middleName, lastName, professorType)
            VALUES ('$username', '$password', '$firstName', $middleName, '$lastName', '$profType')";

    $result = mysqli_query($connection, $sql);

    if ($result) {
        echo "<script>alert('Professor Registration Successful!');</script>";
        echo "<script>window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
        echo "<script>window.history.back();</script>";
    }
}

if (isset($_POST['submitAdmin'])) {
    $username    = mysqli_real_escape_string($connection, trim($_POST['username']));
    $password    = mysqli_real_escape_string($connection, $_POST['password']);
    $firstName   = mysqli_real_escape_string($connection, trim($_POST['firstName']));
    $middleName  = !empty($_POST['middleName']) ? "'" . mysqli_real_escape_string($connection, trim($_POST['middleName'])) . "'" : "NULL";
    $lastName    = mysqli_real_escape_string($connection, trim($_POST['lastName']));

    if (empty($username) || empty($password) || empty($firstName) || empty($lastName)) {
        echo "<script>alert('All required fields must be filled out.');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO admin (username, password, firstName, middleName, lastName)
            VALUES ('$username', '$password', '$firstName', $middleName, '$lastName')";

    $result = mysqli_query($connection, $sql);

    if ($result) {
        echo "<script>alert('Admin Registration Successful!');</script>";
        echo "<script>window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
        echo "<script>window.history.back();</script>";
    }
}

if(isset($_POST['submit-classroom'])){
    $roomName   = $_POST['roomName'];
    $roomFloor  = $_POST['roomFloor'];
    $capacity   = $_POST['capacity'];
    $roomStatus = $_POST['roomStatus'];

    $sql = "INSERT INTO classroom (roomName, roomFloor, roomCapacity, roomStatus) VALUES ('$roomName', '$roomFloor', $capacity, '$roomStatus')";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        echo '<script>alert("Classroom saved successfully!"); window.location.href="admin.php";</script>';
    } else {
        echo '<script>alert("Error: ' . mysqli_error($connection) . '"); window.history.back();</script>';
    }
    exit();
}

if(isset($_POST['submit-course'])){

    $subjectCode = $_POST['subjectCode'];
    $courseTitle = $_POST['courseTitle'];
    $courseUnits = $_POST['courseUnits'];
    $programId = 1;

    $sql = "INSERT INTO course (courseCode, courseName, units, programId)
            VALUES ('$subjectCode', '$courseTitle', '$courseUnits', '$programId')";

    if (!mysqli_query($connection, $sql)) {
        die("SQL Error: " . mysqli_error($connection));
    }

    echo '<script>
            alert("Course saved successfully!");
            window.location.href="admin.php";
          </script>';
    exit();
}


?>