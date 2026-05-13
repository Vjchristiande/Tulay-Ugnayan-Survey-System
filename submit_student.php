<?php

session_start();
require_once 'Config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header('Location: Login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: StudentSurveyForm.php');
    exit();
}

$user_id = (int) $_SESSION['user_id'];

// 1. DUPLICATE ENTRY CHECK
$dup = $conn->prepare("SELECT id FROM student_responses WHERE user_id = ?");
$dup->bind_param('i', $user_id);
$dup->execute();
$dup->store_result();
if ($dup->num_rows > 0) {
    $dup->close();
    $_SESSION['student_error'] = 'You have already submitted a response. Only one submission is allowed per account.';
    header('Location: StudentSurveyForm.php');
    exit();
}
$dup->close();

// 2. COLLECT & SANITIZE
$consent        = trim($_POST['consent']        ?? '');
$grade          = trim($_POST['grade']          ?? '');
$age_raw        = trim($_POST['age']            ?? '');
$gender         = trim($_POST['gender']         ?? '');
$visit_freq     = trim($_POST['visit_freq']     ?? '');
$allowance_use  = trim($_POST['allowance_use']  ?? '');
$study_help     = trim($_POST['study_help']     ?? '');
$smell          = trim($_POST['smell']          ?? '');
$overall_effect = trim($_POST['overall_effect'] ?? '');
$safety         = trim($_POST['safety']         ?? '');
$cleanliness    = trim($_POST['cleanliness']    ?? '');
$suggest_market = trim($_POST['suggest_market'] ?? '');
$suggest_safety = trim($_POST['suggest_safety'] ?? '');
$other_comments = trim($_POST['other_comments'] ?? '');

$buyArray = $_POST['buy'] ?? [];
$buy      = implode(', ', array_map('trim', (array) $buyArray));

// 3. REQUIRED FIELDS CHECK
$required = [
    $consent, $grade, $gender, $visit_freq, $buy,
    $allowance_use, $study_help, $smell, $overall_effect, $safety, $cleanliness
];
foreach ($required as $value) {
    if (empty($value)) {
        $_SESSION['student_error'] = 'Please answer all required questions.';
        header('Location: StudentSurveyForm.php');
        exit();
    }
}

// 4. CONSENT CHECK
if ($consent !== 'yes') {
    $_SESSION['student_error'] = 'You must consent to participate in order to submit the survey.';
    header('Location: StudentSurveyForm.php');
    exit();
}

// 5. AGE VALIDATION 
if ($age_raw === '') {
    $_SESSION['student_error'] = 'Please enter your age.';
    header('Location: StudentSurveyForm.php');
    exit();
} elseif (!ctype_digit($age_raw)) {
    $_SESSION['student_error'] = 'Age must be a whole number.';
    header('Location: StudentSurveyForm.php');
    exit();
} else {
    $age = (int) $age_raw;
    if ($age < 10) {
        $_SESSION['student_error'] = 'You must be at least 10 years old to participate in this survey.';
        header('Location: StudentSurveyForm.php');
        exit();
    } elseif ($age > 25) {
        $_SESSION['student_error'] = 'Age must not exceed 25 years old for student respondents.';
        header('Location: StudentSurveyForm.php');
        exit();
    }
}

// 6. WHITELIST VALIDATION
$valid_grades  = ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'];
$valid_genders = ['Male','Female','Non-binary','Prefer not to say'];
$valid_visit   = ['Never','Rarely','Once a week','Several times a week','Every day'];
$valid_allow   = ['Never','Rarely','Sometimes','Often','Almost always'];
$valid_study   = ['Not helpful at all','Unhelpful','Neutral','Helpful','Very helpful'];
$valid_smell   = ['No smell','Not noticeable','Neutral','Noticeable smell','Strong smell'];
$valid_effect  = ['Very negative','Somewhat negative','Neutral','Somewhat positive','Very positive'];
$valid_safety  = ['Never safe','Often unsafe','Sometimes unsafe','Usually safe','Always safe'];
$valid_clean   = ['Very dirty','Dirty','Neutral','Clean','Very clean'];
$valid_buy     = ['Food and snacks','School supplies','Drinks','Clothing','Groceries','Other','Nothings'];

if (!in_array($grade,          $valid_grades))  { $_SESSION['student_error'] = 'Invalid grade selection.';           header('Location: StudentSurveyForm.php'); exit(); }
if (!in_array($gender,         $valid_genders)) { $_SESSION['student_error'] = 'Invalid gender selection.';          header('Location: StudentSurveyForm.php'); exit(); }
if (!in_array($visit_freq,     $valid_visit))   { $_SESSION['student_error'] = 'Invalid visit frequency.';           header('Location: StudentSurveyForm.php'); exit(); }
if (!in_array($allowance_use,  $valid_allow))   { $_SESSION['student_error'] = 'Invalid allowance usage selection.'; header('Location: StudentSurveyForm.php'); exit(); }
if (!in_array($study_help,     $valid_study))   { $_SESSION['student_error'] = 'Invalid study help selection.';      header('Location: StudentSurveyForm.php'); exit(); }
if (!in_array($smell,          $valid_smell))   { $_SESSION['student_error'] = 'Invalid smell selection.';           header('Location: StudentSurveyForm.php'); exit(); }
if (!in_array($overall_effect, $valid_effect))  { $_SESSION['student_error'] = 'Invalid overall effect selection.';  header('Location: StudentSurveyForm.php'); exit(); }
if (!in_array($safety,         $valid_safety))  { $_SESSION['student_error'] = 'Invalid safety selection.';          header('Location: StudentSurveyForm.php'); exit(); }
if (!in_array($cleanliness,    $valid_clean))   { $_SESSION['student_error'] = 'Invalid cleanliness selection.';     header('Location: StudentSurveyForm.php'); exit(); }

foreach ($buyArray as $item) {
    if (!in_array(trim($item), $valid_buy)) {
        $_SESSION['student_error'] = 'Invalid purchase selection.';
        header('Location: StudentSurveyForm.php');
        exit();
    }
}

// Sanitize open-text fields
$suggest_market = mb_substr(strip_tags($suggest_market), 0, 1000);
$suggest_safety = mb_substr(strip_tags($suggest_safety), 0, 1000);
$other_comments = mb_substr(strip_tags($other_comments), 0, 1000);

// 7. INSERT
$stmt = $conn->prepare(
    "INSERT INTO student_responses
        (user_id, consent, grade, age, gender, visit_freq, buy,
         allowance_use, study_help, smell, overall_effect, safety,
         cleanliness, suggest_market, suggest_safety, other_comments)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    'isssssssssssssss',
    $user_id, $consent, $grade, $age, $gender, $visit_freq, $buy,
    $allowance_use, $study_help, $smell, $overall_effect, $safety,
    $cleanliness, $suggest_market, $suggest_safety, $other_comments
);

if (!$stmt->execute()) {
    $stmt->close();
    $_SESSION['student_error'] = 'Submission failed. Please try again.';
    header('Location: StudentSurveyForm.php');
    exit();
}

$stmt->close();
$conn->close();

header('Location: vendor_results.php');
exit();
?>