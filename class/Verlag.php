<?php


class Verlag
{
    private int $id;
    private string $name;

    /**
     * Verlag constructor.
     * @param int|null $id
     * @param string|null $name
     */
    public function __construct(int $id = null, string $name = null)
    {
        if (isset($id) && isset($name)) {
            $this->id = $id;
            $this->name = $name;
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
    public function getName(): string
    {
        return $this->name;
    }

    public static function getAllAsObjects(): array
    {
        try {
            // Datenbankverbindung herstellen
            $dbh = Db::getConnection();

            // Datenbank abfragen
            $sql = "SELECT * FROM verlag";
            $sth = $dbh->prepare($sql);
            $sth->execute();
            $verlagArr = $sth->fetchAll(PDO::FETCH_FUNC, 'Verlag::buildFromPDO');
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return $verlagArr;
    }

    public static function buildFromPDO(int $id, string $name): Verlag
    {
        return new Verlag($id, $name);
    }

    public function getVerlagById(int $id): Verlag
    {
        try {
            $dbh = Db::getConnection();

            $sql = "SELECT * FROM verlag WHERE id=:id";

            $sth = $dbh->prepare($sql);
            $sth->bindParam(':id', $id);
            $sth->execute();
            $v = $sth->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return new Verlag($id, $v['name']);
    }

    public function insertIntoVerlag(string $name): bool
    {
        try {
            $dbh = Db::getConnection();
            $sql = "INSERT INTO verlag(name) VALUES(:name)";
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':name', $name);
            $sth->execute();
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
            return false;
        }
        return true;
    }

}