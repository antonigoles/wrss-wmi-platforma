<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\KonkursProwadzacych\Models\Kategoria;
use App\KonkursProwadzacych\Models\KonkursProwadzacych;
use App\KonkursProwadzacych\Models\Prowadzacy;

$kandydaci = [
    "Fateme Abbasi",
    "Marek Adamczyk",
    "Krystian Bacławski",
    "Patrycja Balik",
    "Mateusz Basiak",
    "Bartosz Bednarczyk",
    "Mateusz Benedyk",
    "Małgorzata Biernacka",
    "Dariusz Biernacki",
    "Marcin Bieńkowski",
    "Mateusz Biłyk",
    "Bartosz Bolechów",
    "Itai Boneh",
    "Krzysztof Boryczka",
    "Dominik Boszko",
    "Bartosz Brzoza",
    "Dariusz Buraczewski",
    "Jarosław Byrka",
    "Martin Böhm",
    "Kamil Cekiera",
    "Witold Charatonik",
    "Jan Chorowski",
    "Filip Chudy",
    "Rafał Cieślak",
    "Daniel Danielski",
    "Eliza Domiczek",
    "Bartłomiej Dudek",
    "Michał Dymowski",
    "Klaudia Dynak",
    "Piotr Dyszewski",
    "Jacek Dziubański",
    "Krzysztof Dębicki",
    "Robert Ferens",
    "Paweł Florczuk",
    "Paweł Garncarek",
    "Paweł Gawrychowski",
    "Adam Gańczorz",
    "Jakub Gogolok",
    "Leszek Grocholski",
    "Karol Gugała",
    "Daniel Górski",
    "Łukasz Halada",
    "Agnieszka Hejna",
    "Sebastian Jachimek",
    "Wojciech Janczewski",
    "Artur Jeż",
    "Łukasz Jeż",
    "Tomasz Jurdziński",
    "Małgorzata Jurkiewicz",
    "Joanna Jędrzejkowska",
    "Witold Karczewski",
    "Michał Karpiński",
    "Emanuel Kieroński",
    "Michał Kowalczykiewicz",
    "Jakub Kowalski",
    "Arkadiusz Kozdra",
    "Piotr Kozica",
    "Antoni Kościelski",
    "Artur Kraska",
    "Bartłomiej Królikowski",
    "Maciej Kucharski",
    "Agnieszka Kwiecień-Madej",
    "Paweł Laskoś-Grabowski",
    "Grzegorz Latosiński",
    "Tadeusz Lebioda",
    "Wojciech Leśniewski",
    "Piotr Lisowski",
    "Krzysztof Loryś",
    "Jan Marcinkowski",
    "Jerzy Marcinkowski",
    "Michał Marcinkowski",
    "Mateusz Markiewicz",
    "Marek Materzok",
    "Kamil Matuszewski",
    "Jakub Michaliszyn",
    "Radosław Miernik",
    "Marcin Młotkowski",
    "Kuba Nowak",
    "Rafał Nowak",
    "Krzysztof Nyczka",
    "Piotr Ostropolski-Nalewaja",
    "Jan Otop",
    "Leszek Pacholski",
    "Magdalena Paleczna-Sareńcza",
    "Katarzyna Paluch",
    "Maciej Paluszyński",
    "Marek Piotrów",
    "Maciej Piróg",
    "Łukasz Piwowar",
    "Karol Pokorski",
    "Piotr Polesiuk",
    "Zdzisław Płoski",
    "Paweł Rajba",
    "Paweł Rychlikowski",
    "Paweł Rzechonek",
    "Ziemowit Rzeszotnik",
    "Paweł Schmidt",
    "Marcin Selinger",
    "Yongho Shin",
    "Filip Sieczkowski",
    "Bartosz Smolik",
    "Anna Smolińska",
    "Dariusz Sobolewski",
    "Zdzisław Spławski",
    "Grzegorz Stachowiak",
    "Robert Stańczy",
    "Michał Stypułkowski",
    "Maria Szlasa",
    "Marek Szykuła",
    "Piotr Szymajda",
    "Magdalena Szymańska",
    "Mikołaj Słupiński",
    "Krzysztof Tabisz",
    "Agnieszka Tatarczuk",
    "Mariusz Tobolski",
    "Adrian Urbański",
    "Przemysław Uznański",
    "Phanu Vajanopath",
    "Radosław Wasielewski",
    "Mateusz Wasylkiewicz",
    "Piotr Wieczorek",
    "Tomasz Wierzbicki",
    "Piotr Witkowski",
    "Jakub Wiśniewski",
    "Piotr Wnuk-Lipiński",
    "Paweł Woźny",
    "Anna Wysoczańska-Kula",
    "Grzegorz Wyłupek",
    "Marcin Włodarczak",
    "Artur Yakimovich",
    "Filip Zagórski",
    "Wiktor Zychla",
    "Aleksander Łukasiewicz",
    "Andrzej Łukaszewski",
    "Piotr Łukomski",
    "Michał Śliwiński"
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
    [ 
        "name" => "Złoty Pingwin", 
        "description" => "- prowadzący najbardziej zaangażowany w promocje inicjatyw open source"
    ]
];

$konkurs_prowadzacych = (new KonkursProwadzacych())
    ->set_edycja("Konkurs Prowadzących 2026 - Informatyka")
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