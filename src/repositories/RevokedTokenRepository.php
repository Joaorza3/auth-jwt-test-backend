<?php
class RevokedTokenRepository
{
    private $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function find(string $token): ?RevokedTokenModel
    {
        $stmt = $this->connection->prepare('SELECT * FROM revoked_tokens WHERE token = :token LIMIT 1');
        $stmt->execute(['token' => $token]);

        $tokens = $stmt->fetchAll();
        return count($tokens) > 0 ? new RevokedTokenModel($tokens[0]) : null;
    }

    public function create(RevokedTokenModel $data): void
    {
        $stmt = $this->connection->prepare('INSERT INTO revoked_tokens (token) VALUES (:token)');
        $stmt->execute([
            'token' => $data->token
        ]);
    }
}
