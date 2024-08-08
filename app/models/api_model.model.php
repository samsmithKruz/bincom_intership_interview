<?php

class api_model extends Database
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
    public function fetchLgas()
    {
        return $this->db->query("SELECT lga_id, lga_name FROM lga WHERE state_id = :state_id")
            ->bind(':state_id', 25)
            ->resultSet();
    }
    public function fetchWards($lgaId)
    {
        return $this->db->query("SELECT ward_id, ward_name FROM ward WHERE lga_id = :lga_id")
            ->bind(':lga_id', $lgaId)
            ->resultSet();
    }
    public function fetchPollingUnits($wardId)
    {
        return $this->db->query("SELECT uniqueid as  polling_unit_id, polling_unit_name FROM polling_unit WHERE ward_id = :ward_id")
            ->bind(':ward_id', $wardId)
            ->resultSet();
    }
    public function fetchResults($pollingUnitId)
    {
        return $this->db->query("
            SELECT 
                FORMAT(r.party_score,0) as party_score,
                r.party_abbreviation

            FROM 
                announced_pu_results r
            WHERE 
                r.polling_unit_uniqueid = :polling_unit_id
        ")
            ->bind(':polling_unit_id', $pollingUnitId)
            ->resultSet();
    }
    public function fetchTotal($lga_id)
    {
        return $this->db->query("
            SELECT 
                r.party_abbreviation, 
                FORMAT(COALESCE(SUM(r.party_score), 0),0) AS party_score
            FROM 
                announced_pu_results as r
            LEFT JOIN
                polling_unit AS pu ON r.polling_unit_uniqueid = pu.polling_unit_id
            WHERE 
                pu.lga_id = :lga_id
            GROUP BY 
               r.party_abbreviation
        ")
            ->bind(':lga_id', $lga_id)
            ->resultSet();
    }
    public function getVote()
    {
        extract((array)$_GET);
        return $this->db->query("
            SELECT 
                ar.party_score, 
                pu.ward_id,
                pu.polling_unit_name,
                pu.lga_id,
                ar.party_abbreviation
            FROM 
                announced_pu_results ar 
            LEFT JOIN polling_unit pu ON 
                pu.uniqueid = ar.polling_unit_uniqueid 
            WHERE 
                pu.ward_id =:ward
                AND ar.party_abbreviation =:party
                AND pu.polling_unit_name =:polling_unit 
        ")
            ->bind(":ward", $ward)
            ->bind(":polling_unit", $polling_unit)
            ->bind(":party", $party)
            ->single();
    }
    public function collate()
    {
        extract((array)$_GET);

        $this->db->beginTransaction();
        try {
            // Query to get uniqueid from announced_pu_results
            $uniqueid = $this->db->query("
            SELECT pu.uniqueid
            FROM announced_pu_results ar
            LEFT JOIN polling_unit pu ON pu.uniqueid = ar.polling_unit_uniqueid
            WHERE pu.ward_id = :ward
              AND pu.polling_unit_name = :polling_unit
              AND ar.party_abbreviation = :party
        ")
                ->bind(":ward", $ward)
                ->bind(":polling_unit", $poll)
                ->bind(":party", $party)
                ->single()->uniqueid;

            if ($uniqueid) {
                // Perform DELETE and INSERT in a single query
                $this->db->query("
                    DELETE FROM announced_pu_results
                    WHERE polling_unit_uniqueid = :id AND party_abbreviation = :party;
                ")
                    ->bind(":id", $uniqueid)
                    ->bind(":party", $party)
                    ->execute();
                $this->db->query("
                    INSERT INTO announced_pu_results (polling_unit_uniqueid, party_abbreviation, party_score, entered_by_user, date_entered, user_ip_address)
                    VALUES (:id, :party, :vote, 'Bose', NOW(), '192.168.1.101')
                ")
                    ->bind(":id", $uniqueid)
                    ->bind(":party", $party)
                    ->bind(":vote", $vote)
                    ->execute();

                if ($this->db->rowCount() > 0) {
                    $this->db->commitTransaction();
                    return (object)["state" => 1, "message" => "Vote collated."];
                }
                $this->db->rollbackTransaction();
                return (object)["state" => 0, "message" => $__];
                // return (object)["state" => 0, "message" => "Vote not collated."];
            }
            $this->db->rollbackTransaction();
            return (object)["state" => 0, "message" => "An Error occurred."];
        } catch (Exception $e) {
            $this->db->rollbackTransaction();
            return (object)["state" => 0, "message" => $e->getMessage()];
        }
    }
    public function getParty()
    {
        return $this->db->query("
            SELECT 
                partyid,
                partyname
            FROM 
                party
        ")
            ->resultSet();
    }
}
