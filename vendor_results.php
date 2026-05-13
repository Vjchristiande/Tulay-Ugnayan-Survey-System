<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) { 
    header('Location: Login.php'); 
    exit(); 
}

//get all vendor responses from database
$result    = $conn->query("SELECT * FROM vendor_responses"); //get all vendor responses
$responses = [];
while ($row = $result->fetch_assoc()) {  
    $responses[] = $row; //add each row to responses array
}
$total = count($responses);  

//label maps - stored values to display labels
$LABELS = [
    'age'                        => ['18-24' => '18–24 years old', '25-34' => '25–34 years old', '35-44' => '35–44 years old', '45-54' => '45–54 years old', '55+' => '55 and above'],
    'gender'                     => ['male' => 'Male', 'female' => 'Female', 'prefer_not_to_say' => 'Prefer not to say'],
    'goods_type'                 => ['cooked_food' => 'Cooked Food / Snacks / Beverages', 'fresh_produce' => 'Fresh Produce / Meat / Groceries', 'school_supplies' => 'School Supplies / General Merchandise', 'other' => 'Others (Clothes, Shoes, Print & Xerox)'],
    'registered'                 => ['yes' => 'Yes', 'no' => 'No', 'in_process' => 'In the process of registration'],
    'operating_duration'         => ['less_1_year' => 'Less than 1 year', '1_to_3_years' => '1 to 3 years', '4_to_6_years' => '4 to 6 years', 'more_than_6_years' => 'More than 6 years'],
    'student_purchase_freq'      => ['very_frequently' => 'Very Frequently (Daily)', 'frequently' => 'Frequently (A few times a week)', 'occasionally' => 'Occasionally (A few times a month)', 'rarely_never' => 'Rarely / Never'],
    'highest_volume_hours'       => ['morning' => 'Morning (6:00 AM – 7:30 AM)', 'midday' => 'Midday (11:30 AM – 1:00 PM)', 'afternoon' => 'Afternoon (3:00 PM – 5:00 PM)'],
    'sales_percentage'           => ['less_10' => 'Less than 10%', '11_25' => '11%–25%', '26_50' => '26%–50%', 'more_50' => 'More than 50%'],
    'dictate_flow'               => ['strongly_agree' => 'Strongly Agree', 'agree' => 'Agree', 'neutral' => 'Neutral', 'disagree' => 'Disagree', 'strongly_disagree' => 'Strongly Disagree'],
    'relationship'               => ['excellent' => 'Excellent (Friendly and respectful)', 'good' => 'Good (Polite and transactional)', 'fair' => 'Fair (Neutral)', 'poor' => 'Poor (Strained or difficult)'],
    'student_issues'             => ['none' => 'No issues experienced', 'loitering' => 'Loitering or blocking stall area', 'noise' => 'Noise or disruptive behavior', 'disrespectful' => 'Disrespectful language or attitude', 'theft' => 'Theft or unpaid items', 'other' => 'Other'],
    'cleanliness_rating'         => ['5' => '5 — Excellent', '4' => '4 — Good', '3' => '3 — Average', '2' => '2 — Poor', '1' => '1 — Very Poor'],
    'student_effect_cleanliness' => ['negative' => 'They negatively affect it (e.g., littering)', 'neutral' => 'They have no noticeable effect', 'positive' => 'They positively affect it (e.g., using bins properly)'],
];

//helper function to count values for a field
function countField($responses, $field) {
    $tally = [];
    foreach ($responses as $r) {  
        $val = $r[$field] ?? ''; 
        if (empty($val)) continue; 
        $parts = explode(', ', $val); //split comma separated values (for checkboxes)
        foreach ($parts as $part) { 
            $part = trim($part); 
            if (!empty($part)) { 
                $tally[$part] = ($tally[$part] ?? 0) + 1; 
            }
        }
    }
    return $tally; 
}

//helper function to render bar charts with label maps
function renderBars($tally, $total, $labels = []) {
    if (empty($tally)) { 
        echo '<p style="font-size:12px;color:#888;font-style:italic;">No data yet.</p>';
        return;
    }
    foreach ($tally as $val => $count) { 
        $label = isset($labels[$val]) ? $labels[$val] : $val; //get display label or use raw value
        $pct   = $total > 0 ? round(($count / $total) * 100, 1) : 0; //calculate percentage
        echo '
        <div class="bar-item">
            <div class="bar-label-row">
                <span>' . htmlspecialchars($label) . '</span>
                <span>' . $pct . '% (' . $count . ')</span>
            </div>
            <div class="bar-track">
                <div class="bar-fill" style="width:' . $pct . '%;">' . ($pct > 8 ? $pct . '%' : '') . '</div>
            </div>
        </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Vendor Survey Results</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: url('https://i.pinimg.com/736x/11/f4/10/11f41038f04e81e10c0d9d49b850eeaf.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      opacity: 0.25;
      z-index: -1;
    }
    body { position: relative; }
  </style>
  <link rel="stylesheet" href="results.css" />
</head>
<body>

<nav class="navbar">
    <div class="logo">
        <img src="https://i.pinimg.com/736x/bf/78/0e/bf780e807c7d275a981d0d784cf88e64.jpg" alt="Logo" style="height: 44px; width: 44px; object-fit: cover; border-radius: 50%;">
    </div>
    <div class="nav-links">
        <?php if ($_SESSION['role'] === 'Student'): ?>
            <a href="S_Home.php">HOME</a>
            <a href="StudentSurveyForm.php">SURVEY</a>
        <?php else: ?>
            <a href="V_Home.php">HOME</a>
            <a href="VendorSurveyForm.php">SURVEY</a>
        <?php endif; ?>
        <a href="vendor_results.php" class="active">RESULTS</a>
        <a href="logout.php">LOGOUT</a> 
    </div>
</nav>

<!-- SUCCESS MESSAGE -->
<?php if (!empty($_SESSION['student_success'])): ?>
    <div class="success-toast">
        <?php echo $_SESSION['student_success']; unset($_SESSION['student_success']); ?>
    </div>
<?php endif; ?>

  <!-- INTRO CARD -->
  <div class="card intro-card">
    <div class="card-header">
      <h2>Vendors' Survey Results</h2>
      <p>Tulay Ugnayan: Understanding Barriers Between Ramon Magsaysay (Cubao) High School and Q-Mart Market</p>
    </div>
    <div class="card-body">
      <p><b>
        This section presents feedback from Qmart Market vendors to inform students from
        Ramon Magsaysay School the vendor's perspective on their shared environment. These
        data are intended to provide students with a clear understanding of how school
        activities impact vendor operations everyday. By viewing these results, the school
        can identify practical ways to coordinate better with the vendors.
        </b></p>
    </div>
  </div>

  <!-- BUTTON -->
  <div class="btn-row">
    <button class="btn-green" onclick="document.getElementById('results-section').classList.remove('hidden'); this.style.display='none';">See results</button>
  </div>

  <!-- POPUP — only shows if no responses in database -->
  <?php if ($total === 0): ?>
  <div class="popup-overlay active" id="popup">
    <div class="popup-box">
      <div class="popup-header"><h3>Notice</h3></div>
      <div class="popup-body">
        <p>Sorry, the survey data is not available yet. Results will be displayed here once all responses have been collected and processed. Please check back later.</p>
        <button class="popup-close" onclick="document.getElementById('popup').classList.remove('active')">Got it</button>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- RESULTS CARD -->
  <div class="card result-card hidden" id="results-section">
    <div class="card-header">
      <h2>Survey Results</h2>
    </div>
    <div class="card-body">

      <p class="intro-text">
        Based on <?php echo $total; ?> response<?php echo $total !== 1 ? 's' : ''; ?> collected so far.
      </p>

      <!-- Goods Type -->
      <div class="chart-section">
        <h3>Vendor Information — Type of Goods Sold</h3>
        <?php renderBars(countField($responses, 'goods_type'), $total, $LABELS['goods_type']); ?>
      </div>

      <!-- Registered -->
      <div class="chart-section">
        <h3>Vendor Information — Business Registration Status</h3>
        <?php renderBars(countField($responses, 'registered'), $total, $LABELS['registered']); ?>
      </div>

      <!-- Operating Duration -->
      <div class="chart-section">
        <h3>Vendor Information — Years Operating at Q-Mart</h3>
        <?php renderBars(countField($responses, 'operating_duration'), $total, $LABELS['operating_duration']); ?>
      </div>

      <!-- Q7: Student Purchase Frequency -->
      <div class="chart-section">
        <h3>Effect of Students on Sales — Q7. How often do RMCHS students purchase from your stall?</h3>
        <?php renderBars(countField($responses, 'student_purchase_freq'), $total, $LABELS['student_purchase_freq']); ?>
      </div>

      <!-- Q8: Peak Hours (multi-select checkbox) -->
      <div class="chart-section">
        <h3>Effect of Students on Sales — Q8. Peak hours of student customers</h3>
        <?php
          $hoursTally = countField($responses, 'highest_volume_hours'); // Count hours values
          $hoursTotal = array_sum($hoursTally); // Total individual selections for checkboxes
          renderBars($hoursTally, $hoursTotal, $LABELS['highest_volume_hours']); // Use hoursTotal not $total
        ?>
      </div>

      <!-- Q9: Sales Percentage -->
      <div class="chart-section">
        <h3>Effect of Students on Sales — Q9. Percentage of daily sales from students</h3>
        <?php renderBars(countField($responses, 'sales_percentage'), $total, $LABELS['sales_percentage']); ?>
      </div>

      <!-- Q10: Dictate Flow -->
      <div class="chart-section">
        <h3>Effect of Students on Sales — Q10. School hours dictate customer flow</h3>
        <?php renderBars(countField($responses, 'dictate_flow'), $total, $LABELS['dictate_flow']); ?>
      </div>

      <!-- Q11: Relationship -->
      <div class="chart-section">
        <h3>Social Dynamics — Q11. Overall relationship with student customers</h3>
        <?php renderBars(countField($responses, 'relationship'), $total, $LABELS['relationship']); ?>
      </div>

      <!-- Q12: Student Issues (multi-select checkbox) -->
      <div class="chart-section">
        <h3>Social Dynamics — Q12. Issues experienced with student customers</h3>
        <?php
          $issuesTally = countField($responses, 'student_issues'); // Count issues values
          $issuesTotal = array_sum($issuesTally); // Total individual selections for checkboxes
          renderBars($issuesTally, $issuesTotal, $LABELS['student_issues']); // Use issuesTotal not $total
        ?>
      </div>

      <!-- Q13: Cleanliness Rating -->
      <div class="chart-section">
        <h3>Environmental Impact — Q13. Cleanliness &amp; orderliness of Q-Mart</h3>
        <?php renderBars(countField($responses, 'cleanliness_rating'), $total, $LABELS['cleanliness_rating']); ?>
      </div>

      <!-- Q14: Student Effect on Cleanliness -->
      <div class="chart-section">
        <h3>Environmental Impact — Q14. How does student presence affect cleanliness?</h3>
        <?php renderBars(countField($responses, 'student_effect_cleanliness'), $total, $LABELS['student_effect_cleanliness']); ?>
      </div>

    </div>
  </div>
 <!-- FOOTER -->
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-grid">

                <!-- Column 1: Logo + About -->
                <div class="footer-col footer-col--about">
                    <div class="footer-logo-wrap">
                        <img src="https://i.pinimg.com/736x/bf/78/0e/bf780e807c7d275a981d0d784cf88e64.jpg" alt="LOGO" class="footer-logo-img">
                    </div>
                    <p class="footer-about-text">
                        For suggestions, questions, or concerns, feel free to contact our developers through the provided contact information.
                        Your feedback helps us improve and provide better service to the community.
                    </p>
                </div>

                <!-- Column 2: Developers -->
                <div class="footer-col footer-col--devs">
                <h3 class="footer-col-title">Developers</h3>
                   <ul class="footer-devs-list">
                      <li><a href="https://www.facebook.com/neco.alvarez.2025" target="_blank">Neco G. Alvarez</a></li>
                      <li><a href="https://web.facebook.com/markgrason28" target="_blank">Mark Grason S. Avellana</a></li>
                      <li><a href="https://www.facebook.com/share/1CxrRUrH4N/" target="_blank">Angel Mharkie D. Cabales</a></li>
                      <li><a href="https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.linkedin.com%2Fin%2Fjim-christian-de-vera-437178337%2F%3Ffbclid%3DIwZXh0bgNhZW0CMTAAYnJpZBExUUtUdDh5aklVaUtabm5nZnNydGMGYXBwX2lkEDIyMjAzOTE3ODgyMDA4OTIAAR5IavhRgCmoAOZdgFaK_1rHSly4iv4b7UP36XqMX4RyX9m_GiQzlh7fDEJpdQ_aem_jqBPMbIgiLY-bfWIbqMfIQ&h=AUBtTX3hycR0YAve6vkjQHxTx8tELeqy7CZEqtxb0ltqs1nCSkCWmp5G4mnK2YSVsztue4RwSm-MLUtYACvSIo5-xIA8BUT4vHuIbXGI2BQS3ak7X-dNujgpBk2pr-U2gKxMp3AytWQLm0Seku_dFw" target="_blank">Jim Christian P. De Vera</a></li>
                      <li><a href="https://www.linkedin.com/in/venizemontojo/" target="_blank">Venize Mica M. Montojo</a></li>
                     </ul> 
                </div>

                <!-- Column 4: Contact -->
                <div class="footer-col footer-col--contact">
                    <h3 class="footer-col-title">Contact Information</h3>
                    <ul class="footer-contact-list">
                        <li>
                            <span class="contact-icon">📩</span>
                            <div>
                                <span class="contact-label">Email</span>
                                <span class="contact-value">theStuds@gmail.com</span>
                            </div>
                        </li>
                        <li>
                            <span class="contact-icon">📞</span>
                            <div>
                                <span class="contact-label">Phone Number</span>
                                <span class="contact-value">0966-766-2512</span>
                            </div>
                        </li>
                        <li>
                            <span class="contact-icon">📍</span>
                            <div>
                                <span class="contact-label">Location</span>
                                <span class="contact-value">Institute of Technology, Pureza St., Sta. Mesa, Manila, Philippines 1016</span>
                            </div>
                        </li>
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
</body>
</html>