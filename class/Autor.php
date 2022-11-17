<?php


class Autor
{
    private int $id; //PK
    private string $vorname;
    private string $nachname;
    private array $buchArr;

    /**
     * Autor constructor.
     * @param int|null $id
     * @param string|null $vorname
     * @param string|null $nachname
     */
    public function __construct(int $id = null, string $vorname = null, string $nachname = null)
    {
        if (isset($id) && isset($vorname) && isset($nachname)) {
            $this->id = $id;
            $this->vorname = $vorname;
            $this->nachname = $nachname;
        }
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
    public function getVorname(): string
    {
        return $this->vorname;
    }

    /**
     * @return string
     */
    public function getNachname(): string
    {
        return $this->nachname;
    }

    public function getAutorById(int $id): Autor
    {
        try {
            $dbh = Db::getConnection();

            $sql = "SELECT * FROM autor WHERE id=:id";

            $sth = $dbh->prepare($sql);
            $sth->bindParam(':id', $id);
            $sth->execute();
            $a = $sth->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return new Autor($id, $a['vorname'], $a['nachname']);
    }

    public function getAlleAutorenByTitel(string $titel)
    {
        $b = new Buch();
        $buch = $b->getBuchByTitel($titel);
        return $buch->getAutoren();
    }
    public static function getAllAsObjects(): array
    {
        try {
            // Datenbankverbindung herstellen
            $dbh = Db::getConnection();

            // Datenbank abfragen
            $sql = "SELECT * FROM autor";

            // $sth ist ein prepared statement, dass später ausgeführt wird
            $sth = $dbh->prepare($sql);
            $sth->execute();
            $autoren = $sth->fetchAll(PDO::FETCH_FUNC, 'Autor::buildFromPDO');
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return $autoren;
    }

    public static function buildFromPDO(int $id, string $vorname, string $nachname): Autor
    {
        return new Autor($id, $vorname, $nachname);
    }

    public function getName(){
        return $this->getVorname() . ' ' . $this->getNachname();
    }
}