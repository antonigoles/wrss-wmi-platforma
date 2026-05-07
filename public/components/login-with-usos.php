<?php 
use App\Website\WebsiteUtilities;
$next_url = rawurldecode(WebsiteUtilities::sanitize_redirect_url($_GET['next'] ?? '/')) 
?>

<a href="/usos_oauth.php?next=<?php echo $next_url ?>" class="login-button">
    <div class="login-uwr-logo"></div>
    Zaloguj się za pomocą konta USOS
</a>