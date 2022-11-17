<?php

/*
 * Design Pattern Singleton
 * man möchte nur 1 Objekt aus dieser Klasse haben
 */

class Db
{
    // $dbh meint Datenbankhandle, die Verbindung zur Datenbank
    private static object $dbh;

    public static function getConnection(): object
    {
        // bei Verbindung nach außerhalb kann immer etwas schief gehen
        // wofür wir als Programmierer nichts können
        // falls si ein Fehler auftritt, können wir ihn mit try-catch abfangen
        try {
            if (!isset(self::$dbh)) {
                self::$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
            }
        } catch (PDOException $e){
            // $e bekommt im Fehlerfall Daten von PDO, die den Fehler näher beschreibt
            echo 'Datenbankverbindung funktioniert nicht: '. $e->getMessage();
        }
        return self::$dbh;
    }
}