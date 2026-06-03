<?php
session_start();

/* CAPTCHA */
if (!isset($_SESSION['captcha_answer'])) {
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);

    $_SESSION['captcha_question'] = "$num1 + $num2";
    $_SESSION['captcha_answer'] = $num1 + $num2;
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = htmlspecialchars(trim($_POST['email']));
    $phone   = htmlspecialchars(trim($_POST['phone']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    $captcha = trim($_POST['captcha']);

    if ($captcha != $_SESSION['captcha_answer']) {

        $error = "Incorrect security answer.";

    } else {

        /* EMAIL TO ADMIN */

        $admin_email = "lahoremarketpk@gmail.com";

        $admin_subject = "New Website Inquiry - $subject";

        $admin_message =
        "New inquiry received from LahoreMarket.pk\n\n".
        "Name: $name\n".
        "Email: $email\n".
        "Phone: $phone\n".
        "Subject: $subject\n\n".
        "Message:\n$message";

        $headers =
        "From: info@lahoremarket.pk\r\n".
        "Reply-To: $email\r\n".
        "MIME-Version: 1.0\r\n".
        "Content-Type: text/plain; charset=UTF-8\r\n";

        $admin_sent = mail(
            $admin_email,
            $admin_subject,
            $admin_message,
            $headers
        );

        if ($admin_sent) {

            /* AUTO REPLY TO USER */

            $user_subject = "Thank You For Contacting Lahore Market";

            $user_message =
            "Dear $name,\n\n".
            "Thank you for contacting Lahore Market.\n\n".
            "We have successfully received your message and our team will contact you shortly.\n\n".
            "Your submitted details:\n\n".
            "Subject: $subject\n".
            "Phone: $phone\n\n".
            "Best Regards,\n".
            "Lahore Market Team\n".
            "https://lahoremarket.pk";

            $user_headers =
            "From: info@lahoremarket.pk\r\n".
            "MIME-Version: 1.0\r\n".
            "Content-Type: text/plain; charset=UTF-8\r\n";

            mail(
                $email,
                $user_subject,
                $user_message,
                $user_headers
            );

            $success = "Thank you! Your message has been sent successfully.";

            unset($_SESSION['captcha_answer']);
            unset($_SESSION['captcha_question']);

        } else {

            $error = "Unable to send your message. Please try again later.";

        }
    }
}
?><!DOCTYPE html><html lang="en">
<head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Contact Us | Lahore Market</title><style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
background:linear-gradient(135deg,#0d6efd,#00c6ff);
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
padding:30px;
}

.container{
width:100%;
max-width:850px;
background:#ffffff;
padding:40px;
border-radius:20px;
box-shadow:0 15px 40px rgba(0,0,0,0.20);
}

.logo{
text-align:center;
font-size:36px;
font-weight:700;
color:#0d6efd;
margin-bottom:10px;
}

.tagline{
text-align:center;
color:#666;
margin-bottom:30px;
font-size:16px;
}

.success{
background:#d1e7dd;
color:#0f5132;
padding:15px;
border-radius:10px;
margin-bottom:20px;
}

.error{
background:#f8d7da;
color:#842029;
padding:15px;
border-radius:10px;
margin-bottom:20px;
}

.row{
display:flex;
gap:15px;
}

.col{
flex:1;
}

.form-group{
margin-bottom:18px;
}

label{
display:block;
margin-bottom:8px;
font-weight:600;
color:#333;
}

input,
textarea{
width:100%;
padding:14px;
border:1px solid #dcdcdc;
border-radius:10px;
font-size:15px;
outline:none;
transition:.3s;
}

input:focus,
textarea:focus{
border-color:#0d6efd;
}

textarea{
height:180px;
resize:vertical;
}

.captcha-box{
background:#f5f5f5;
padding:15px;
border-radius:10px;
font-weight:700;
text-align:center;
margin-bottom:15px;
font-size:18px;
}

button{
width:100%;
padding:15px;
border:none;
border-radius:10px;
background:#0d6efd;
color:#fff;
font-size:17px;
font-weight:700;
cursor:pointer;
transition:.3s;
}

button:hover{
background:#084298;
}

.footer-text{
text-align:center;
margin-top:20px;
font-size:13px;
color:#777;
}

@media(max-width:768px){

.row{
flex-direction:column;
}

.container{
padding:25px;
}

}

</style></head><body><div class="container"><div class="logo">Lahore Market</div><div class="tagline">
Have a question or business inquiry? Send us a message.
</div><?php if($success != "") { ?><div class="success"><?php echo $success; ?></div>
<?php } ?><?php if($error != "") { ?><div class="error"><?php echo $error; ?></div>
<?php } ?><form method="POST"><div class="row"><div class="col form-group">
<label>Full Name</label>
<input type="text" name="name" required>
</div><div class="col form-group">
<label>Email Address</label>
<input type="email" name="email" required>
</div></div><div class="row"><div class="col form-group">
<label>Phone Number</label>
<input type="text" name="phone" required>
</div><div class="col form-group">
<label>Subject</label>
<input type="text" name="subject" required>
</div></div><div class="form-group">
<label>Message</label>
<textarea name="message" required></textarea>
</div><div class="captcha-box">
Security Verification:
<?php echo $_SESSION['captcha_question']; ?>
</div><div class="form-group">
<input type="text" name="captcha" placeholder="Enter Answer" required>
</div><button type="submit">Send Message</button>

</form><div class="footer-text">
© <?php echo date('Y'); ?> Lahore Market. All Rights Reserved.
</div></div></body>
</html>
