<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home | Survey System</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <!-- NAVIGATION -->
        <section id="home">
            <nav class="navbar">
                <div class="logo">
                    <img src="https://i.pinimg.com/736x/bf/78/0e/bf780e807c7d275a981d0d784cf88e64.jpg" alt="Logo" style="height: 44px; width: 44px; object-fit: cover; border-radius: 50%;">
                </div>
                <div class="nav-links">
                    <a href="Login.php" class="logout-btn">LOGIN</a>
                </div>
            </nav>

            <!-- HEADER CONTENT -->
            <div class="header">
                <h1>Tulay Ugnayan: Understanding Barriers Between<br>
                Ramon Magsaysay (Cubao) High School and Q-Mart Market</h1>
                <p>A digital survey designed to bridge the gap between students and vendors—helping<br>
                both sides share their experiences, understand each other, and build a better<br>
                community together</p>
            </div>
        </section>

        <div class="divider"></div>

        <!-- SURVEY BUTTON -->
        <section id="survey">
            <div class="button">
                <div class="community">
                    <img src="https://i.imgur.com/OfOm5Ad.png" alt="Vendor Icon">
                    <img src="https://i.imgur.com/WWx2ogc.png" alt="Student Icon">
                    <h3>COMMUNITY SURVEY</h3>
                    <button type="button" onclick="window.location.href='Login.php'">LOG IN</button>
                    <p>A Survey for RMCHS Students & QMart Vendors.</p>
                </div> <!-- NECO, CHECK MO MESSAGE KO SA GC -->
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
        </script>
    </body>
</html>