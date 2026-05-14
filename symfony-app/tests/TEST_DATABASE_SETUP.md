# Konfiguracja bazy danych testowej

## Przegląd
Ten katalog zawiera konfigurację testowej bazy danych dla testów integracyjnych. Setup obejmuje:

1. **Oddzielna baza testowa**: `symfony_app_test` (tworzona jako baza z sufiksem `symfony_app_test`)
2. **Automatyczne czyszczenie bazy danych**: Każdy test dziedziczy po `IntegrationTestCase`, który automatycznie:
   - resetuje bazę danych przed każdym testem
   - czyści wszystkie tabele po każdym teście
   - zachowuje spójność kluczy obcych (foreign key constraints)

## Kroki konfiguracji

### 1. Utworzenie bazy testowej (jednorazowo)

```bash
# Wewnątrz kontenera Docker
docker compose exec symfony php bin/console doctrine:database:create --env=test

# Lub ręcznie
docker compose exec symfony-db psql -U postgres -c "CREATE DATABASE symfony_app_test;"
```

---

### 2. Uruchomienie migracji dla bazy testowej

```bash
docker compose exec symfony php bin/console doctrine:migrations:migrate --env=test
```

---

## Używanie testów

### Rozszerz IntegrationTestCase dla testów bazodanowych

```php
use App\Tests\Support\IntegrationTestCase;

class YourRepositoryTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Twój kod inicjalizacyjny
    }
}
```

---

### Uruchamianie testów

```bash
# Wszystkie testy
docker compose exec symfony php vendor/bin/phpunit

# Konkretna klasa testowa
docker compose exec symfony php vendor/bin/phpunit tests/Integration/Repository/PhotoRepositoryTest.php

# Konkretna metoda testowa
docker compose exec symfony php vendor/bin/phpunit tests/Integration/Repository/PhotoRepositoryTest.php --filter testCreatePhoto
```

---

## Architektura

### DatabaseTrait
Znajduje się w `tests/Support/DatabaseTrait.php`:

- obsługuje reset bazy przed i po każdym teście
- czyści wszystkie tabele z zachowaniem constraintów kluczy obcych
- działa z PostgreSQL

### IntegrationTestCase
Znajduje się w `tests/Support/IntegrationTestCase.php`:

- bazowa klasa dla testów integracyjnych
- automatycznie bootuje kernel Symfony
- zapewnia czyszczenie bazy przez DatabaseTrait

---

## Zmienne środowiskowe

- `.env.test`: standardowe zmienne dla środowiska testowego  
- `.env.test.local`: lokalne nadpisania (nie są commitowane do repozytorium)

Domyślny URL bazy testowej:

```
DATABASE_URL=postgres://postgres:postgres@symfony-db:5432/symfony_app_test
```

---

## Rozwiązywanie problemów

### „Testowa baza danych nie istnieje”

```bash
docker compose exec symfony php bin/console doctrine:database:create --env=test
```

---

### Błędy „tabela nie istnieje”

```bash
docker compose exec symfony php bin/console doctrine:migrations:migrate --env=test
```

---

### Reset bazy testowej

```bash
docker compose exec symfony php bin/console doctrine:database:drop --env=test --force
docker compose exec symfony php bin/console doctrine:database:create --env=test
docker compose exec symfony php bin/console doctrine:migrations:migrate --env=test
```