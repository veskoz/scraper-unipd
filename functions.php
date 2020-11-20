<?php

require 'vendor/autoload.php';
include 'classes.php';
include 'config.php';

use Goutte\Client;

function setErrors() {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ini_set('display_errors', true);
}

/** 
 * getUnits
 *
 * Iterate all the units than will provide each ID and Name to @see getRooms
 *
 */
function getUnits() {
    $client = new Client();
    $crawler = $client->request('GET', 'https://gestionedidattica.unipd.it/Aule/index.php?content=gestore_aree_pubblico&_lang=it&parentID=0&entryID=1');

    $crawler->filter('option')->each(function ($node, $z) {
        $unitID = $node->attr('value');
        $unitName = $node->text();

        getRooms($unitID, $unitName);
        /*
          if ($z > 2) {
          die("\n\nLimite forzato");
          }
         */
    });
}

/**
 * getUnitCode
 * 
 * Get the code of the unit by searching the last occurrence of '(' 
 * 
 * @param unitID The ID of the unit
 * @return String The code of the unit
 */
function getUnitCode($unitID) {

    $client = new Client();
    $crawler = $client->request('GET', 'https://gestionedidattica.unipd.it/Aule/index.php?content=gestore_aree_pubblico&cercaSede=' . $unitID);

    $title = $crawler->filter('#detailsForm')->filter('b')->text();
    $lastOccurance = strrpos($title, '(');
    
    return substr($title, $lastOccurance + 1, -1);
}

function getRooms($unitID, $unitName) {
    $client = new Client();
    $crawler = $client->request('GET', 'https://gestionedidattica.unipd.it/Aule/index.php?content=gestore_aree_pubblico&cercaSede=' . $unitID);

    $crawler->filter('#info_sede')->each(function ($node) use (&$unitID, &$unitName) {
        $unit = new Unit();
        $unit->set_unit_id($unitID);
        $unit->set_unit_name($unitName);
        $unit->set_unit_address($node->filter('td')->eq(0)->text());
        $unit->set_unit_referent($node->filter('td')->eq(2)->text());
        $unit->set_unit_code(getUnitCode($unitID));
        insertUnit($unit);
    });

    $crawler->filter('#roomsListCheck')->filter('tr')->each(function ($node, $i) use (&$unitID) {
        if ($i) {//ignore header
            $room = new Room();
            $room->set_room_code($node->filter('td')->eq(1)->text());
            $room->set_room_name($node->filter('td')->eq(2)->text());
            $room->set_room_floor($node->filter('td')->eq(3)->text());
            $room->set_room_capacity($node->filter('td')->eq(4)->text());
            $room->set_room_type($node->filter('td')->eq(5)->text());
            $room->set_room_fk_to_unit($unitID);
            insertRoom($room);
        }
    });
}

function createTables() {
    $link = new MySQL();
    $createUnits = "CREATE TABLE units ( "
            . "unit_id INT NOT NULL , "
            . "unit_code VARCHAR(255) NOT NULL , "
            . "unit_name VARCHAR(255) NULL , "
            . "unit_address VARCHAR(255) NULL , "
            . "unit_referent VARCHAR(255) NULL , "
            . "PRIMARY KEY (unit_id)) ENGINE = InnoDB;";

    $createRooms = "CREATE TABLE rooms ( "
            . "room_id INT NOT NULL AUTO_INCREMENT , "
            . "room_code INT NULL , "
            . "room_name VARCHAR(255) NULL , "
            . "room_floor VARCHAR(255) NULL , "
            . "room_capacity INT NULL , "
            . "room_type VARCHAR(255) NULL , "
            . "room_fk_to_unit INT NOT NULL , "
            . "PRIMARY KEY (room_id)) ENGINE = InnoDB;";

    $createFK_to_unit = "ALTER TABLE rooms ADD CONSTRAINT room_fk_to_unit FOREIGN KEY (room_fk_to_unit) REFERENCES units(unit_id) ON DELETE RESTRICT ON UPDATE RESTRICT; ";

    if (mysqli_query($link->link(), $createUnits)) {
        echo "<br></br>Table Units created successfully<br></br>";
        if (mysqli_query($link->link(), $createRooms)) {
            echo "<br></br>Table Rooms created successfully<br></br>";
            if (mysqli_query($link->link(), $createFK_to_unit)) {
                echo "<br></br>FK created successfully<br></br>";
            } else {
                echo "<br></br>Error creating FK: " . mysqli_error($link->link()) . "<br></br>";
            }
        } else {
            echo "<br></br>Error creating table Rooms: " . mysqli_error($link->link()) . "<br></br>";
        }
    } else {
        echo "<br></br>Error creating table Units: " . mysqli_error($link->link()) . "<br></br>";
    }

    $link->close();
}

function insertUnit(Unit $unit) {
    $link = new MySQL();

    $insertUnit = "INSERT INTO units VALUES ("
            . $unit->get_unit_id() . ","
            . "'" . $unit->get_unit_code() . "',"
            . "'" . mysqli_real_escape_string($link->link(), $unit->get_unit_name()) . "',"
            . "'" . mysqli_real_escape_string($link->link(), $unit->get_unit_address()) . "',"
            . "'" . mysqli_real_escape_string($link->link(), $unit->get_unit_referent()) . "');";

    if (mysqli_query($link->link(), $insertUnit)) {
        // echo "<br></br>Insert Unit was successfully<br></br>";
    } else {
        echo "<br></br>Error while inserting Unit: " . mysqli_error($link->link()) . "<br></br>";
        echo "<br></br>SQL -> " . $insertUnit . "<br></br>";
    }

    $link->close();
}

function insertRoom(Room $room) {
    $link = new MySQL();

    $insertRoom = "INSERT INTO rooms VALUES ("
            . "NULL" . ","
            . $room->get_room_code() . ","
            . "'" . mysqli_real_escape_string($link->link(), $room->get_room_name()) . "',"
            . "'" . $room->get_room_floor() . "',"
            . $room->get_room_capacity() . ","
            . "'" . $room->get_room_type() . "',"
            . $room->get_room_fk_to_unit() . ");";

    if (mysqli_query($link->link(), $insertRoom)) {
        //echo "<br></br>Insert Room was successfully<br></br>";
    } else {
        echo "<br></br>Error while inserting Room: " . mysqli_error($link->link()) . "<br></br>";
        echo "<br></br>SQL -> " . $insertRoom . "<br></br>";
    }

    $link->close();
}