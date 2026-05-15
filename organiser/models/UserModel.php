<?php
class UserModel {
    public static function findByEmail($email) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $db->close();
        return $result;
    }

    public static function findById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $db->close();
        return $result;
    }

    public static function create($name, $email, $hash, $phone, $role) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO users (name, email, password_hash, phone, role) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $name, $email, $hash, $phone, $role);
        $stmt->execute();
        $id = $stmt->insert_id;
        $db->close();
        return $id;
    }
}
