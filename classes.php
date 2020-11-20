<?php

class Unit {

    //Proerties
    public $unit_id;
    public $unit_code;
    public $unit_name;
    public $unit_address;
    public $unit_referent;

    // Methods
    function set_unit_id($id) {
        $this->unit_id = (int) $id;
    }

    function get_unit_id() {
        return $this->unit_id;
    }

    function set_unit_code($code) {
        $this->unit_code =  $code;
    }

    function get_unit_code() {
        return $this->unit_code;
    }

    function set_unit_name($name) {
        $this->unit_name = $name;
    }

    function get_unit_name() {
        return $this->unit_name;
    }

    function set_unit_address($address) {
        $this->unit_address = str_replace("Indirizzo: ", "", $address);
    }

    function get_unit_address() {
        return $this->unit_address;
    }

    function set_unit_referent($referent) {
        $this->unit_referent = str_replace("Referente: ", "", $referent);
        ;
    }

    function get_unit_referent() {
        return $this->unit_referent;
    }

    function show() {

        echo 'ID -> ' . $this->get_unit_id() . "<br></br>";
        echo 'NAME -> ' . $this->get_unit_name() . "<br></br>";
        echo 'ADDRESS -> ' . $this->get_unit_address() . "<br></br>";
        echo 'REFERENT -> ' . $this->get_unit_referent() . "<br></br>";
    }

}//End Class

class Room {

    //Proerties
    public $room_id;
    public $room_code;
    public $room_name;
    public $room_floor;
    public $room_capacity;
    public $room_type;
    public $room_fk_to_unit;

    // Methods
    function set_room_id($id) {
        $this->room_id = (int) $id;
    }

    function get_room_id() {
        return $this->room_id;
    }

    function set_room_code($code) {
        $this->room_code = (int) $code;
    }

    function get_room_code() {
        return $this->room_code;
    }

    function set_room_name($name) {
        $this->room_name = $name;
    }

    function get_room_name() {
        return $this->room_name;
    }

    function set_room_floor($floor) {
        $this->room_floor = $floor;
    }

    function get_room_floor() {
        return $this->room_floor;
    }

    function set_room_capacity($capacity) {
        $this->room_capacity = (int) $capacity;
    }

    function get_room_capacity() {
        return $this->room_capacity;
    }

    function set_room_type($type) {
        $this->room_type = $type;
    }

    function get_room_type() {
        return $this->room_type;
    }

    function set_room_fk_to_unit($fk_to_unit) {
        $this->room_fk_to_unit = (int) $fk_to_unit;
    }

    function get_room_fk_to_unit() {
        return $this->room_fk_to_unit;
    }

}//End Class