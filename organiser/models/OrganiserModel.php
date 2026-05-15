<?php
class OrganiserModel {
    public static function getProfileByUserId($user_id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM organiser_profiles WHERE user_id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $db->close();
        return $result;
    }

    public static function create($user_id, $org_name, $org_desc, $logo_path, $website) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO organiser_profiles (user_id, org_name, org_description, org_logo_path, website) VALUES (?,?,?,?,?)");
        $stmt->bind_param("issss", $user_id, $org_name, $org_desc, $logo_path, $website);
        $stmt->execute();
        $db->close();
    }
}
