<?php
// Build and redirect to the actual mailto link
$emailUser = "flatmark";
$emailDomain = "elektrischerwalfisch.de";
$email = $emailUser . '@' . $emailDomain;

// Redirect to the mail client
header("Location: mailto:$email");
exit;

?>