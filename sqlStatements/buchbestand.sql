# Database löschen, neu erstellen und auswählen
DROP DATABASE IF EXISTS Buchbestand2;
CREATE DATABASE IF NOT EXISTS Buchbestand2;
USE Buchbestand2;

# Tabellen anlegen
#verlag
CREATE TABLE verlag
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
);

# autor
CREATE TABLE autor
(
    id       INT AUTO_INCREMENT PRIMARY KEY,
    vorname  VARCHAR(45),
    nachname VARCHAR(45)
);

# zimmer
CREATE TABLE zimmer
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(45)
);

# buch
CREATE TABLE buch
(
    id        INT AUTO_INCREMENT PRIMARY KEY,
    titel     VARCHAR(100),
    zimmer_id INT,
    verlag_id INT
);

# autor2buch
CREATE TABLE autor2buch
(
    id       INT AUTO_INCREMENT PRIMARY KEY,
    autor_id INT,
    buch_id  INT
);

# Tabellen füllen
# verlag
INSERT INTO verlag(id, name)
VALUES (NULL, 'Adison-Wesley'),
       (NULL, 'Semmelverlag');

# autor
INSERT INTO autor(id, vorname, nachname)
VALUES (NULL, 'Eric', 'Gamma'),
       (NULL, 'John', 'Vlissides'),
       (NULL, 'Pete', 'Meyer');

# zimmer
INSERT INTO zimmer(id, name)
VALUES (NULL, 'Arbeitszimmer'),
       (NULL, 'Schlafzimmer');

# buch
INSERT INTO buch(id, titel, zimmer_id, verlag_id)
VALUES (NULL, 'Design Pattern', 1, 1),
       (NULL, 'Advanced Programming', 2, 1),
       (NULL, 'Nice Dreams', 2, 2);

# autor2buch
INSERT INTO autor2buch(id, autor_id, buch_id)
VALUES (NULL, 1, 1),
       (NULL, 2, 1),
       (NULL, 1, 2),
       (NULL, 3, 3);

# FOREIGn KEYs
# buch - zimmer
ALTER TABLE buch
    ADD FOREIGN KEY (zimmer_id)
        REFERENCES zimmer (id);

# buch - verlag
ALTER TABLE buch
    ADD FOREIGN KEY (verlag_id)
        REFERENCES verlag (id);

# autor2buch - autor
ALTER TABLE autor2buch
    ADD FOREIGN KEY (autor_id)
        REFERENCES autor (id);

# autor2buch - buch
ALTER TABLE autor2buch
    ADD FOREIGN KEY (buch_id)
        REFERENCES buch (id);
