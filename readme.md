# Instrukcja

Uruchamiamy aplikację komendą (wymagany jest zainstalowany docker): `sudo sh run.sh`

Naszą aplikację uruchamiamy komendą `php bin/console app:download-rand-images-from-website {website} {randNumber}`.
W pierwszym parametrze podajemy stronę skąd mamy pobrać zdjęcia, a w drugim podajemy liczbę zwracanych zdjęć.
Domyślnie mamy ustawioną stronę https://sklep.swiatkwiatow.pl/ oraz liczbę zwracanych zdjęć 3.
