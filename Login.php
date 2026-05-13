<?php session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'Student') {
        header('Location: S_Home.php');
    } elseif ($_SESSION['role'] === 'Vendor') {
        header('Location: V_Home.php');
    } else {
        session_destroy();
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | Tulay Ugnayan</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #333;
        }
        .terms-row input[type="checkbox"] {
            width: 18px;
            min-width: 18px;
            height: 18px;
            margin: 0;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #2d5a1b;
        }
        .terms-row a {
            color: #2d5a1b;
            text-decoration: underline;
        }
        .password-wrapper {
            position: relative;
            margin-bottom: 20px;
        }
        .password-wrapper input {
            margin-bottom: 0;
            padding-right: 44px;
        }
        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            width: auto;
            color: #666;
            font-size: 18px;
            line-height: 1;
            margin: 0;
        }
        .toggle-pw:hover { color: #2d5a1b; }
        .field-hint {
            font-size: 11px;
            color: #888;
            margin: -14px 0 14px 0;
        }
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            margin: -14px 0 14px 0;
            background: #e0e0e0;
            overflow: hidden;
        }
        .strength-bar span {
            display: block;
            height: 100%;
            width: 0;
            border-radius: 2px;
            transition: width .3s, background .3s;
        }
    </style>
</head>
<body>

<div class="image-bg">
    <div class="container">

        <!-- LOGIN FORM -->
        <div class="form-box active" id="login-form">
            <form method="POST" action="Process.php" autocomplete="off">
                <h2>Login</h2>

                <?php if (!empty($_SESSION['login_error'])): ?>
                    <p class="error-message">
                        <?php echo htmlspecialchars($_SESSION['login_error']); unset($_SESSION['login_error']); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($_SESSION['register_success'])): ?>
                    <p style="color:#2d5a1b;text-align:center;background:#e8f5e9;padding:10px;border-radius:6px;">
                        <?php echo htmlspecialchars($_SESSION['register_success']); unset($_SESSION['register_success']); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($_SESSION['popup'])): ?>
                <script>
                    window.onload = function() {
                        alert("<?php echo addslashes($_SESSION['popup']); ?>");
                    };
                </script>
                <?php unset($_SESSION['popup']); ?>
                <?php endif; ?>

                <input type="email" name="email" placeholder="name@example.com" required autocomplete="username">

                <div class="password-wrapper">
                    <input type="password" name="password" id="loginPw" placeholder="Password" required autocomplete="current-password">
                    <button type="button" class="toggle-pw" onclick="togglePw('loginPw', this)">👁</button>
                </div>

                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="Student">Student</option>
                    <option value="Vendor">Vendor</option>
                </select>

                <button type="submit" name="login">Login</button>
                <p>Don't have an account? <a href="#" onclick="showForm('register-form')">Register</a></p>
            </form>
        </div>

        <!-- REGISTER FORM -->
        <div class="form-box <?php echo !empty($_SESSION['register_error']) ? 'active' : ''; ?>" id="register-form">
            <form method="POST" action="Process.php" autocomplete="off" id="regForm">
                <h2>Register</h2>

                <?php if (!empty($_SESSION['register_error'])): ?>
                    <p class="error-message">
                        <?php echo htmlspecialchars($_SESSION['register_error']); unset($_SESSION['register_error']); ?>
                    </p>
                <?php endif; ?>

                <input type="text" name="name" placeholder="Full Name" required
                       minlength="2" maxlength="60"
                       pattern="[a-zA-ZÀ-ÿ\s'\-\.]{2,60}"
                       title="Letters, spaces, hyphens, and periods only (2–60 characters)">

                <input type="email" name="email" placeholder="name@example.com" required>

                <div class="password-wrapper">
                    <input type="password" name="password" id="regPw" placeholder="Password"
                           minlength="8" maxlength="32" required
                           oninput="checkStrength(this.value)">
                    <button type="button" class="toggle-pw" onclick="togglePw('regPw', this)">👁</button>
                </div>
                <div class="strength-bar"><span id="strengthBar"></span></div>
                <p class="field-hint">8–32 characters · at least one uppercase, lowercase, and number</p>

                <div class="password-wrapper">
                    <input type="password" name="confirm_password" id="confirmPw"
                           placeholder="Confirm Password" required>
                    <button type="button" class="toggle-pw" onclick="togglePw('confirmPw', this)">👁</button>
                </div>
                <p class="field-hint" id="matchHint"></p>

                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="Student">Student</option>
                    <option value="Vendor">Vendor</option>
                </select>

                <!-- Terms & Conditions -->
                <div class="terms-row">
                    <input type="checkbox" name="terms" id="termsCheck" required>
                    <label for="termsCheck">
                        I have read and agree to the
                        <a href="#" onclick="openTerms(); return false;">Terms and Conditions</a>
                        and the <strong>Data Privacy Act of 2012 (R.A. 10173)</strong>.
                        I understand my data will be used solely for academic purposes.
                    </label>
                </div>

                <button type="submit" name="register">Register</button>
                <p>Already have an account? <a href="#" onclick="showForm('login-form')">Login</a></p>
            </form>
        </div>

    </div>
</div>

<!-- TERMS MODAL -->
<div id="termsModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;justify-content:center;align-items:center;">
    <div style="background:#fff;border-radius:12px;max-width:500px;width:90%;max-height:80vh;overflow-y:auto;padding:32px;">
        <h3 style="margin-bottom:12px;color:#2d5a1b;">Terms and Conditions</h3>
        <p style="font-size:13px;line-height:1.8;color:#333;margin-bottom:10px;">By registering, you agree to the following:</p>
        <ul style="font-size:13px;line-height:1.8;color:#333;padding-left:20px;margin-bottom:16px;">
            <li>All data collected is used <strong>solely for academic and research purposes</strong> under the Tulay Ugnayan project.</li>
            <li>Your information will be kept strictly confidential and will <strong>not be shared</strong> with third parties without your consent.</li>
            <li>Participation is entirely <strong>voluntary</strong>. You may withdraw at any time.</li>
            <li>You agree to provide <strong>truthful and accurate</strong> information.</li>
            <li>You acknowledge compliance with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>.</li>
        </ul>
        <p style="font-size:12px;color:#888;">© 2026 DIT 2-5 Group 4 — Institute of Technology, PUP</p>
        <button onclick="closeTerms()" style="margin-top:16px;background:#2d5a1b;color:#fff;border:none;padding:10px 24px;border-radius:6px;cursor:pointer;font-size:14px;width:auto;">Close</button>
    </div>
</div>

<!-- FOOTER -->
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
    function showForm(formId) {
        document.querySelectorAll('.form-box').forEach(b => b.classList.remove('active'));
        document.getElementById(formId).classList.add('active');
    }

    window.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('register-form').classList.contains('active')) {
            document.getElementById('login-form').classList.remove('active');
        }
    });

    function togglePw(id, btn) {
        const input = document.getElementById(id);
        if (input.type === 'password') { input.type = 'text';     btn.textContent = '🙈'; }
        else                           { input.type = 'password'; btn.textContent = '👁';  }
    }

    function checkStrength(pw) {
        const bar  = document.getElementById('strengthBar');
        let score  = 0;
        if (pw.length >= 8)           score++;
        if (/[A-Z]/.test(pw))         score++;
        if (/[a-z]/.test(pw))         score++;
        if (/[0-9]/.test(pw))         score++;
        if (/[^A-Za-z0-9]/.test(pw))  score++;
        const pct   = ['0%','20%','40%','60%','80%','100%'][score];
        const color = ['#e0e0e0','#e53935','#fb8c00','#fdd835','#43a047','#00897b'][score];
        bar.style.width      = pct;
        bar.style.background = color;
    }

    document.getElementById('confirmPw').addEventListener('input', function () {
        const hint = document.getElementById('matchHint');
        if (this.value === '') { hint.textContent = ''; return; }
        if (this.value === document.getElementById('regPw').value) {
            hint.textContent = '✔ Passwords match';
            hint.style.color = '#2d5a1b';
        } else {
            hint.textContent = '✘ Passwords do not match';
            hint.style.color = '#e53935';
        }
    });

    document.getElementById('regForm').addEventListener('submit', function (e) {
        const pw  = document.getElementById('regPw').value;
        const cpw = document.getElementById('confirmPw').value;
        if (pw !== cpw) {
            e.preventDefault();
            alert('Passwords do not match. Please try again.');
            return;
        }
        if (!/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/.test(pw)) {
            e.preventDefault();
            alert('Password must be at least 8 characters with at least one uppercase letter, lowercase letter, and number.');
            return;
        }
        if (!document.getElementById('termsCheck').checked) {
            e.preventDefault();
            alert('You must agree to the Terms and Conditions to register.');
        }
    });

    function openTerms() {
        document.getElementById('termsModal').style.display = 'flex';
    }
    function closeTerms() {
        document.getElementById('termsModal').style.display = 'none';
    }
    document.getElementById('termsModal').addEventListener('click', function (e) {
        if (e.target === this) closeTerms();
    });
</script>

</body>
</html>