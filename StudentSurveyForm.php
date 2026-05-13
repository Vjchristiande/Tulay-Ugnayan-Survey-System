<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header('Location: Login.php');
    exit();
}

require_once 'Config.php';

$user_id = (int) $_SESSION['user_id'];
$already = $conn->prepare("SELECT id FROM student_responses WHERE user_id = ?");
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
    <title>Student Experience Survey</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="StudentForm.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <img src="https://i.pinimg.com/736x/bf/78/0e/bf780e807c7d275a981d0d784cf88e64.jpg" alt="Logo" style="height:44px;width:44px;object-fit:cover;border-radius:50%;">
        </div>
        <div class="nav-links">
            <a href="S_Home.php">HOME</a>
            <a href="#" class="active">SURVEY</a>
            <a href="vendor_results.php">RESULTS</a>
            <a href="logout.php" class="logout-btn">LOGOUT</a>
        </div>
    </nav>

    <div class="split-container">
        <div class="left-content">
            <div class="form-wrapper">

                <div class="form-header">
                    <span class="form-tag">Community Survey</span>
                    <h1 class="form-title">Student Experience Survey</h1>
                    <p class="form-description">Your answers will help us understand how the nearby Qmart Market affects your school life and daily experiences as a student.</p>
                </div>

                <div class="notice-block">
                    <strong>Before you begin:</strong>
                    In partial fulfillment of the requirements for the subject Web Development, we, the students of DIT 2-5 (Group 4), are conducting this survey as part of our project titled
                    <em>"Tulay Ugnayan: Understanding Barriers Between Ramon Magsaysay Cubao High School and Q-mart Market."</em>
                    This survey aims to gather insights on how the proximity of Ramon Magsaysay Cubao High School and Qmart Market influences students' daily experiences, particularly in terms of study habits, environment, and safety.
                </div>

                <?php if ($has_submitted): ?>
                    <div class="already-submitted-banner">
                        <strong>⚠ You have already submitted a response.</strong><br>
                        Only one submission is allowed per account. Thank you for participating!
                        <br><br>
                        <a href="vendor_results.php" style="color:#e65100;font-weight:600;">View Survey Results →</a>
                    </div>

                <?php else: ?>

                    <?php if (!empty($_SESSION['student_error'])): ?>
                        <p style="color:red;text-align:center;"><?php echo htmlspecialchars($_SESSION['student_error']); unset($_SESSION['student_error']); ?></p>
                    <?php endif; ?>

                    <form id="mainForm" action="submit_student.php" method="POST">

                        <!-- Privacy & Consent -->
                        <div class="question-container">
                            <h2 class="question-title">Privacy Notice &amp; Consent <span class="required">*</span></h2>
                            <p class="question-desc">In accordance with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>, all information you provide will be kept strictly confidential, used solely for academic purposes, and will not be shared with third parties without your consent. Participation is entirely voluntary.</p>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="consent" value="yes" required><div class="custom-radio"></div><span class="option-text">I consent to participate</span></label>
                                <label class="option-card"><input type="radio" name="consent" value="no"><div class="custom-radio"></div><span class="option-text">I do not consent</span></label>
                            </div>
                        </div>

                        <!-- PART 1 -->
                        <h2 class="section-title">Part 1: Student Information</h2>

                        <div class="question-container">
                            <h2 class="question-title">1. Name <span class="optional">(Optional)</span></h2>
                            <input type="text" class="text-input" name="fullname" placeholder="Full Name" maxlength="100">
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">2. Grade Level <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="grade" value="Grade 7" required><div class="custom-radio"></div><span class="option-text">Grade 7</span></label>
                                <label class="option-card"><input type="radio" name="grade" value="Grade 8"><div class="custom-radio"></div><span class="option-text">Grade 8</span></label>
                                <label class="option-card"><input type="radio" name="grade" value="Grade 9"><div class="custom-radio"></div><span class="option-text">Grade 9</span></label>
                                <label class="option-card"><input type="radio" name="grade" value="Grade 10"><div class="custom-radio"></div><span class="option-text">Grade 10</span></label>
                                <label class="option-card"><input type="radio" name="grade" value="Grade 11"><div class="custom-radio"></div><span class="option-text">Grade 11</span></label>
                                <label class="option-card"><input type="radio" name="grade" value="Grade 12"><div class="custom-radio"></div><span class="option-text">Grade 12</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">3. Age <span class="required">*</span></h2>
                            <p class="question-desc">Must be between 10 and 25 years old.</p>
                            <input type="number" class="text-input" name="age" id="ageInput"
                                min="10" max="25" step="1"
                                placeholder="Enter your age (10–25)"
                                style="max-width:200px;"
                                required>
                            <p class="age-error-msg" id="ageError"></p>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">4. Gender <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="gender" value="Male" required><div class="custom-radio"></div><span class="option-text">Male</span></label>
                                <label class="option-card"><input type="radio" name="gender" value="Female"><div class="custom-radio"></div><span class="option-text">Female</span></label>
                                <label class="option-card"><input type="radio" name="gender" value="Non-binary"><div class="custom-radio"></div><span class="option-text">Non-binary</span></label>
                                <label class="option-card"><input type="radio" name="gender" value="Prefer not to say"><div class="custom-radio"></div><span class="option-text">Prefer not to say</span></label>
                            </div>
                        </div>

                        <!-- PART 2 -->
                        <h2 class="section-title">Part 2: Buying Habits</h2>

                        <div class="question-container">
                            <h2 class="question-title">5. How often do you visit or pass by Qmart Market? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="visit_freq" value="Never" required><div class="custom-radio"></div><span class="option-text">Never</span></label>
                                <label class="option-card"><input type="radio" name="visit_freq" value="Rarely"><div class="custom-radio"></div><span class="option-text">Rarely</span></label>
                                <label class="option-card"><input type="radio" name="visit_freq" value="Once a week"><div class="custom-radio"></div><span class="option-text">Once a week</span></label>
                                <label class="option-card"><input type="radio" name="visit_freq" value="Several times a week"><div class="custom-radio"></div><span class="option-text">Several times a week</span></label>
                                <label class="option-card"><input type="radio" name="visit_freq" value="Every day"><div class="custom-radio"></div><span class="option-text">Every day</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                        <h2 class="question-title">6. What do you usually buy at Qmart Market? <span class="required">*</span></h2>
                        <p class="question-desc">Select all that apply.</p>
                        <div class="options-grid">
                            <label class="option-card"><input type="checkbox" name="buy[]" value="Food and snacks"><div class="custom-checkbox"></div><span class="option-text">Food and snacks</span></label>
                            <label class="option-card"><input type="checkbox" name="buy[]" value="School supplies"><div class="custom-checkbox"></div><span class="option-text">School supplies (notebooks, pens, etc.)</span></label>
                            <label class="option-card"><input type="checkbox" name="buy[]" value="Drinks"><div class="custom-checkbox"></div><span class="option-text">Drinks / beverages</span></label>
                            <label class="option-card"><input type="checkbox" name="buy[]" value="Clothing"><div class="custom-checkbox"></div><span class="option-text">Clothing or accessories</span></label>
                            <label class="option-card"><input type="checkbox" name="buy[]" value="Groceries"><div class="custom-checkbox"></div><span class="option-text">Groceries for home</span></label>
                            <label class="option-card"><input type="checkbox" name="buy[]" value="Nothings"><div class="custom-checkbox"></div><span class="option-text">I don't buy anything / I just pass by</span></label>
                            <label class="option-card"><input type="checkbox" name="buy[]" value="Other" id="buyOtherCheck"><div class="custom-checkbox"></div><span class="option-text">Other</span></label>
                        </div>
                    </div>

                        <div class="question-container">
                            <h2 class="question-title">7. How often do you use your allowance to buy something in Qmart Market? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="allowance_use" value="Never" required><div class="custom-radio"></div><span class="option-text">Never</span></label>
                                <label class="option-card"><input type="radio" name="allowance_use" value="Rarely"><div class="custom-radio"></div><span class="option-text">Rarely</span></label>
                                <label class="option-card"><input type="radio" name="allowance_use" value="Sometimes"><div class="custom-radio"></div><span class="option-text">Sometimes</span></label>
                                <label class="option-card"><input type="radio" name="allowance_use" value="Often"><div class="custom-radio"></div><span class="option-text">Often</span></label>
                                <label class="option-card"><input type="radio" name="allowance_use" value="Almost always"><div class="custom-radio"></div><span class="option-text">Almost always</span></label>
                            </div>
                        </div>

                        <!-- PART 3 -->
                        <h2 class="section-title">Part 3: Effects on Studies</h2>

                        <div class="question-container">
                            <h2 class="question-title">8. How much does Qmart Market help you in your studies? <span class="required">*</span></h2>
                            <p class="question-desc">e.g., buying school materials, printing, etc.</p>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="study_help" value="Not helpful at all" required><div class="custom-radio"></div><span class="option-text">Not helpful at all</span></label>
                                <label class="option-card"><input type="radio" name="study_help" value="Unhelpful"><div class="custom-radio"></div><span class="option-text">Unhelpful</span></label>
                                <label class="option-card"><input type="radio" name="study_help" value="Neutral"><div class="custom-radio"></div><span class="option-text">Neutral</span></label>
                                <label class="option-card"><input type="radio" name="study_help" value="Helpful"><div class="custom-radio"></div><span class="option-text">Helpful</span></label>
                                <label class="option-card"><input type="radio" name="study_help" value="Very helpful"><div class="custom-radio"></div><span class="option-text">Very helpful</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">9. Does the smell from Qmart Market distract you during classes? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="smell" value="No smell" required><div class="custom-radio"></div><span class="option-text">No smell</span></label>
                                <label class="option-card"><input type="radio" name="smell" value="Not noticeable"><div class="custom-radio"></div><span class="option-text">Not noticeable</span></label>
                                <label class="option-card"><input type="radio" name="smell" value="Neutral"><div class="custom-radio"></div><span class="option-text">Neutral</span></label>
                                <label class="option-card"><input type="radio" name="smell" value="Noticeable smell"><div class="custom-radio"></div><span class="option-text">Noticeable smell</span></label>
                                <label class="option-card"><input type="radio" name="smell" value="Strong smell"><div class="custom-radio"></div><span class="option-text">Strong smell</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">10. Overall, do you think Qmart Market has a positive or negative effect on your school life? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="overall_effect" value="Very negative" required><div class="custom-radio"></div><span class="option-text">Very negative</span></label>
                                <label class="option-card"><input type="radio" name="overall_effect" value="Somewhat negative"><div class="custom-radio"></div><span class="option-text">Somewhat negative</span></label>
                                <label class="option-card"><input type="radio" name="overall_effect" value="Neutral"><div class="custom-radio"></div><span class="option-text">Neutral</span></label>
                                <label class="option-card"><input type="radio" name="overall_effect" value="Somewhat positive"><div class="custom-radio"></div><span class="option-text">Somewhat positive</span></label>
                                <label class="option-card"><input type="radio" name="overall_effect" value="Very positive"><div class="custom-radio"></div><span class="option-text">Very positive</span></label>
                            </div>
                        </div>

                        <!-- PART 4 -->
                        <h2 class="section-title">Part 4: Safety &amp; Environment</h2>

                        <div class="question-container">
                            <h2 class="question-title">11. Do you feel safe walking through or near Qmart Market on your way to school or home? <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="safety" value="Never safe" required><div class="custom-radio"></div><span class="option-text">Never safe</span></label>
                                <label class="option-card"><input type="radio" name="safety" value="Often unsafe"><div class="custom-radio"></div><span class="option-text">Often unsafe</span></label>
                                <label class="option-card"><input type="radio" name="safety" value="Sometimes unsafe"><div class="custom-radio"></div><span class="option-text">Sometimes unsafe</span></label>
                                <label class="option-card"><input type="radio" name="safety" value="Usually safe"><div class="custom-radio"></div><span class="option-text">Usually safe</span></label>
                                <label class="option-card"><input type="radio" name="safety" value="Always safe"><div class="custom-radio"></div><span class="option-text">Always safe</span></label>
                            </div>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">12. Rate the overall cleanliness of the area between your school and the market. <span class="required">*</span></h2>
                            <div class="options-grid">
                                <label class="option-card"><input type="radio" name="cleanliness" value="Very dirty" required><div class="custom-radio"></div><span class="option-text">1 — Very dirty</span></label>
                                <label class="option-card"><input type="radio" name="cleanliness" value="Dirty"><div class="custom-radio"></div><span class="option-text">2 — Dirty</span></label>
                                <label class="option-card"><input type="radio" name="cleanliness" value="Neutral"><div class="custom-radio"></div><span class="option-text">3 — Neutral</span></label>
                                <label class="option-card"><input type="radio" name="cleanliness" value="Clean"><div class="custom-radio"></div><span class="option-text">4 — Clean</span></label>
                                <label class="option-card"><input type="radio" name="cleanliness" value="Very clean"><div class="custom-radio"></div><span class="option-text">5 — Very clean</span></label>
                            </div>
                        </div>

                        <!-- PART 5 -->
                        <h2 class="section-title">Part 5: Suggestions</h2>

                        <div class="question-container">
                            <h2 class="question-title">13. What improvements would you suggest for Qmart Market to better serve students?</h2>
                            <textarea class="text-input" name="suggest_market" rows="4" placeholder="Write your suggestions here…" maxlength="1000" style="resize:vertical;min-height:90px"></textarea>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">14. Do you have any suggestions regarding safety and cleanliness near the market?</h2>
                            <textarea class="text-input" name="suggest_safety" rows="4" placeholder="Write your suggestions here…" maxlength="1000" style="resize:vertical;min-height:90px"></textarea>
                        </div>

                        <div class="question-container">
                            <h2 class="question-title">15. Any other comments or concerns?</h2>
                            <textarea class="text-input" name="other_comments" rows="4" placeholder="Additional comments…" maxlength="1000" style="resize:vertical;min-height:90px"></textarea>
                        </div>

                        <button type="submit" class="submit-btn" id="submitBtn">Submit Survey</button>

                    </form>

                <?php endif; ?>

            </div>
        </div>

        <div class="right-image"></div>
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
    document.getElementById('buyOtherCheck').addEventListener('change', function () {
        const txt = document.getElementById('buyOtherText');
        txt.classList.toggle('active', this.checked);
        if (this.checked) txt.focus(); else txt.value = '';
    });

    function validateAge(val) {
        const errEl = document.getElementById('ageError');
        const input  = document.getElementById('ageInput');
        const age    = parseInt(val, 10);

        if (val === '' || isNaN(age)) {
            errEl.textContent = 'Please enter your age.';
            errEl.style.display = 'block';
            input.setCustomValidity('Please enter your age.');
        } else if (age < 10) {
            errEl.textContent = 'Age must be at least 10 years old to participate.';
            errEl.style.display = 'block';
            input.setCustomValidity('Age must be at least 10.');
        } else if (age > 25) {
            errEl.textContent = 'Age must not exceed 25 years old for student respondents.';
            errEl.style.display = 'block';
            input.setCustomValidity('Age must not exceed 25.');
        } else {
            errEl.textContent = '';
            errEl.style.display = 'none';
            input.setCustomValidity('');
        }
    }

    document.getElementById('ageInput').addEventListener('input', function () {
        validateAge(this.value);
    });

    const form = document.getElementById('mainForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); 

            const ageVal = document.getElementById('ageInput').value;
            validateAge(ageVal);
            if (document.getElementById('ageInput').validity.customError) {
                document.getElementById('ageInput').focus();
                return;
            }

            const buyChecked = document.querySelectorAll('input[name="buy[]"]:checked');
            if (buyChecked.length === 0) {
                alert('Please select at least one option for Question 6 (What do you usually buy).');
                return;
            }

            alert('Thank you! Your response has been submitted successfully.');
            form.submit(); 
        });
    }
</script>

</body>
</html>