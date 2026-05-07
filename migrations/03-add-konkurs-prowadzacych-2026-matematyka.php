<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\KonkursProwadzacych\Models\Kategoria;
use App\KonkursProwadzacych\Models\KonkursProwadzacych;
use App\KonkursProwadzacych\Models\Prowadzacy;

$kandydaci = [
    "Annette Karrer",
    "Marek Arendarczyk",
    "Piotr Biler",
    "Małgorzata Bogdan",
    "Piotr Borodulin-Nadzieja",
    "Dariusz Buraczewski",
    "Szymon Cygan",
    "Wojciech Cygan",
    "Ewa Damek",
    "Daniel Danielski",
    "Leonidas Daskalakis",
    "Krzysztof Dębicki",
    "Jan Dobrowolski",
    "Jan Dymara",
    "Piotr Dyszewski",
    "Jacek Dziubański",
    "Tomasz Elsner",
    "Światosław Gal",
    "Jakub Gismatullin",
    "Jakub Gogolok",
    "Jaroslaw Harezlak",
    "Waldemar Hebisch",
    "Michael Hecht",
    "Agnieszka Hejna-Łyżwa",
    "Grzegorz Jagiella",
    "Tadeusz Januszkiewicz",
    "Elżbieta Kalinowska",
    "Grzegorz Karch",
    "Paweł Kawa",
    "Krzysztof Kępczyński",
    "Przemysław Klusik",
    "Alicja Kołodziejska",
    "Michał Kos",
    "Piotr Kowalski",
    "Krzysztof Kowalski",
    "Jan Kraszewski",
    "Krzysztof Krawczyk",
    "Michał Krawiec",
    "Krzysztof Krupiński",
    "Konrad Krystecki",
    "Maciej Kucharski",
    "Aleksandra Kwiatkowska",
    "Paweł Lorek",
    "Michał Marcinkowski",
    "Nadav Meir",
    "Małgorzata Mikołajczyk",
    "Mariusz Mirek",
    "Wojciech Młotkowski",
    "Ludomir Newelski",
    "Krzysztof Omiljanowski",
    "Damian Osajda",
    "Maciej Paluszyński",
    "Tadeusz Pezda",
    "Grzegorz Plebanek",
    "Marcin Preisner",
    "Andrzej Raczyński",
    "Arturo Antonio Martinez Celis Rodriguez",
    "Tomasz Rzepecki",
    "Ziemowit Rzeszotnik",
    "Wojciech Słomian",
    "Robert Stańczy",
    "Mateusz Staniak",
    "Tomasz Zachary Szarek",
    "Michał Śliwiński",
    "Jacek Świątkowski",
    "Witold Świątkowski",
    "Mariusz Tobolski",
    "Krzysztof Topolski",
    "Roman Urban",
    "Motiejus Valiunas",
    "Aleksandra Wencel",
    "Błażej Wróbel",
    "Jarosław Wróblewski",
    "Grzegorz Wyłupek",
    "Anna Wysoczańska-Kula",
    "Janusz Wysoczański",
    "Liudmyla Zaitseva",
    "Alexander Bendikov",
    "Marek Bożejko",
    "Andrzej Dąbrowski",
    "Zofia Diaków",
    "Roman Duda",
    "Bogusław Hajduk",
    "Agata Hoffmann",
    "Zbigniew J. Jurek",
    "Bolesław Kopociński",
    "Wiesław Krakowiak",
    "Jurij Kryakin",
    "Andrzej Krzywicki",
    "Stanisław Miklos",
    "Kazimierz Musiał",
    "Władysław Narkiewicz",
    "Janusz Pawlikowski",
    "Tomasz Rolski",
    "Małgorzata Romanowska-Majsnerowska",
    "Władysław Szczotka",
    "Ryszard Szekli",
    "Ryszard Szwarc",
    "Krzysztof Tabisz",
    "Danuta Zaremba"
];

$kategorie = [
    [ 
        "name" => "Złoty komunikator", 
        "description" => "- Szybko odpowiada na maile<br> - Jest łatwo dostępny na uczelni"
    ],
    [ 
        "name" => "Oaza Spokoju", 
        "description" => "- Pozwala poprawiać listy (informatyka)<br> - Umawia się na konsultacje<br> - Na zajęciach rozluźnia atmosfere"
    ],
    [ 
        "name" => "Najlepszy nauczyciel", 
        "description" => "- Świetnie tłumaczy trudne koncepty<br>- Dąży do tego by każdy student zrozumiał"
    ],
    [ 
        "name" => "Śmieszek", 
        "description" => "- Potrafi sprawić że student się uśmiechnie w trakcie zajęć"
    ],
    [ 
        "name" => "Ikona", 
        "description" => "- Uniwersalna kategoria<br> - Prowadzący, który odznaczył się dla ciebie czymś specjalnym w dowolny sposób<br>- Potencjalnie osoba, z którą kojarzysz swój kierunek"
    ],
    [ 
        "name" => "Frontend Wydziału", 
        "description" => "- Prowadzący z najlepszym stylem <br> - Ubranie, fryzura itd."
    ],
    [ 
        "name" => "Koneser LaTeXa", 
        "description" => "- Nagroda za najlepsze notatki z wykładu"
    ],
    [ 
        "name" => "Kaligraf", 
        "description" => "- dla prowadzącego z najlepszym stylem pisania"
    ],
];

$konkurs_prowadzacych = (new KonkursProwadzacych())
    ->set_edycja("Konkurs Prowadzących 2026 - Matematyka")
    ->save();
 
foreach ($kandydaci as $kandydat) {
    (new Prowadzacy())
        ->set_konkurs_prowadzacych($konkurs_prowadzacych->get_id())
        ->set_name($kandydat)
        ->save();
}

foreach ($kategorie as $kategoria) {
    (new Kategoria())
        ->set_konkurs_prowadzacych($konkurs_prowadzacych->get_id())
        ->set_name($kategoria['name'])
        ->set_description($kategoria['description'])
        ->save();
}

?>