<?php

class UserModel {

    private PDO $db;

    public function __construct() {
        $this->db = getPDO();
    }

    public function findByLogin(string $login): array|false {
        $st = $this->db->prepare('SELECT * FROM users WHERE login = ? LIMIT 1');
        $st->execute([$login]);
        return $st->fetch();
    }

    public function create(string $login, string $plainPassword): int {
        $hash = password_hash($plainPassword, PASSWORD_BCRYPT);
        $st   = $this->db->prepare('INSERT INTO users (login, senha) VALUES (?, ?)');
        $st->execute([$login, $hash]);
        return (int) $this->db->lastInsertId();
    }

    public function verifyPassword(string $plain, string $hash): bool {
        return password_verify($plain, $hash);
    }

    public function loginExists(string $login): bool {
        $st = $this->db->prepare('SELECT id FROM users WHERE login = ? LIMIT 1');
        $st->execute([$login]);
        return (bool) $st->fetch();
    }
}
