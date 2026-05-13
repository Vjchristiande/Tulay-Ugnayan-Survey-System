<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Vendor') {
    header('Location: Login.php');
    exit();
}

require_once 'Config.php';

$user_id = (int) $_SESSION['user_id'];
$already = $conn->prepare("SELECT id FROM vendor_responses WHERE user_id = ?");
$already->bind_param('i', $user_id);
$already->execute();
$already->store_result();
$has_submitted = ($already->num_rows > 0);
$already->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Survey Form</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="VendorForm.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">
        <img src="https://i.pinimg.com/736x/bf/78/0e/bf780e807c7d275a981d0d784cf88e64.jpg" alt="Logo" style="height:44px;width:44px;object-fit:cover;border-radius:50%;">
    </div>
    <div class="nav-links">
        <a href="V_Home.php">HOME</a>
        <a href="VendorSurveyForm.php" class="active">SURVEY</a>
        <a href="student_results.php">RESULTS</a>
        <a href="logout.php" class="logout-btn">LOGOUT</a>
    </div>
</nav>

    <div class="split-container">
        <div class="left-image"></div>

        <div class="right-content">
            <div class="form-wrapper">

                <div class="form-header">
                    <span class="form-tag">Community Survey</span>
                    <h1 class="form-title">Vendor Experience Survey</h1>
                    <p class="form-description">Your answers will help us understand how the proximity to Ramon Magsaysay Cubao High School affects your business operations.</p>
                </div>

                <div class="notice-block">
                    <strong>Before you begin:</strong>
                    In partial fulfillment of the requirements for the subject Web Development, we, the students of DIT 2-5 (Group 4), are conducting this survey as part of our project titled <em>"Tulay Ugnayan: Understanding Barriers Between Ramon Magsaysay Cubao High School and Q-mart Market."</em>
                </div>

                <?php if ($has_submitted): ?>
                    <div class="already-submitted-banner">
                        <strong>⚠ You have already submitted a response.</strong><br>
                        Only one submission is allowed per account. Thank you for participating!
                        <br><br>
                        <a href="student_results.php" style="color:#e65100;font-weight:600;">View Survey Results →</a>
                    </div>

                <?php else: ?>

                    <?php if (!empty($_SESSION['vendor_error'])): ?>
                        <p style="color:red;text-align:center;"><?php echo htmlspecialchars($_SESSION['vendor_error']); unset($_SESSION['vendor_error']); ?></p>
                    <?php endif; ?>

                    <form action="submit_vendor.php" method="POST" id="vendorForm">

                        <!-- Privacy Notice -->
                        <div class="question-container">
                            <h2 class="question-title">Privacy Notice &amp; Consent <span class="required">*</span></h2>
                            <p class="question-desc">In accordance with the <strong>Data Privacy Act of 2012</strong>, all information you provide will be kept strictly confidential and used solely for academic purposes.</p>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="consent" value="yes" required><div class="custom-radio"></div><span class="option-text">I consent to participate</span></label>
                                <label class="option-card"><input type="radio" name="consent" value="no"><div class="custom-radio"></div><span class="option-text">I do not consent</span></label>
                            </div>
                        </div>

                        <!-- PART 1 -->
                        <h2 class="section-title">Part 1: Vendor Demographic Profile</h2>

                        <div class="question-container">
                            <h2 class="question-title">1. Name (Optional)</h2>
                            <p class="question-desc">Making this optional encourages honest responses.</p>
                            <input type="text" class="text-input" name="vendor_name" placeholder="Enter your name" maxlength="100">
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">2. Age <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="age" value="18-24" required><div class="custom-radio"></div><span class="option-text">18 – 24 years old</span></label>
                                <label class="option-card"><input type="radio" name="age" value="25-34"><div class="custom-radio"></div><span class="option-text">25 – 34 years old</span></label>
                                <label class="option-card"><input type="radio" name="age" value="35-44"><div class="custom-radio"></div><span class="option-text">35 – 44 years old</span></label>
                                <label class="option-card"><input type="radio" name="age" value="45-54"><div class="custom-radio"></div><span class="option-text">45 – 54 years old</span></label>
                                <label class="option-card"><input type="radio" name="age" value="55+"><div class="custom-radio"></div><span class="option-text">55 years old and above</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">3. Gender <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="gender" value="male" required><div class="custom-radio"></div><span class="option-text">Male</span></label>
                                <label class="option-card"><input type="radio" name="gender" value="female"><div class="custom-radio"></div><span class="option-text">Female</span></label>
                                <label class="option-card"><input type="radio" name="gender" value="prefer_not_to_say"><div class="custom-radio"></div><span class="option-text">Prefer not to say</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">4. What primary type of goods do you sell? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="goods_type" value="cooked_food" required><div class="custom-radio"></div><span class="option-text">Cooked Food / Snacks / Beverages</span></label>
                                <label class="option-card"><input type="radio" name="goods_type" value="fresh_produce"><div class="custom-radio"></div><span class="option-text">Fresh Produce / Meat / Groceries</span></label>
                                <label class="option-card"><input type="radio" name="goods_type" value="school_supplies"><div class="custom-radio"></div><span class="option-text">School Supplies / General Merchandise</span></label>
                                <label class="option-card"><input type="radio" name="goods_type" value="other"><div class="custom-radio"></div><span class="option-text">Other</span></label>
                            </div>
                            <input type="text" class="text-input mt-3" name="goods_type_other" placeholder="If Other, please specify" maxlength="200">
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">5. Is your business registered/legally permitted by the local government? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="registered" value="yes" required><div class="custom-radio"></div><span class="option-text">Yes</span></label>
                                <label class="option-card"><input type="radio" name="registered" value="no"><div class="custom-radio"></div><span class="option-text">No</span></label>
                                <label class="option-card"><input type="radio" name="registered" value="in_process"><div class="custom-radio"></div><span class="option-text">In the process of registration</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">6. How long have you been operating a stall at Q-Mart? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="operating_duration" value="less_1_year" required><div class="custom-radio"></div><span class="option-text">Less than 1 year</span></label>
                                <label class="option-card"><input type="radio" name="operating_duration" value="1_to_3_years"><div class="custom-radio"></div><span class="option-text">1 to 3 years</span></label>
                                <label class="option-card"><input type="radio" name="operating_duration" value="4_to_6_years"><div class="custom-radio"></div><span class="option-text">4 to 6 years</span></label>
                                <label class="option-card"><input type="radio" name="operating_duration" value="more_than_6_years"><div class="custom-radio"></div><span class="option-text">More than 6 years</span></label>
                            </div>
                        </div>

                        <!-- PART 2 -->
                        <h2 class="section-title">Part 2: Economic Impact (Effect on Sales)</h2>

                        <div class="question-container">
                            <h2 class="question-title">7. How often do RMCHS students purchase from your stall? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="student_purchase_freq" value="very_frequently" required><div class="custom-radio"></div><span class="option-text">Very Frequently (Daily)</span></label>
                                <label class="option-card"><input type="radio" name="student_purchase_freq" value="frequently"><div class="custom-radio"></div><span class="option-text">Frequently (A few times a week)</span></label>
                                <label class="option-card"><input type="radio" name="student_purchase_freq" value="occasionally"><div class="custom-radio"></div><span class="option-text">Occasionally (A few times a month)</span></label>
                                <label class="option-card"><input type="radio" name="student_purchase_freq" value="rarely_never"><div class="custom-radio"></div><span class="option-text">Rarely / Never</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">8. During which hours do you experience the highest volume of student customers? <span class="required">*</span></h2>
                            <p class="question-desc">Select all that apply.</p>
                            <div class="options-grid">
                                <label class="option-card"><input type="checkbox" name="highest_volume_hours[]" value="morning"><div class="custom-checkbox"></div><span class="option-text">Morning (Before school starts: 6:00 AM – 7:30 AM)</span></label>
                                <label class="option-card"><input type="checkbox" name="highest_volume_hours[]" value="midday"><div class="custom-checkbox"></div><span class="option-text">Midday (Lunch break: 11:30 AM – 1:00 PM)</span></label>
                                <label class="option-card"><input type="checkbox" name="highest_volume_hours[]" value="afternoon"><div class="custom-checkbox"></div><span class="option-text">Afternoon (Dismissal time: 3:00 PM – 5:00 PM)</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">9. Approximately what percentage of your daily sales comes from student customers? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="sales_percentage" value="less_10" required><div class="custom-radio"></div><span class="option-text">Less than 10%</span></label>
                                <label class="option-card"><input type="radio" name="sales_percentage" value="11_25"><div class="custom-radio"></div><span class="option-text">11% – 25% (About a quarter)</span></label>
                                <label class="option-card"><input type="radio" name="sales_percentage" value="26_50"><div class="custom-radio"></div><span class="option-text">26% – 50% (About half)</span></label>
                                <label class="option-card"><input type="radio" name="sales_percentage" value="more_50"><div class="custom-radio"></div><span class="option-text">More than 50%</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">10. How strongly do you agree that the school's operating hours dictate the flow of customers to your stall? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="dictate_flow" value="strongly_agree" required><div class="custom-radio"></div><span class="option-text">Strongly Agree</span></label>
                                <label class="option-card"><input type="radio" name="dictate_flow" value="agree"><div class="custom-radio"></div><span class="option-text">Agree</span></label>
                                <label class="option-card"><input type="radio" name="dictate_flow" value="neutral"><div class="custom-radio"></div><span class="option-text">Neutral</span></label>
                                <label class="option-card"><input type="radio" name="dictate_flow" value="disagree"><div class="custom-radio"></div><span class="option-text">Disagree</span></label>
                                <label class="option-card"><input type="radio" name="dictate_flow" value="strongly_disagree"><div class="custom-radio"></div><span class="option-text">Strongly Disagree</span></label>
                            </div>
                        </div>

                        <!-- PART 3 -->
                        <h2 class="section-title">Part 3: Social Dynamics (Relationship with Students)</h2>

                        <div class="question-container">
                            <h2 class="question-title">11. How would you rate your overall relationship with the student customers? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="relationship" value="excellent" required><div class="custom-radio"></div><span class="option-text">Excellent (Friendly and respectful)</span></label>
                                <label class="option-card"><input type="radio" name="relationship" value="good"><div class="custom-radio"></div><span class="option-text">Good (Polite and transactional)</span></label>
                                <label class="option-card"><input type="radio" name="relationship" value="fair"><div class="custom-radio"></div><span class="option-text">Fair (Neutral)</span></label>
                                <label class="option-card"><input type="radio" name="relationship" value="poor"><div class="custom-radio"></div><span class="option-text">Poor (Strained or difficult)</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">12. Have you experienced any of the following issues with student customers? <span class="required">*</span></h2>
                            <p class="question-desc">Select all that apply.</p>
                            <div class="options-grid">
                                <label class="option-card"><input type="checkbox" name="student_issues[]" value="none"><div class="custom-checkbox"></div><span class="option-text">No issues experienced</span></label>
                                <label class="option-card"><input type="checkbox" name="student_issues[]" value="loitering"><div class="custom-checkbox"></div><span class="option-text">Loitering or blocking the stall area</span></label>
                                <label class="option-card"><input type="checkbox" name="student_issues[]" value="noise"><div class="custom-checkbox"></div><span class="option-text">Noise or disruptive behavior</span></label>
                                <label class="option-card"><input type="checkbox" name="student_issues[]" value="disrespectful"><div class="custom-checkbox"></div><span class="option-text">Disrespectful language or attitude</span></label>
                                <label class="option-card"><input type="checkbox" name="student_issues[]" value="theft"><div class="custom-checkbox"></div><span class="option-text">Theft or unpaid items</span></label>
                                <label class="option-card"><input type="checkbox" name="student_issues[]" value="other"><div class="custom-checkbox"></div><span class="option-text">Other</span></label>
                            </div>
                            <input type="text" class="text-input mt-3" name="student_issues_other" placeholder="If Other, please specify" maxlength="200">
                        </div>

                        <!-- PART 4 -->
                        <h2 class="section-title">Part 4: Environmental Impact (Safety &amp; Cleanliness)</h2>

                        <div class="question-container">
                            <h2 class="question-title">13. How would you rate the overall cleanliness and orderliness of Q-Mart and its immediate surroundings? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="cleanliness_rating" value="5" required><div class="custom-radio"></div><span class="option-text">5 – Excellent</span></label>
                                <label class="option-card"><input type="radio" name="cleanliness_rating" value="4"><div class="custom-radio"></div><span class="option-text">4 – Good</span></label>
                                <label class="option-card"><input type="radio" name="cleanliness_rating" value="3"><div class="custom-radio"></div><span class="option-text">3 – Average</span></label>
                                <label class="option-card"><input type="radio" name="cleanliness_rating" value="2"><div class="custom-radio"></div><span class="option-text">2 – Poor</span></label>
                                <label class="option-card"><input type="radio" name="cleanliness_rating" value="1"><div class="custom-radio"></div><span class="option-text">1 – Very Poor</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">14. In your observation, how does the presence of students affect the cleanliness of the market area? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="student_effect_cleanliness" value="negative" required><div class="custom-radio"></div><span class="option-text">They negatively affect it (e.g., leaving trash, littering)</span></label>
                                <label class="option-card"><input type="radio" name="student_effect_cleanliness" value="neutral"><div class="custom-radio"></div><span class="option-text">They have no noticeable effect</span></label>
                                <label class="option-card"><input type="radio" name="student_effect_cleanliness" value="positive"><div class="custom-radio"></div><span class="option-text">They positively affect it (e.g., using bins properly, maintaining order)</span></label>
                            </div>
                        </div>

                        <button type="submit" class="submit-btn" id="vendorSubmitBtn">Submit Survey</button>

                    </form>

                <?php endif; ?>

            </div>
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-grid">
                <div class="footer-col footer-col--about">
                    <div class="footer-logo-wrap">
                        <img src="https://i.pinimg.com/736x/bf/78/0e/bf780e807c7d275a981d0d784cf88e64.jpg" alt="LOGO" class="footer-logo-img">
                    </div>
                    <p class="footer-about-text">For suggestions, questions, or concerns, feel free to contact our developers through the provided contact information. Your feedback helps us improve and provide better service to the community.</p>
                </div>
                <div class="footer-col footer-col--devs">
                    <h3 class="footer-col-title">Developers</h3>
                    <ul class="footer-devs-list">
                        <li><a href="https://www.facebook.com/neco.alvarez.2025" target="_blank">Neco G. Alvarez</a></li>
                        <li><a href="https://web.facebook.com/markgrason28" target="_blank">Mark Grason S. Avellana</a></li>
                        <li><a href="https://www.facebook.com/share/1CxrRUrH4N/" target="_blank">Angel Mharkie D. Cabales</a></li>
                        <li><a href="#" target="_blank">Jim Christian P. De Vera</a></li>
                        <li><a href="https://www.linkedin.com/in/venizemontojo/" target="_blank">Venize Mica M. Montojo</a></li>
                    </ul>
                </div>
                <div class="footer-col footer-col--contact">
                    <h3 class="footer-col-title">Contact Information</h3>
                    <ul class="footer-contact-list">
                        <li><span class="contact-icon">📩</span><div><span class="contact-label">Email</span><span class="contact-value">theStuds@gmail.com</span></div></li>
                        <li><span class="contact-icon">📞</span><div><span class="contact-label">Phone Number</span><span class="contact-value">0966-766-2512</span></div></li>
                        <li><span class="contact-icon">📍</span><div><span class="contact-label">Location</span><span class="contact-value">Institute of Technology, Pureza St., Sta. Mesa, Manila, Philippines 1016</span></div></li>
                    </ul>
                </div>
            </div>
            <div class="footer-divider"></div>
            <div class="footer-bottom">
                <p class="footer-privacy">Privacy Notice: All collected data will be used strictly for academic and research purposes only.</p>
                <p class="footer-copyright">© 2026 DIT 2-5 Group 4 — Institute of Technology, PUP</p>
            </div>
        </div>
    </footer>

    <script>
        const vendorForm = document.getElementById('vendorForm');
        if (vendorForm) {
            vendorForm.addEventListener('submit', function (e) {
                const hoursChecked = document.querySelectorAll('input[name="highest_volume_hours[]"]:checked');
                if (hoursChecked.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one peak hour for Question 8.');
                    return;
                }
                const issuesChecked = document.querySelectorAll('input[name="student_issues[]"]:checked');
                if (issuesChecked.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one option for Question 12 (Student Issues).');
                    return;
                }
                alert('Thank you! Your response has been submitted successfully.');
            });
        }
    </script>

</body>
</html>