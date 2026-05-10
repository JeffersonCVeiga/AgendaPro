<?php

class ActivityModel {

    private PDO $db;

    public function __construct() {
        $this->db = getPDO();
    }

    public function allByUser(int $userId, string $status = ''): array {
        $sql    = 'SELECT * FROM activities WHERE user_id = ?';
        $params = [$userId];
        if ($status !== '') {
            $sql    .= ' AND status = ?';
            $params[] = $status;
        }
        $sql .= ' ORDER BY inicio ASC';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function findById(int $id, int $userId): array|false {
        $st = $this->db->prepare(
            'SELECT * FROM activities WHERE id = ? AND user_id = ? LIMIT 1'
        );
        $st->execute([$id, $userId]);
        return $st->fetch();
    }

    public function create(int $userId, array $data): int {
        $st = $this->db->prepare('
            INSERT INTO activities (user_id, nome, descricao, inicio, termino, status)
            VALUES (:user_id, :nome, :descricao, :inicio, :termino, :status)
        ');
        $st->execute([
            ':user_id'   => $userId,
            ':nome'      => $data['nome'],
            ':descricao' => $data['descricao'] ?? '',
            ':inicio'    => $data['inicio'],
            ':termino'   => $data['termino'],
            ':status'    => $data['status'] ?? 'pendente',
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $userId, array $data): bool {
        $st = $this->db->prepare('
            UPDATE activities
               SET nome      = :nome,
                   descricao = :descricao,
                   inicio    = :inicio,
                   termino   = :termino,
                   status    = :status
             WHERE id = :id AND user_id = :user_id
        ');
        $st->execute([
            ':nome'      => $data['nome'],
            ':descricao' => $data['descricao'] ?? '',
            ':inicio'    => $data['inicio'],
            ':termino'   => $data['termino'],
            ':status'    => $data['status'],
            ':id'        => $id,
            ':user_id'   => $userId,
        ]);
        return $st->rowCount() > 0;
    }

    public function updateStatus(int $id, int $userId, string $status): bool {
        $allowed = ['pendente', 'concluida', 'cancelada'];
        if (!in_array($status, $allowed, true)) return false;
        $st = $this->db->prepare(
            'UPDATE activities SET status = ? WHERE id = ? AND user_id = ?'
        );
        $st->execute([$status, $id, $userId]);
        return $st->rowCount() > 0;
    }

    public function delete(int $id, int $userId): bool {
        $st = $this->db->prepare(
            'DELETE FROM activities WHERE id = ? AND user_id = ?'
        );
        $st->execute([$id, $userId]);
        return $st->rowCount() > 0;
    }

    public function forCalendar(int $userId): array {
        $rows = $this->allByUser($userId);
        return array_map(fn($a) => [
            'id'         => $a['id'],
            'title'      => $a['nome'],
            'start'      => $a['inicio'],
            'end'        => $a['termino'],
            'className'  => 'status-' . $a['status'],
            'extendedProps' => ['status' => $a['status']],
        ], $rows);
    }

    public function countsByUser(int $userId): array {
        $st = $this->db->prepare('
            SELECT status, COUNT(*) AS total
              FROM activities
             WHERE user_id = ?
             GROUP BY status
        ');
        $st->execute([$userId]);
        $result = ['pendente' => 0, 'concluida' => 0, 'cancelada' => 0, 'total' => 0];
        foreach ($st->fetchAll() as $row) {
            $result[$row['status']] = (int) $row['total'];
            $result['total'] += (int) $row['total'];
        }
        return $result;
    }
}
