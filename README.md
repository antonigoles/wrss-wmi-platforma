# WRSS WMI Platforma

Platforma pod projekty WRSSowe żeby można było weryfikować czy jesteś wmi student

Requirements na czystym serwerze:

```sh
php-curl # sudo apt install php-curl
php >= 8.3 # sudo apt install php8.3
```

Aby odpalić szybko, na czysto, przez kontener

```sh
docker compose up -d --build
```
---

### `.secrets.json` format:

```json
{
    "app_url": <App url> // domyślnie tu będzie http://localhost:8081,
    "name": <nazwa aplikacji USOS>,
    "consumer_key": <USOS consumer key>,
    "consumer_secret": <USOS consumer secret key>
}
```