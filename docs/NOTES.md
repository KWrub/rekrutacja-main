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

co jeszcze warto zmienić:
    dodać paginacje zdjęć

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

co jeszcze warto zmienić:
    ze względu na potencjalnie duże ilości zdjęć które mogłyby być importowane należałoby pomyśleć o paginacji oraz asynchroniczności tego rozwiązania, stosując np dynamicznie tworzone genservery które wysyłałby dane chunkami do symphony app, wymagałoby to dodanie nowej końcówki dostępnej tylko dla phoenix api. 
    alternatywą jest użycie messengera w symphony app w ramach owrapowania komunikacji oraz procesowania zdjęć z phoenix api, jednakże przy tym rozwiązaniu nie jesteśmy wstanie zwrócić użytkownikowi informacji na temat statusu importowania zdjęć bez implementacji dodatkowej komunikacji po ws lub sse
    warto byłoby też dodać ograniczenie do maksymalnie jednego importu per zdjecie przez zastosowanie np ograniczenia unique na urlu zdjęcia, innym rozwiazaniem tego problemu mogłaby być dodatkowa tablica w której zapisywane byłyby informacje dotyczące tego jakie zdjęcia są aktualnie zaimportowane po id oraz nazwie serwisu z którego pochodzą

zadanie 3
filtry:
    dodano formularz na stronie głównej (style zvibe codowane)
    dodano walidacje dla filtrów

like:
    dodano redirect do poprzedniej strony zamiast na strone domową w celu zachowania filtrów, w finalnej wersji najlepiej byłoby użyć Axios + API zamiast redirectów

zadanie 4
rate limiting:
    do zaimplementowania rate limitingu posłużyła biblioteka hammer

inne podejście:
    w przypadku aplikacji multi node zalecane jest zastosowanie biblioteki hammer_backend_redis, jest to wrapper który pozwala zamienić ets na redisa, pozwala to na zachowanie rate limitingu pomiedzy wieloma node'ami (w przeciwieństwie do ETS'a który jest oddzielny dla każdego node'a) oraz zabezpiecza nas przed straceniem aktualnych liczników w przypadku restartu aplikacji