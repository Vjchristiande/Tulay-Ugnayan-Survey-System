<?php
session_start();
require_once 'Config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}

$result    = $conn->query("SELECT * FROM student_responses");
// FIX: removed stray '/' after '[]'
$responses = [];
while ($row = $result->fetch_assoc()) {
    $responses[] = $row;
}
$total = count($responses);

function countField($responses, $field) {
    $tally = [];
    foreach ($responses as $r) {
        $val = $r[$field] ?? '';
        if (empty($val)) continue;
        $parts = explode(', ', $val);
        foreach ($parts as $part) {
            $part = trim($part);
            if (!empty($part)) {
                $tally[$part] = ($tally[$part] ?? 0) + 1;
            }
        }
    }
    return $tally;
}

function renderBars($tally, $total) {
    if (empty($tally)) {
        echo '<p style="font-size:12px;color:#888;font-style:italic;">No data yet.</p>';
        return;
    }
    foreach ($tally as $label => $count) {
        $pct = $total > 0 ? round(($count / $total) * 100, 1) : 0;
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
  <title>Student Survey Results</title>
  <style>
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: url('https://i.pinimg.com/736x/71/44/c5/7144c51731ea87ecc33c55ffde6be2e5.jpg');
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
      <a href="student_results.php" class="active">RESULTS</a>
      <a href="logout.php">LOGOUT</a>
    </div>
  </nav>

  <div class="card intro-card">
    <div class="card-header">
      <h2>Students' Survey Results</h2>
      <p>Tulay Ugnayan: Understanding Barriers Between Ramon Magsaysay (Cubao) High School and Q-Mart Market</p>
    </div>
    <div class="card-body">
      <p><b>
        This section presents feedback from Ramon Magsaysay School students to inform vendors
        at how Qmart Market's daily operations influence the learning environment of the school.
        These insights are intended to help vendors understand the school's needs regarding cases
        such as noise levels during class hours. This data will be used to help the vendors on
        understanding how the school may affect their environment and vice versa.
      </b></p>
    </div>
  </div>

  <div class="btn-row">
    <button class="btn-green" onclick="document.getElementById('results-section').classList.remove('hidden'); this.style.display='none';">See results</button>
  </div>

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

  <div class="card result-card hidden" id="results-section">
    <div class="card-header">
      <h2>Survey Results</h2>
    </div>
    <div class="card-body">

      <p class="intro-text">
        Based on <?php echo $total; ?> response<?php echo $total !== 1 ? 's' : ''; ?> collected so far.
      </p>

      <div class="chart-section">
        <h3>Buying Habits — Q5. How often do you visit or pass by Qmart Market?</h3>
        <?php renderBars(countField($responses, 'visit_freq'), $total); ?>
      </div>

      <div class="chart-section">
        <h3>Buying Habits — Q6. What do you usually buy at Qmart Market?</h3>
        <?php
          $buyTally = countField($responses, 'buy');
          $buyTotal = array_sum($buyTally);
          renderBars($buyTally, $buyTotal);
        ?>
      </div>

      <div class="chart-section">
        <h3>Buying Habits — Q7. How often do you use your allowance to buy something?</h3>
        <?php renderBars(countField($responses, 'allowance_use'), $total); ?>
      </div>

      <div class="chart-section">
        <h3>Effects on Studies — Q8. How much does Qmart Market help you in your studies?</h3>
        <?php renderBars(countField($responses, 'study_help'), $total); ?>
      </div>

      <div class="chart-section">
        <h3>Effects on Studies — Q9. Does the smell from Qmart Market distract you during classes?</h3>
        <?php renderBars(countField($responses, 'smell'), $total); ?>
      </div>

      <div class="chart-section">
        <h3>Effects on Studies — Q10. Overall effect of Qmart Market on school life</h3>
        <?php renderBars(countField($responses, 'overall_effect'), $total); ?>
      </div>

      <div class="chart-section">
        <h3>Safety &amp; Environment — Q11. Do you feel safe near Qmart Market?</h3>
        <?php renderBars(countField($responses, 'safety'), $total); ?>
      </div>

      <div class="chart-section">
        <h3>Safety &amp; Environment — Q12. Rate the overall cleanliness of the area</h3>
        <?php renderBars(countField($responses, 'cleanliness'), $total); ?>
      </div>

<div class="chart-section comments-section">
        <h3>Respondents' Comments &amp; Suggestions — Suggestions for Qmart Market to better serve students.</h3>
        <p class="comments-intro">The following are direct comments shared by student respondents during the survey.</p>

        <div class="comment-card">
          <div class="comment-badge">Student 1</div>
          <p class="comment-text">"I would suggest discounts for students especially in buying school supplies."</p>
        </div>

        <div class="comment-card">
          <div class="comment-badge">Student 2</div>
          <p class="comment-text">"Better cleanliness, some students find it hard to cross the overpass so revisions to the pathway to ramon from q-mart need to be made."</p>
        </div>

        <div class="comment-card">
          <div class="comment-badge">Student 3</div>
          <p class="comment-text">"not crowded less."</p>
        </div>

        <div class="comment-card">
          <div class="comment-badge">Student 4</div>
          <p class="comment-text">"I hope they value the safety of their customers in terms of belongings. A lot of robbing incidents happen in the market, including one of my friends."</p>
        </div>

        <div class="comment-card">
          <div class="comment-badge">Student 5</div>
          <p class="comment-text">"They can either increase security or hire more guards for further safety."</p>
        </div>
      </div>

      <div class="chart-section comments-section">
        <h3>Respondents' Comments &amp; Suggestions — Suggestions regarding safety and cleanliness near the market.</h3>

        <div class="comment-card">
          <div class="comment-badge">Student 1</div>
          <p class="comment-text">"Yes, cleaning programs. At least twice a month."</p>
        </div>

        <div class="comment-card">
          <div class="comment-badge">Student 2</div>
          <p class="comment-text">"I think mas better po if may magbabantay sa mga tulay na dinadaan mula sa school papunta sa qmart or vise versa. Sometimes po kasi may feeling na unsafe dumaan doon, lalo na kapag gabi."</p>
        </div>

        <div class="comment-card">
          <div class="comment-badge">Student 3</div>
          <p class="comment-text">"Clean up the area surrounding the market and hire more security staff to prevent some cases of sexual harassment to the students by some of the vendors and some pick-pockets preying on the young students."</p>
        </div>

        <div class="comment-card">
          <div class="comment-badge">Student 4</div>
          <p class="comment-text">"Implement stricter laws about robbery and neatness. Clean the path along E. Rodriguez Street."</p>
        </div>

        <div class="comment-card">
          <div class="comment-badge">Student 5</div>
          <p class="comment-text">"They can either increase security or hire more guards for further safety."</p>
        </div>
      </div>

    </div>
  </div>
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