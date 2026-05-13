<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Vendor') {
    header('Location: Login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home | Survey System</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style2.css">
    </head>
    <body>
        <!-- NAVIGATION -->
        <section id="home">
            <nav class="navbar">
                <div class="logo">
                    <img src="https://i.pinimg.com/736x/bf/78/0e/bf780e807c7d275a981d0d784cf88e64.jpg" alt="Logo" style="height: 44px; width: 44px; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="nav-links">
                    <a href="V_Home.php">HOME</a>
                    <a href="VendorSurveyForm.php">SURVEY</a>
                    <a href="student_results.php">RESULTS</a>
                    <a href="logout.php" class="logout-btn">LOGOUT</a>
                </div>
            </nav>

            <!-- HEADER CONTENT -->
            <div class="header">
                <h1>Welcome, <span id="username" style="color: gold;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);"><?php echo htmlspecialchars($_SESSION['name']); ?></span>!</h1>
                <p>Ready to share your experience?</p>
            </div>
        </section>
</section>

<section id="about-vendor">
    <div class="content-box-right">
        <div class="community-text">
            <h3>Tulay Ugnayan</h3>
            <p>Day after day, students fill the streets around Q-Mart—they are your customers, your neighbors, and a big part of what makes this place alive. But how often do their habits, their schedules, and their presence truly get considered from your side of the stall?</p>
            <p><strong>Tulay Ugnayan</strong> is a digital survey designed to bridge the gap between vendors and students—helping both sides share their experiences, understand each other's world, and work together to build a better, more connected community. Because when both voices are heard, real change begins.</p>
            <p>Share your experiences as a Q-Mart vendor and help build a real understanding between you and Ramon Magsaysay (Cubao) High School students. Your perspective matters. Together, we can turn a shared neighborhood into a stronger community.</p>
        </div>
    </div>
</section>

        <div class="divider"></div>

        <!-- SURVEY BUTTON -->
        <section id="survey" style="background-image: url('https://i.imgur.com/OiQGVwR.jpeg');">
            <div class="button">
                <div class="community">
                    <img src="https://i.imgur.com/OfOm5Ad.png" alt="Vendor Icon">
                    <h3>VENDOR SURVEY</h3>
                    <button type="button" onclick="window.location.href='VendorSurveyForm.php'">TAKE SURVEY</button>
                    <p>Share your experience as a QMart Vendor.</p>
                </div>
            </div>
        </section>

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

        <!-- ANIMATION -->
        <script>
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    } else {
                        entry.target.classList.remove('visible');
                    }
                });
            });

            observer.observe(document.querySelector('.button'));
            if(document.querySelector('.content-box-right')) {
        observer.observe(document.querySelector('.content-box-right'));
    }
        </script>
    </body>
</html>