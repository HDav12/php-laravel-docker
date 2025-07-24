<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verkrijg de feedback
    $feedback = htmlspecialchars($_POST['feedback']);

    // E-mailinstellingen
    $to = "socialsnl@pinterpal.com";
    $subject = "Nieuwe feedback van PinterPal";
    $message = "Je hebt nieuwe feedback ontvangen:\n\n" . $feedback;
    $headers = "From: no-reply@pinterpal.com\r\n" .
               "Reply-To: no-reply@pinterpal.com\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // E-mail versturen
    if (mail($to, $subject, $message, $headers)) {
        echo "Bedankt voor je feedback! We hebben het ontvangen.";
    } else {
        echo "Er is een fout opgetreden bij het verzenden van je feedback. Probeer het later opnieuw.";
    }
} else {
    echo "Ongeldige aanvraag.";
}
?>
