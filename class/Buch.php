<?php

/*
 *
 */

class Buch
{
    private int $id;
    private string $titel;
    private Zimmer $zimmer;
    private Verlag $verlag;
    private array $autoren;

    /**
     * Buch constructor.
     * @param int|null $id
     * @param string|null $titel
     * @param Zimmer|null $zimmer
     * @param Verlag|null $verlag
     * @param Autor[] $autoren
     */
    public function __construct(int $id = null, string $titel = null, Zimmer $zimmer = null,
                                Verlag $verlag = null, array $autoren = null)
    {
        if (isset($id) && isset($titel) && isset($verlag)
            && isset($zimmer) && isset($autoren)) {
            $this->id = $id;
            $this->titel = $titel;
            $this->verlag = $verlag;
            $this->zimmer = $zimmer;
            $this->autoren = $autoren;
        }
    }

    /**
     * @param string $titel
     */
    public function setTitel(string $titel): void
    {
        $this->titel = $titel;
    }

    /**
     * @return Verlag
     */
    public function getVerlag(): Verlag
    {
        return $this->verlag;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitel(): string
    {
        return $this->titel;
    }

    /**
     * @return Zimmer
     */
    public function getZimmer(): Zimmer
    {
        return $this->zimmer;
    }

    /**
     * @return array|Autor[]
     */
    public function getAutoren(): array
    {
        return $this->autoren;
    }

    public static function getAllAsObjects(): array
    {
        try {
            // Datenbankverbindung herstellen
            $dbh = Db::getConnection();

            // Datenbank abfragen
            $sql = "SELECT * FROM buch";

            // $sth ist ein prepared statement, dass später ausgeführt wird
            $sth = $dbh->prepare($sql);
            $sth->execute();
            $buecher = $sth->fetchAll(PDO::FETCH_FUNC, 'Buch::buildFromPDO');
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return $buecher;
    }

    public static function buildFromPDO(int $id, string $titel, int $zimmer_id, int $verlag_id): Buch
    {
        $z = new Zimmer();
        $v = new Verlag();
        $a2b = new Autor2Buch();
        return new Buch($id, $titel, $z->getZimmerById($zimmer_id), $v->getVerlagById($verlag_id),
            $a2b->getAutorByBuch_id($id));
    }

    // ein Buch aus Tabelle buch erstellen
    public function getBuchById(int $id): Buch
    {
        try {
            // Datenbank abfragen
            $dbh = Db::getConnection();

            // Datenbank abfragen
            $sql = "SELECT * FROM buch WHERE id=:id";

            // $sth ist ein prepared statement, dass später ausgeführt wird
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':id', $id);
            $sth->execute();
            $b = $sth->fetch(PDO::FETCH_ASSOC);

            $z = new Zimmer();
            $v = new Verlag();
            $a2b = new Autor2Buch();

        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return new Buch($id, $b['titel'], $z->getZimmerById($b['zimmer_id']), $v->getVerlagById($b['verlag_id']),
            $a2b->getAutorByBuch_id($id));
    }

    public function getBuchByTitel(string $titel): Buch
    {
        try {
            // Datenbank abfragen
            $dbh = Db::getConnection();

            // Datenbank abfragen
            $sql = "SELECT * FROM buch WHERE titel=:titel";

            // $sth ist ein prepared statement, dass später ausgeführt wird
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':titel', $titel, PDO::PARAM_STR);
            $sth->execute();
            $b = $sth->fetch(PDO::FETCH_ASSOC);

            $z = new Zimmer();
            $v = new Verlag();
            $a2b = new Autor2Buch();

        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return new Buch($b['id'], $b['titel'], $z->getZimmerById($b['zimmer_id']), $v->getVerlagById($b['verlag_id']),
            $a2b->getAutorByBuch_id($b['id']));
    }

    public function getAlleBuecherByZimmer(Zimmer $z): array
    {
        try {
            // Datenbankverbindung herstellen
            $dbh = Db::getConnection();

            // Datenbank abfragen
            $sql = "SELECT * FROM buch WHERE zimmer_id=:zimmer_id";

            // $sth ist ein prepared statement, dass später ausgeführt wird
            $sth = $dbh->prepare($sql);
            $zimmer_id = $z->getId(); // Zwischenvariable aus php-Technischen Gründen
            $sth->bindParam(':zimmer_id', $zimmer_id);
            $sth->execute();
            $buecher = $sth->fetchAll(PDO::FETCH_FUNC, 'Buch::buildFromPDO');
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return $buecher;
    }

    public function update(): bool
    {
        try {
            $dbh = Db::getConnection();
            $sql = "UPDATE buch
                    SET titel=:titel,
                        verlag_id=:verlag_id,
                        zimmer_id=:zimmer_id
                    WHERE id=:id";
            $sth = $dbh->prepare($sql);
            // Zwischenvariablen um die "notice" zu verhindern
            $zimmer_id = $this->zimmer->getId();
            $verlag_id = $this->verlag->getId();
            // Parameter "binden"
            $sth->bindParam(':titel', $this->titel, PDO::PARAM_STR);
            $sth->bindParam(':verlag_id', $verlag_id, PDO::PARAM_INT);
            $sth->bindParam(':zimmer_id', $zimmer_id, PDO::PARAM_INT);
            $sth->bindParam(':id', $this->id, PDO::PARAM_INT);
            return $sth->execute();
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
            return false;
        }
    }

    public function setZimmer(int $zimmer_id)
    {
        $z = new Zimmer();
        $this->zimmer = $z->getZimmerById($zimmer_id);
    }

    public function setVerlag(int $verlag_id)
    {
        $v = new Verlag();
        $this->verlag = $v->getVerlagById($verlag_id);
    }


}