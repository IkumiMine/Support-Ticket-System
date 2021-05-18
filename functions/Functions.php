<?php
namespace assignment2\functions;
class Functions {

    //format date and time from xml to display
    public function formatDate($date) {
        $date_issue = date_create($date);
        $new_date = date_format($date_issue, 'Y-m-d H:i:s');
        return $new_date;
    }
}