<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>
<body>
<h1>Oops! Something went wrong.</h1>
<p>We're sorry, but an unexpected error occurred. Please try again later.</p>
<?php if (isset($_SESSION['error_message'])): ?>
    <p>Error details: <?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>
</body>
</html>


