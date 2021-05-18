<?php
namespace assignment2\models;

class Xml {

    //load user xml file
    public function loadUser(){
        $xml_user = simplexml_load_file("xml/users-support-ticket.xml");
        return $xml_user;
    }

    //get user data
    public function getUsers($xml_user) {

        $users = array (
            "id" => $xml_user->xpath("//userId"),
            "pass" => $xml_user->xpath("//password"),
            "type" => $xml_user->xpath("//@userType"),
            "fname" => $xml_user->xpath("//name/first"),
            "lname" => $xml_user->xpath("//name/last")
        );

        return $users;
    }

    //get selected user data by ID
    public function getSelectedUser($id, $xml_user) {

        $selecred_user = array (
            "type" => strval($xml_user->xpath("//userId[text() = ".$id."]/@userType")[0]),
            "fname" => strval($xml_user->xpath("//userId[text() = ".$id."]/..//first")[0]),
            "lname" => strval($xml_user->xpath("//userId[text() = ".$id."]/..//last")[0]),
            "id" => strval($xml_user->xpath("//userId[text() = ".$id."]")[0])
        );

        return $selecred_user;
    }

    //load ticket xml file
    public function loadTickets() {
        $xml_ticket = simplexml_load_file("xml/tickets-support-ticket.xml");
        return $xml_ticket;
    }

    //get selected ticket by user ID
    public function getSelectedTicketById($id, $xml_ticket) {
        $selected_ticket = $xml_ticket->xpath("//ticket[userId[text()=".$id."]]");
        return $selected_ticket;
    }

    //get selected ticket by ticket number
    public function getSelectedTicketByNum($number, $xml_ticket) {
        $selected_ticket = $xml_ticket->xpath("//ticket[number[text()=".$number."]]")[0];
        return $selected_ticket;
    }


}