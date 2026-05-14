<style>
    .main-container {
        justify-content: flex-start;
    }

    .choose-konkurs {
        display: flex;
        /* justify-content: center; */
        align-items: center;
        width: 100%;
        height: 100%;
        padding-top: 25px;
        gap: 15px;
        text-align: center;
        flex-direction: column;
    }

    .ck-list-element {
        padding: 15px 30px;
        color: black;
        user-select: none;
        font-size: 32px;
        font-weight: bold;
        border: 4px dashed black;
        width: 900px;
        max-width: 80%;
        text-align: center;
    }
    
    .ck-list-element:hover {
        background-color: black;
        color: white;
        cursor: pointer;
    }

    @keyframes pulse-opacity {
        from {
            opacity: 0.3;
        }
        to {
            opacity: 0.4;
        }
    }

    .ck-list-element-loader {
        background-color: gray;
        max-width: 80%;
        width: 900px;
        height: 100px;
        opacity: 0.3;
        animation: pulse-opacity 0.5s ease-in-out infinite alternate;
    }

    a {
        text-decoration: none;
    }

    .return-btn {
        width: 900px;
        max-width: 80%;
        text-align: center;
        font-size: 26px;
        padding-top: 32px;
        color: #00A7A6;
    }

</style>

<a class="return-btn" href="/">< Cofnij</a>
<div class="choose-konkurs">
    <h1>Dostepne głosowania</h1>
</div>

<script>
const $ = (e) => document.querySelector(e);

(function () {
    // fetch data
    async function load_list() {
        $('.choose-konkurs').innerHTML = 
        `   <h1>Dostepne głosowania</h1>
            <div class="ck-list-element-loader"></div>
        `
        const result = await fetch('/api/get_full_kp_data.php');
        const json = await result.json();
        if (json['error']) {
            $('.choose-konkurs').innerHTML = 
            `   <h1>Dostepne głosowania</h1>
                <h2>Błąd 403 :/</h2> <p> spróbuj odświeżyć strone </p>
            `
            return;
        }
        konkurs_data = Array.isArray(json) ? json : [];
        $('.choose-konkurs').innerHTML = `<h1>Dostepne głosowania</h1>`;
        for (const konkurs of konkurs_data) {
            $('.choose-konkurs').innerHTML += `
            <a class="ck-list-element" href="/konkurs-prowadzacych.php?id=${konkurs['konkurs']['id']}">${konkurs['konkurs']['edycja']}</a>
            `;
        }
    }

    load_list();
})();

</script>