<?php
    use App\Website\WebsiteUtilities;
    $stamp = WebsiteUtilities::get_asset_cache_stamp('/scripts/article-renderer.js');
?>

<style>

.article-target {
    height: 100%;
    width: 70%;
    max-width: 1200px;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
    padding: 120px 120px;
    /* align-items: center; */
    border: 1px solid rgba(0, 0, 0, 0.11);
    color: rgba(0, 0, 0, 0.70);
    font-size: 24px;

    .center {
        align-self: center;
    }

    h1 {
        color: rgba(0, 0, 0, 0.90);
    }

    a {
        color: #00A7A6;
        text-decoration: none;
    }
}

@media (max-aspect-ratio: 0.77) {
    .article-target {
        width: 100%;
        padding: 120px 60px;
    }
}

</style>

<div class="main-container">
    <div class="article-target"></div>
</div>

<script src="/scripts/article-renderer.js?t=<?php echo $stamp;?>"></script>