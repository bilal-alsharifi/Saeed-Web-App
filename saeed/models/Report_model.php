<?php

include_once ('../libraries/Model.php');

class Report_model extends Model {

    function getAllPatients() {
        $query = "SELECT * FROM patient;";
        $sth = $this->db->prepare($query);
        $sth->execute();
        $rows = $sth->fetchAll();
        return $rows;
    }
    
    function getPatientDoctorVisits($patient_id) {
        $query = "SELECT doctorvisit.patient_id, doctor.firstName, doctor.lastName, doctorvisit.date, doctorvisit.notes, doctorvisit.price from doctorvisit
                INNER JOIN doctor ON (doctorvisit.doctor_id = doctor.user_id)
                WHERE doctorvisit.patient_id = :patient_id
                AND doctorvisit.date BETWEEN  (DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND CURDATE();";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patient_id', $patient_id);
        $sth->execute();
        $rows = $sth->fetchAll();
        return $rows;
    }

    function getPatientHospitalServices($patient_id) {
        $query = "SELECT hospital.name, hospitalservice.date, hospitalservice.notes, hospitalservice.price FROM hospitalservice
                INNER JOIN hospital ON (hospitalservice.hospital_id = hospital.user_id)
                INNER JOIN doctorvisit ON (hospitalservice.doctorVisit_id = doctorvisit.id)
                WHERE doctorvisit.patient_id = :patient_id
                AND hospitalservice.date BETWEEN  (DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND CURDATE();";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patient_id', $patient_id);

        $sth->execute();
        $rows = $sth->fetchAll();
        return $rows;
        ;
    }

    function getPatientMedicines($patient_id) {
        $query = "SELECT pharmacy.name, medicine.date, medicine.notes, medicine.price FROM medicine
                    INNER JOIN pharmacy ON (medicine.pharmacy_id = pharmacy.user_id)
                    INNER JOIN doctorvisit ON (medicine.doctorVisit_id = doctorvisit.id)
                    WHERE doctorvisit.patient_id = :patient_id
                    AND medicine.date BETWEEN  (DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND CURDATE();";
        $sth = $this->db->prepare($query);
        $sth->bindParam('patient_id', $patient_id);
        $sth->execute();

        $rows = $sth->fetchAll();
        return $rows;
    }

}

?>
