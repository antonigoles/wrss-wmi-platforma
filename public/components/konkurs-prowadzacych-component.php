<style>
    .main-container {
        justify-content: flex-start;
    }

    .kp-header {
        margin-top: min(200px, 20vh);
        font-weight: normal;
        font-size: min(15px, 15vw);
        text-align: center;
    }

    .kp-header > p {
        padding: 0px 12px;
        font-size: 3em;
        font-weight: bold;
        margin: 0;
    }

    .kp-description {
        text-align: center;
        font-size: 1em;
        margin-top: 0;
        opacity: 0.5;
        padding: 2px 12px;
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
        padding: 12px 8px;
        font-size: 1em;
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
        background-color: white;
        z-index: 10;
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
        font-size: 1em;
    }

    .kt-candidate-list-el:hover {
        background-color: black;
        color: white;
        cursor: pointer;
    }

    .kt-navigation-buttons {
        position: absolute;
        bottom: 12px;
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

    .kt-btn.btn-disabled {
        opacity: 0.5;
    }

    .kt-btn:not(.btn-disabled):hover, 
    .kt-btn:not(.btn-disabled):active {
        background-color: black;
        color: white;
        cursor: pointer;
    }

    .thank-you-page {
        left: 0;
        top: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: #4bb543;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        font-size: xx-large;
        text-align: center;
        color: white;
        user-select: none;
        padding: 8px 24px;
        box-sizing: border-box;
        transition: 0.7s transform ease-in-out;
    }

    .thank-you-page > h1 {
        padding: 8px 24px;
        font-size: 1.5em;
    }

    .thank-you-page > p {
        margin-top: -10px;
        font-size: 0.5em;
    }

    .thank-you-page-hidden {
        transform: translateY(-100%);
    }

</style>

<div class="thank-you-page thank-you-page-hidden">
    <h1> Dzięki za wypełnienie ankiety! </h1>
    <p> Możesz teraz zamknąć te stronę </p>
</div>

<div class="kp-header">
    Nagroda w kategorii:
    <p> ... </p>
</div>

<div class="kp-description"> ... </div>

<div class="kt-vote-component">
    <input type="text" class="kt-text-input" placeholder="Wyszukaj kandydata..."/>
    <div class="kt-candidate-list" style="display: none"></div>
</div>

<div class="kt-navigation-buttons">
    <div class="kt-btn kt-back-btn btn-disabled">Wstecz</div>
    <div class="kt-btn kt-next-btn btn-disabled">Dalej</div>
</div>

<script>
const $ = (e) => document.querySelector(e);
const $$ = (e) => document.querySelectorAll(e);

(function () {

// Pairs of {question_id} => {prowadzacy_id}
let current_answer = {};
let voting_options = [];
let currently_choosen_id = null;
let current_question_id = null;
let konkurs_data = 0;

async function submit_answer()
{
    const result = await (await fetch('/api/submit_kp_vote.php', {
        method: 'POST',
        body: JSON.stringify({
            konkurs_id: <?php echo $_GET['id']?>,
            answers: current_answer
        })
    })).json() 

    if (result['error']) {
        alert('Błąd w trakcie wysyłania odpowiedzi')
        return;
    }

    $('.thank-you-page').classList.remove('thank-you-page-hidden');
}

function set_previous_question() {
    // 1. find index in list of current_question_id
    let index = konkurs_data['kategorie'].findIndex(e => e['id'] === current_question_id)
    let new_index = index - 1;
    if (new_index < 0) {
        return;
    }
    if (new_index == 0) {
        $('.kt-back-btn').classList.add('btn-disabled');
    } else {
        $('.kt-back-btn').classList.remove('btn-disabled');
    }
    current_question_id = konkurs_data['kategorie'][new_index]['id'];
}

function set_next_question_or_finish() {
    // 1. find index in list of current_question_id
    let index = konkurs_data['kategorie'].findIndex(e => e['id'] === current_question_id)
    let new_index = index + 1;
    if (konkurs_data['kategorie'].length <= new_index) {
        // Submit answer
        submit_answer();
        return;
    }
    if (konkurs_data['kategorie'].length == new_index + 1) {
        $('.kt-next-btn').innerHTML = 'Wyślij';
    } else {
        $('.kt-next-btn').innerHTML = 'Dalej';
    }
    $('.kt-back-btn').classList.remove('btn-disabled');
    current_question_id = konkurs_data['kategorie'][new_index]['id'];
}

function render_question() {
    // 1. find question by current_question_id in konkurs_data
    let found = konkurs_data['kategorie'].find(e => e['id'] == current_question_id);
    if (!found) {
        alert("Wystąpił błąd, odśwież strone");
        return;
    }
    // 2. draw question
    $('.kp-header > p').innerHTML = found['name'];
    $('.kp-description').innerHTML = found['description'];

    // 3. clear out inputs
    $(".kt-text-input").value = '';
    $('.kt-next-btn').classList.add('btn-disabled');

    // 4. try to also draw our previous answer
    if (current_question_id in current_answer) {
        const prow_id = current_answer[current_question_id];
        // find prow id
        currently_choosen_id = prow_id;
        $('.kt-next-btn').classList.remove('btn-disabled');
        const prow = konkurs_data['prowadzacy'].find( e => e['id'] === prow_id);
        $(".kt-text-input").value = prow ? prow['name'] : 'Brak głosu';
    }
}

async function fetch_konkurs() {
    const data = await (await fetch(`/api/get_kp_data.php?id=<?php echo $_GET['id']?>`)).json();
    if (data['error']) {
        $('.main-container').innerHTML = `
            <h1>Wystąpił błąd : (</h1>
            <p> ${data['error']} </p>
        `;
        return;
    }
    voting_options = data['prowadzacy'];
    konkurs_data = data;
    current_question_id = data['kategorie'][0]['id'];
    for (const vote of data['votes']) {
        current_answer[vote['kategoria_id']] = vote['prowadzacy_id'];
    }
    render_question();
}

fetch_konkurs();

function render_voting_list(list) {
    $(".kt-candidate-list").innerHTML = 
    `<div class="kt-candidate-list-el" data-id="null">Brak głosu</div>` +
    list.map(
        e => `<div class="kt-candidate-list-el" data-id="${e['id']}">${e['name']}</div>`
    ).join("");
    
    $$(".kt-candidate-list-el").forEach(
        element => element.addEventListener('click', (e) => {
            $(".kt-candidate-list").style.display = "none";
            $(".kt-text-input").value = element.innerText;
            currently_choosen_id = element.dataset.id === "null" ? null : Number(element.dataset.id);
            $('.kt-next-btn').classList.remove('btn-disabled');
            if (currently_choosen_id !== null) {
                // disable next button
            //     $('.kt-next-btn').classList.add('btn-disabled');
            // } else {
                // enable next button
                current_answer[current_question_id] = currently_choosen_id;
            }
        })
    )
}

function search_filter(query, list) {
    return list.filter(
        e => e['name'].toLowerCase().includes(query.toLowerCase()) && e['name'] !== query
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

$(".kt-back-btn").addEventListener('click', (e) => {
    if (e.srcElement.classList.contains('btn-disabled')) return;
    set_previous_question();
    render_question();
});

$(".kt-next-btn").addEventListener('click', (e) => {
    if (e.srcElement.classList.contains('btn-disabled')) return;
    set_next_question_or_finish();
    render_question();
});


})();
</script>