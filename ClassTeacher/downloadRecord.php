<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$filename = "All_Students_All_Dates";
$cnt = 1;

// Fetch all attendance records for all students across all dates
$ret = mysqli_query($conn, "SELECT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblclass.className,
        tblclassarms.classArmName, tblsessionterm.sessionName, tblsessionterm.termId, tblterm.termName,
        tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
        FROM tblattendance
        INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
        INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
        INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
        INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
        INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo");

// Start building the Excel file
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$filename."-report.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo '<style>
    .present { background-color: #00FF00; }
    .absent { background-color: #FF0000; }
</style>';

echo '<table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Other Name</th>
                <th>ID No</th>
                <th>Class</th>
                <th>Class Section</th>
                <th>School Year</th>
                <th>Semester</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>';

if (mysqli_num_rows($ret) > 0) {
    while ($row = mysqli_fetch_array($ret)) {
        $status = ($row['status'] == '1') ? "Present" : "Absent";
        $statusClass = ($row['status'] == '1') ? "present" : "absent";

        echo '<tr class="'.$statusClass.'">
                <td>'.$cnt.'</td>
                <td>'.$row['firstName'].'</td>
                <td>'.$row['lastName'].'</td>
                <td>'.$row['otherName'].'</td>
                <td>'.$row['admissionNumber'].'</td>
                <td>'.$row['className'].'</td>
                <td>'.$row['classArmName'].'</td>
                <td>'.$row['sessionName'].'</td>
                <td>'.$row['termName'].'</td>
                <td>'.$status.'</td>
                <td>'.$row['dateTimeTaken'].'</td>
            </tr>';
        
        $cnt++;
    }
}

echo '</table>';
?>
