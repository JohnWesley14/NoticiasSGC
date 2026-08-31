<?php

declare(strict_types=1);

namespace Src\App\Infrastructure\Repositories;

use PDO;
use Src\App\Infrastructure\IRepositories\IGraficosRepository;
use Src\App\Models\Graficos;
use Src\Core\Database;

// TODO: renomeie para o seu domínio e adapte os campos SQL
class GraficosRepository implements IGraficosRepository
{
    private PDO $conn;

    public function __construct(Database $conn)
    {
        $this->conn = $conn->getConnection();
    }

    public function create(Graficos $graficos): Graficos
    {
        $sql = 'INSERT INTO tb_exemplo (id, titulo, descricao, status, created_at, updated_at)
                VALUES (:id, :titulo, :descricao, :status, :created_at, :updated_at)';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $graficos->getId(), PDO::PARAM_STR);
        $stmt->bindValue(':titulo', $graficos->getTitulo());
        $stmt->bindValue(':descricao', $graficos->getDescricao());
        $stmt->bindValue(':status', $graficos->getStatus(), PDO::PARAM_BOOL);
        $stmt->bindValue(':created_at', $graficos->getCreatedAt());
        $stmt->bindValue(':updated_at', $graficos->getUpdatedAt());
        $stmt->execute();

        return $graficos;
    }

    public function update(Graficos $graficos): Graficos
    {
        $sql = 'UPDATE tb_exemplo
                SET titulo = :titulo, descricao = :descricao,
                    status = :status, updated_at = :updated_at
                WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':titulo', $graficos->getTitulo());
        $stmt->bindValue(':descricao', $graficos->getDescricao());
        $stmt->bindValue(':status', $graficos->getStatus(), PDO::PARAM_BOOL);
        $stmt->bindValue(':updated_at', $graficos->getUpdatedAt());
        $stmt->bindValue(':id', $graficos->getId(), PDO::PARAM_STR);
        $stmt->execute();

        return $graficos;
    }

    public function getAll(): array
    {
        $sql = 'SELECT id, titulo, descricao, status FROM tb_exemplo ORDER BY id DESC';
        $stmt = $this->conn->query($sql);
        $itens = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itens[] = new Graficos(
                $row['titulo'],
                $row['descricao'],
                (bool) $row['status'],
                null,
                null,
                $row['id']
            );
        }

        return $itens;
    }

    public function getById(string $id): ?Graficos
    {
        $sql = 'SELECT * FROM tb_exemplo WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Graficos(
                $row['titulo'],
                $row['descricao'],
                (bool) $row['status'],
                $row['created_at'],
                $row['updated_at'],
                $row['id']
            );
        }

        return null;
    }

    public function delete(string $id): bool
    {
        $sql = 'DELETE FROM tb_exemplo WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function count(): int
    {
        $stmt = $this->conn->query('SELECT COUNT(*) FROM tb_exemplo');
        return (int) $stmt->fetchColumn();
    }
}
