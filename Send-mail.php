<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Honeypot check
    if (!empty($_POST['phone'])) {
        header("Location: contact.html?status=spam");
        exit;
    }

    // Captcha check
    $n1 = (int)($_POST['captcha1'] ?? 0);
    $n2 = (int)($_POST['captcha2'] ?? 0);
    $ans = (int)($_POST['captcha'] ?? -1);

    if ($ans !== ($n1 + $n2)) {
        header("Location: contact.html?status=captcha");
        exit;
    }

    // Sanitize inputs
    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($message) < 10) {
        header("Location: contact.html?status=error");
        exit;
    }

    // Send email
    $to = "ronaldrussia1@proton.me";
    $email_subject = "Contact Form: " . ($subject ?: "No Subject");
    $email_body = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message\n";
    $headers = "From: ronaldrussia1@proton.me\r\nReply-To: $email\r\n";

    if (mail($to, $email_subject, $email_body, $headers)) {
        header("Location: contact.html?status=success");
    } else {
        // Some servers block mail() → log email for debugging
        error_log("Mail failed: $email_subject\n$email_body");
        header("Location: contact.html?status=error");
    }
    exit;
}
header("Location: contact.html");
exit;
?>
