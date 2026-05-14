<?php
    use App\Website\WebsiteUtilities;
    $stamp = WebsiteUtilities::get_asset_cache_stamp('/scripts/article-renderer.js');
?>

<style>

.article-target {
    min-height: 100%;
    width: 70%;
    max-width: 1200px;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
    padding: 120px 120px;
    padding-bottom: 120px;
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

    .text-justify {
        display: inline-block;
        text-align: justify;
    }
}

@media (max-aspect-ratio: 0.77) {
    .article-target {
        width: 100%;
        padding: 20px 30px;
        font-size: 20px;

        /* no justify on mobile */
        .text-justify {
            display: inline-block;
            text-align: unset; 
        }
    }

    
}

</style>

<div class="main-container">
    <div class="article-target"></div>
</div>

<script src="/scripts/article-renderer.js?t=<?php echo $stamp;?>"></script>