<?php

include_once ('../libraries/Model.php');

class Statistics_model extends Model {

    function graphArray() {
        $query = "SELECT DATE(date) AS date, SUM(price) AS price
                FROM
                (
                        SELECT date, price FROM doctorvisit
                        UNION
                        SELECT date, price FROM hospitalservice
                        WHERE date IS NOT null AND price IS NOT null
                        UNION
                        SELECT date, price FROM medicine
                        WHERE date IS NOT null AND price IS NOT null
                ) AS t
                GROUP BY DATE(date);";
        $sth = $this->db->prepare($query);
        $sth->execute();
        $rows = $sth->fetchAll();
        $values = array();
        foreach ($rows as $row) {
            $values[] = $row['date'] . "," . $row['price'];
        }
        $valuesString = implode($values, ";");
        return $valuesString;
    }

}

?>
