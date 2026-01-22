<?php
// generate_certificate.php
header('Content-Type: application/json');
include 'db.php';

/* INPUT SANITIZATION */
$fullName = trim($_POST['full_name'] ?? '');
$program  = trim($_POST['program'] ?? '');
$date     = trim($_POST['program_date'] ?? '');

if ($fullName === '' || $program === '' || $date === '') {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required'
    ]);
    exit;
}

/* MAP SHORTCODE TO FULL PROGRAM NAME AND PREFIX */
$programMap = [
    'AI'  => ['name' => 'AI For Productivity and Profit Training', 'prefix' => 'APP/OL/'],
    'BCC' => ['name' => 'Code Camp Training', 'prefix' => 'BCC/OL/'],
    'BWD' => ['name' => 'Web Design Training', 'prefix' => 'BWD/OL/']
];

if (!isset($programMap[$program])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid program selected'
    ]);
    exit;
}

$fullProgramName = $programMap[$program]['name'];
$prefix          = $programMap[$program]['prefix'];

/* GET LAST CERTIFICATE NUMBER */
$stmt = $conn->prepare("
    SELECT certificate_number 
    FROM certificates 
    WHERE certificate_number LIKE CONCAT(?, '%')
    ORDER BY id DESC 
    LIMIT 1
");
$stmt->bind_param("s", $prefix);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $lastCert = $result->fetch_assoc()['certificate_number'];
    $num = (int)substr($lastCert, -3) + 1;
} else {
    $num = 1; // start from 001
}
$number = str_pad($num, 3, '0', STR_PAD_LEFT);
$certificateNo = $prefix . $number;

/* INSERT RECORD */
$insert = $conn->prepare("
    INSERT INTO certificates 
    (full_name, program, program_date, certificate_number)
    VALUES (?, ?, ?, ?)
");
$insert->bind_param("ssss", $fullName, $fullProgramName, $date, $certificateNo);

if ($insert->execute()) {
    echo json_encode([
        'success' => true,
        'certificate_number' => $certificateNo
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to generate certificate'
    ]);
}
