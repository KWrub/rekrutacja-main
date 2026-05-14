zadanie 1
logowanie:
    usunięto podatność SQL injection
    dodano walidacje
    zmieniono metode z GET na POST
    dodano formularz na stronie głównej (style zvibe codowane)
    samo logowanie zamiast tokenu mogłoby używać zwykłego zahashowanego hasła w bazie, w przypadku tokenu podawanie username kłóci się z założeniami tokena, podsumowując, albo username + hasło lub sam token

refactor:
    zmieniono strukture katalogową
    dodano repository
    usunięto interfejs, na ten moment jest zbędny
    dodano servisy
    dodano unit testy

zadanie 2
symphony app:
    dodano migracje
    dodano końcówke odpowiedzialna za przypisywanie tokenu
    dodano formularz w profilu (style zvibe codowane, bezpieczniej byłoby nie wyświetlać tego tokenu i na przykład zwracać tylko jakaś flagę że jest ustawiony i umożliwić wpisywanie go tylko od nowa za każdym razem)
    dodano końcówkę odpowiedzialną za importowanie zdjęć
    dodano serwisy odpowiedzialne za komunikacje oraz procesowanie odpowiedzi z phoenix api

phoenix api:
    zmodyfikowano kontroler
    dodano maper w osobnym pliku

zadanie 3
filtry:
    dodano formularz na stronie głównej (style zvibe codowane)
    dodano walidacje dla filtrów

like:
    dodano redirect do poprzedniej strony zamiast na strone domową w celu zachowania filtrów, w finalnej wersji najlepiej byłoby użyć Axios + API zamiast redirectów