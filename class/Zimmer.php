<?php


class Zimmer
{
    private int $id; // PK
    private string $name;

    /**
     * Zimmer constructor.
     * @param string|null $id
     * @param string|null $name
     */
    // Konstruktor wird überladen, d.h. ich kann ihn ohne  oder mit Parametern aufrufen
    public function __construct(string $id = null, string $name = null)
    {
        if (isset($id) && isset($name)) {
            $this->id = $id;
            $this->name = $name;
        }
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /*
     * Rückgabe: ein Array mit allen Objekten,
     * zu denen es Datensätze in der Tabelle Zimmer gibt
     */
    public static function getAllAsObjects(): array
    {
        try {
            // Datenbankverbindung herstellen
            $dbh = Db::getConnection();

            // Datenbank abfragen
            $sql = "SELECT * FROM zimmer";

            // $sth ist ein prepared statement, dass später ausgeführt wird
            $sth = $dbh->prepare($sql);
            $sth->execute();
            $zimmerArr = $sth->fetchAll(PDO::FETCH_FUNC, 'Zimmer::buildFromPDO');
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return $zimmerArr;
    }

    public static function buildFromPDO(int $id, string $name): Zimmer
    {
        return new Zimmer($id, $name);
    }

    public function getZimmerById(int $id): Zimmer
    {
//        $this->id = $id;
        try {
            // Datenbankverbindung herstellen
            $dbh = Db::getConnection();

            // Datenbank abfragen
            $sql = "SELECT * FROM zimmer WHERE id=:id";

            // $sth ist ein prepared statement, dass später ausgeführt wird
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':id', $id);
            $sth->execute();
            $z = $sth->fetch(PDO::FETCH_ASSOC);
//            $zimmerArr = $sth->fetchAll(PDO::FETCH_FUNC, 'Zimmer::buildFromPDO');
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return new Zimmer($id, $z['name']);
    }

    public function getZimmerByName(string $name): Zimmer
    {
        try {
            $dbh = Db::getConnection();
            $sql = "SELECT * FROM zimmer WHERE name=:name";
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':name', $name);
            $sth->execute();
            $z = $sth->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return new Zimmer($z['id'], $z['name']);
    }

    public function getAllBuecher(): array
    {
        $b = new Buch();
        return $b->getAlleBuecherByZimmer($this);
    }
}