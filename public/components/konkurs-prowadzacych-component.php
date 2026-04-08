<style>
    .main-container {
        justify-content: flex-start;
    }

    .kp-header {
        margin-top: 20%;
        font-weight: normal;
        font-size: medium;
        text-align: center;
    }

    .kp-header > p {
        font-size: xxx-large;
        font-weight: bold;
        margin: 0;
    }

    .kp-description {
        text-align: center;
    }

    .kp-description {
        margin-top: 0;
        opacity: 0.5;
    }

    .kt-vote-component {
        margin-top: 25px;
        width: 80%;
        display: flex;
        flex-direction: column;
    }

    .kt-text-input {
        box-sizing: border-box;
        text-align: center;
        font-family: "Roboto Mono", monospace;
        padding: 12px 0;
        font-size: x-large;
        border: 2px solid black;
        outline: none;
        width: 100%;
    }

    @media (min-aspect-ratio: 0.77) {
        .kt-vote-component {
            width: 50%;
        }
    }

    .kt-text-input:active, .kt-text-input:focus {
        border: 2px solid black;
    }

    .kt-candidate-list {
        box-sizing: border-box;
        width: 100%;
        display: flex;
        flex-direction: column;
        max-height: 20vh;
        overflow-y: scroll;
        overflow-x: hidden;
        border: 2px solid black;
        border-top-width: 0;
    }

    .kt-candidate-list::-webkit-scrollbar {
        display: none;
    }

    .kt-candidate-list-el:last-child {
        border-bottom-width: 0;
    }

    .kt-candidate-list-el {
        user-select: none;
        font-size: x-large;
        box-sizing: border-box;
        text-align: center;
        width: 100%;
        padding-top: 12px;
        padding-bottom: 12px;
    }

    .kt-candidate-list-el:hover {
        background-color: black;
        color: white;
        cursor: pointer;
    }

    .kt-navigation-buttons {
        position: absolute;
        bottom: 50px;
        width: 100%;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .kt-btn {
        user-select: none;
        border: 2px solid black;
        padding: 15px 32px;
        font-size: x-large
    }

    .kt-btn:hover, .kt-btn:active {
        background-color: black;
        color: white;
        cursor: pointer;
    }

</style>

<div class="kp-header">
    Nagroda w kategorii:
    <p>Złoty komunikator</p>
</div>

<div class="kp-description">
    Nagroda dla prowadzącego, który: <br>
    - Szybko odpowiada na maile <br>
    - Jest łatwo dostępny na uczelni
</div>

<div class="kt-vote-component">
    <input type="text" class="kt-text-input" placeholder="Wyszukaj kandydata..."/>
    <div class="kt-candidate-list" style="display: none">
        <div class="kt-candidate-list-el">Marek Łaźniak</div>
        <div class="kt-candidate-list-el">Laskoś Skurywsyn</div>
        <div class="kt-candidate-list-el">Profesor Lorenzo</div>
        <div class="kt-candidate-list-el">Marek Łaźniak</div>
        <div class="kt-candidate-list-el">Laskoś Skurywsyn</div>
        <div class="kt-candidate-list-el">Profesor Lorenzo</div>
        <div class="kt-candidate-list-el">Marek Łaźniak</div>
        <div class="kt-candidate-list-el">Laskoś Skurywsyn</div>
        <div class="kt-candidate-list-el">Profesor Lorenzo</div>
    </div>
</div>

<div class="kt-navigation-buttons">
    <div class="kt-btn kt-back-btn">Wstecz</div>
    <div class="kt-btn kt-next-btn">Dalej</div>
</div>

<script>
const $ = (e) => document.querySelector(e);
const $$ = (e) => document.querySelectorAll(e);

let voting_options = [
    "Marek Łaźniak",
    "Laskoś Skurwysyn",
    "Profesor Lorenzo",
    "Marek Łaźniak",
    "Laskoś Skurwysyn",
    "Profesor Lorenzo",
    "Marek Łaźniak",
    "Laskoś Skurwysyn",
    "Profesor Lorenzo",
];

function render_voting_list(list) {
    $(".kt-candidate-list").innerHTML = 
    `<div class="kt-candidate-list-el">Brak głosu</div>` +
    list.map(
        e => `<div class="kt-candidate-list-el">${e}</div>`
    ).join("");
    
    $$(".kt-candidate-list-el").forEach(
        element => element.addEventListener('click', (e) => {
            $(".kt-candidate-list").style.display = "none";
            $(".kt-text-input").value = element.innerText;
        })
    )
}

function search_filter(query, list) {
    return list.filter(
        e => e.toLowerCase().includes(query.toLowerCase()) && e != query
    );
}

function refresh_and_show_candidate_list() {
    $(".kt-candidate-list").style.display = "flex";
    render_voting_list(
        search_filter($(".kt-text-input").value, voting_options)
    );
}

$(".kt-text-input").addEventListener("click", refresh_and_show_candidate_list)
$(".kt-text-input").addEventListener("input", refresh_and_show_candidate_list)


</script>