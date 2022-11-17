<?php


class Autor2Buch
{
    private int $id;
    private int $autor_id;
    private int $buch_id;

    /**
     * Autor2Buch constructor.
     * @param int|null $id
     * @param int|null $autor_id
     * @param int|null $buch_id
     */
    public function __construct(int $id = null, int $autor_id = null, int $buch_id = null)
    {
        if (isset($id) && isset($autor_id) && isset($buch_id)) {
            $this->id = $id;
            $this->autor_id = $autor_id;
            $this->buch_id = $buch_id;
        }
    }

    public function getAutorByBuch_id(int $buch_id): array
    {
        try {
            $dbh = Db::getConnection();

            $sql = "SELECT * FROM autor2buch WHERE buch_id=:buch_id";

            $sth = $dbh->prepare($sql);
            $sth->bindParam(':buch_id', $buch_id);
            $sth->execute();
            $a2buch = $sth->fetchAll(PDO::FETCH_ASSOC);
            $a = new Autor();
            $autoren = [];
            foreach ($a2buch as $a2b){
                array_push($autoren, $a->getAutorById($a2b['autor_id']));
            }
        } catch (PDOException $e) {
            echo 'Datenbankverbindung funktioniert nicht: ' . $e->getMessage();
        }
        return $autoren;
    }

}