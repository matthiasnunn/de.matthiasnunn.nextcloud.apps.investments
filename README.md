# Investments

Nextcloud App, um Investments und Investmentsentwicklungsdaten anzuzeigen.

## Tests

Aufrufen:

```
docker exec --user www-data nextcloud php /var/www/html/custom_apps/investments/tests/Repositories/InvestmentsRepositoryTest.php
```

```
docker exec --user www-data nextcloud php /var/www/html/custom_apps/investments/tests/Services/InvestmentsDevelopmentServiceTest.php
```

```
docker exec --user www-data nextcloud php /var/www/html/custom_apps/investments/tests/Services/InvestmentsServiceTest.php
```