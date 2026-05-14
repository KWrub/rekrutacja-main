zadanie 1
logowanie:
    usunięto podatność SQL injection
    dodano walidacje
    zmieniono metode z GET na POST
    dodano formularz na stronie głównej (style zvibe codowane)
    samo logowanie zamiast tokenu mogłoby używać zwykłego zahashowanego hasła w bazie, w przypadku tokenu podawanie username kłóci się z założeniami tokena, podsumowując: albo username + hasło, albo sam token
    w przypadku wersji produkcyjnej warto byłoby także pomyśleć o rate limitingu endpointu logowania oraz czasowym blokowaniu prób logowania po przekroczeniu określonej ilości requestów

refactor:
    zmieniono strukture katalogową
    dodano repository
    usunięto interfejs, na ten moment jest zbędny
    dodano servisy
    dodano unit testy

co jeszcze warto zmienić:
    dodać paginacje zdjęć
    ograniczyć zwracanie pełnych encji na rzecz DTO lub projection query
    dodać bardziej generyczne response'y błędów zamiast zwracania wyjątków bezpośrednio użytkownikowi

zadanie 2
Symfony app:
    dodano migracje
    dodano końcówke odpowiedzialna za przypisywanie tokenu
    dodano formularz w profilu (style zvibe codowane, bezpieczniej byłoby nie wyświetlać tego tokenu i na przykład zwracać tylko jakaś flagę że jest ustawiony i umożliwić wpisywanie go tylko od nowa za każdym razem)
    dodano końcówkę odpowiedzialną za importowanie zdjęć
    dodano serwisy odpowiedzialne za komunikacje oraz procesowanie odpowiedzi z phoenix api

phoenix api:
    zmodyfikowano kontroler
    dodano maper w osobnym pliku

co jeszcze warto zmienić:
    ze względu na potencjalnie duże ilości zdjęć które mogłyby być importowane należałoby pomyśleć o paginacji oraz asynchroniczności tego rozwiązania stosując np dynamicznie tworzone genservery które wysyłałby dane chunkami do Symfony app, wymagałoby to dodanie nowej końcówki dostępnej tylko dla phoenix api
    alternatywą jest użycie messengera w Symfony app w ramach owrapowania komunikacji oraz procesowania zdjęć z phoenix api, jednakże przy tym rozwiązaniu nie jesteśmy wstanie zwrócić użytkownikowi informacji na temat statusu importowania zdjęć bez implementacji dodatkowej komunikacji po ws lub sse
    warto byłoby też dodać ograniczenie do maksymalnie jednego importu per zdjecie przez zastosowanie np ograniczenia unique na urlu zdjęcia, innym rozwiazaniem tego problemu mogłaby być dodatkowa tablica w której zapisywane byłyby informacje dotyczące tego jakie zdjęcia są aktualnie zaimportowane po id oraz nazwie serwisu z którego pochodzą
    przy większej ilości zewnętrznych providerów zdjęć warto byłoby dodać dodatkową warstwe abstrakcji lub adapterów odpowiedzialnych za mapowanie odpowiedzi z konkretnych api
    nie została dodana pełna walidacja odpowiedzi z zewnętrznego api, w wersji produkcyjnej należałoby zweryfikować zarówno typy danych jak i kompletność wymaganych pól
    warto byłoby także dodać retry policy oraz logowanie błędów komunikacji z zewnętrznym api

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

ogólne uwagi i alternatywy:
    w przypadku aplikacji Symfony app można by się pokusić o zamienienie serwisów dotyczących konkretnych encji na handlery dotyczące tylko i wyłacznie pojedynczych funkcji, rozwiazanie to spełaniałoby o wiele bardziej założenia SOLID oraz pozwoliłoby maksymalnie odchudzić kontrolery
    w samej aplikacji nie została także zaimplementowana obsługa wyjkatków a także niema żadnych generycznych odpowiedzi w przpyadku kiedy takie by się pojawiły, jest to kolejny temat do potencjalnego zaopiekowania
    w przypadku importowania zdjęć lepiej byłoby wdrożyć walidacje danych z api zewnętrznego oraz dodać pewien poziom abstrakcji w przypadku kiedy chcielibyśmy importować zdjecia z większej ilości serwisów
    część potencjalnych usprawnień została świadomie pominięta ze względu na charakter zadania rekrutacyjnego oraz chęć uniknięcia overengineeringu
    obecna wersja skupia się bardziej na poprawności flow, bezpieczeństwie podstawowych endpointów oraz pokazaniu możliwych kierunków dalszego rozwoju niż na budowaniu pełnego enterpriseowego rozwiązania
    w przypadku dalszego rozwijania aplikacji największy priorytet miałaby obsługa wyjątków, asynchroniczność importowania zdjęć, paginacja oraz optymalizacja query