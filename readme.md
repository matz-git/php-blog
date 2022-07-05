Ein Blog System in PHP. Einträge werden verschlüsselt auf dem Server gespeichert. Dabei besteht jeder Eintrag aus einer Datei, deren Inhalt aus HTML bestehen kann und mithilfe Base64 können Bilder in der Datei gespeichert werden.

## Password Hash erstellen
Folgender Befehl gibt ein Hash für das gewählte Password zurück:

`php password.php -g password`

Dieser Hash muss in "check.php" eingetragen werden.


## Eine Datei verschlüsseln:
Den zu verschlüsselnen Text in die Datei "file.txt" schreiben. Der Befehl:

`php encrypt.php -p password`

erzeugt eine neue Datei mit der Endung .enc im "files" Ordner. Der Inhalt wird mit dem Advanced Encryption Standard (AES-128) Verfahren verschlüsselt. 
