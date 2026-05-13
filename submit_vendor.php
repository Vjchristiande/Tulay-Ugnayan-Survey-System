<?php

session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Vendor') {
    header('Location: Login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: VendorSurveyForm.php');
    exit();
}

$user_id = (int) $_SESSION['user_id'];

// 1. DUPLICATE ENTRY CHECK
$dup = $conn->prepare("SELECT id FROM vendor_responses WHERE user_id = ?");
$dup->bind_param('i', $user_id);
$dup->execute();
$dup->store_result();
if ($dup->num_rows > 0) {
    $dup->close();
    $_SESSION['vendor_error'] = 'You have already submitted a response. Only one submission is allowed per account.';
    header('Location: VendorSurveyForm.php');
    exit();
}
$dup->close();

// 2. COLLECT & SANITIZE
$consent                    = trim($_POST['consent']                    ?? '');
$age                        = trim($_POST['age']                        ?? '');
$gender                     = trim($_POST['gender']                     ?? '');
$goods_type                 = trim($_POST['goods_type']                 ?? '');
$registered                 = trim($_POST['registered']                 ?? '');
$operating_duration         = trim($_POST['operating_duration']         ?? '');
$student_purchase_freq      = trim($_POST['student_purchase_freq']      ?? '');
$sales_percentage           = trim($_POST['sales_percentage']           ?? '');
$dictate_flow               = trim($_POST['dictate_flow']               ?? '');
$relationship               = trim($_POST['relationship']               ?? '');
$cleanliness_rating         = trim($_POST['cleanliness_rating']         ?? '');
$student_effect_cleanliness = trim($_POST['student_effect_cleanliness'] ?? '');

$hoursArray           = $_POST['highest_volume_hours'] ?? [];
$highest_volume_hours = implode(', ', array_map('trim', (array) $hoursArray));

$issuesArray    = $_POST['student_issues'] ?? [];
$student_issues = implode(', ', array_map('trim', (array) $issuesArray));

// 3. REQUIRED FIELDS CHECK
$required = [
    $consent, $age, $gender, $goods_type, $registered,
    $operating_duration, $student_purchase_freq, $highest_volume_hours,
    $sales_percentage, $dictate_flow, $relationship, $student_issues,
    $cleanliness_rating, $student_effect_cleanliness
];
foreach ($required as $value) {
    if (empty($value)) {
        $_SESSION['vendor_error'] = 'Please answer all required questions.';
        header('Location: VendorSurveyForm.php');
        exit();
    }
}

// 4. CONSENT CHECK
if ($consent !== 'yes') {
    $_SESSION['vendor_error'] = 'You must consent to participate in order to submit the survey.';
    header('Location: VendorSurveyForm.php');
    exit();
}

// 5. WHITELIST VALIDATION
$valid_age     = ['18-24','25-34','35-44','45-54','55+'];
$valid_gender  = ['male','female','prefer_not_to_say'];
$valid_goods   = ['cooked_food','fresh_produce','school_supplies','other'];
$valid_reg     = ['yes','no','in_process'];
$valid_dur     = ['less_1_year','1_to_3_years','4_to_6_years','more_than_6_years'];
$valid_freq    = ['very_frequently','frequently','occasionally','rarely_never'];
$valid_sales   = ['less_10','11_25','26_50','more_50'];
$valid_dictate = ['strongly_agree','agree','neutral','disagree','strongly_disagree'];
$valid_rel     = ['excellent','good','fair','poor'];
$valid_clean_r = ['5','4','3','2','1'];
$valid_effect  = ['negative','neutral','positive'];
$valid_hours   = ['morning','midday','afternoon'];
$valid_issues  = ['none','loitering','noise','disrespectful','theft','other'];

if (!in_array($age,                        $valid_age))     { $_SESSION['vendor_error'] = 'Invalid age selection.';                  header('Location: VendorSurveyForm.php'); exit(); }
if (!in_array($gender,                     $valid_gender))  { $_SESSION['vendor_error'] = 'Invalid gender selection.';               header('Location: VendorSurveyForm.php'); exit(); }
if (!in_array($goods_type,                 $valid_goods))   { $_SESSION['vendor_error'] = 'Invalid goods type selection.';           header('Location: VendorSurveyForm.php'); exit(); }
if (!in_array($registered,                 $valid_reg))     { $_SESSION['vendor_error'] = 'Invalid registration status.';            header('Location: VendorSurveyForm.php'); exit(); }
if (!in_array($operating_duration,         $valid_dur))     { $_SESSION['vendor_error'] = 'Invalid operating duration selection.';   header('Location: VendorSurveyForm.php'); exit(); }
if (!in_array($student_purchase_freq,      $valid_freq))    { $_SESSION['vendor_error'] = 'Invalid purchase frequency selection.';   header('Location: VendorSurveyForm.php'); exit(); }
if (!in_array($sales_percentage,           $valid_sales))   { $_SESSION['vendor_error'] = 'Invalid sales percentage selection.';     header('Location: VendorSurveyForm.php'); exit(); }
if (!in_array($dictate_flow,               $valid_dictate)) { $_SESSION['vendor_error'] = 'Invalid dictate flow selection.';         header('Location: VendorSurveyForm.php'); exit(); }
if (!in_array($relationship,               $valid_rel))     { $_SESSION['vendor_error'] = 'Invalid relationship selection.';         header('Location: VendorSurveyForm.php'); exit(); }
if (!in_array($cleanliness_rating,         $valid_clean_r)) { $_SESSION['vendor_error'] = 'Invalid cleanliness rating.';             header('Location: VendorSurveyForm.php'); exit(); }
if (!in_array($student_effect_cleanliness, $valid_effect))  { $_SESSION['vendor_error'] = 'Invalid student effect selection.';       header('Location: VendorSurveyForm.php'); exit(); }

foreach ($hoursArray as $h) {
    if (!in_array(trim($h), $valid_hours)) {
        $_SESSION['vendor_error'] = 'Invalid peak hours selection.';
        header('Location: VendorSurveyForm.php');
        exit();
    }
}
foreach ($issuesArray as $i) {
    if (!in_array(trim($i), $valid_issues)) {
        $_SESSION['vendor_error'] = 'Invalid student issues selection.';
        header('Location: VendorSurveyForm.php');
        exit();
    }
}

// 6. INSERT
$stmt = $conn->prepare(
    "INSERT INTO vendor_responses
        (user_id, consent, age, gender, goods_type, registered,
         operating_duration, student_purchase_freq, highest_volume_hours,
         sales_percentage, dictate_flow, relationship, student_issues,
         cleanliness_rating, student_effect_cleanliness)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    'issssssssssssss',
    $user_id, $consent, $age, $gender, $goods_type, $registered,
    $operating_duration, $student_purchase_freq, $highest_volume_hours,
    $sales_percentage, $dictate_flow, $relationship, $student_issues,
    $cleanliness_rating, $student_effect_cleanliness
);

if (!$stmt->execute()) {
    $stmt->close();
    $_SESSION['vendor_error'] = 'Submission failed. Please try again.';
    header('Location: VendorSurveyForm.php');
    exit();
}

$stmt->close();
$conn->close();

header('Location: student_results.php');
exit();
?>