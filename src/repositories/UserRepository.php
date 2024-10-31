<?php
class UserRepository
{
    private $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    /**
     * @return UserModel[]
     */
    public function findAll(): array
    {
        $stmt = $this->connection->query('SELECT id, username, email, created_at FROM users');
        return $stmt->fetchAll();
    }

    /**
     * @return UserModel[]
     */
    public function findById(int $id): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    /**
     * @return UserModel | null
     */
    public function findByEmail(string $email): ?UserModel
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        $users = $stmt->fetchAll();
        return count($users) > 0 ? new UserModel($users[0]) : null;
    }

    /**
     * @return UserModel[]
     */
    public function create(UserModel $data): array
    {
        $stmt = $this->connection->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->execute([
            'username' => $data->username,
            'email' => $data->email,
            'password' => $data->password
        ]);

        return $this->findById($this->connection->lastInsertId());
    }

    /**
     * @return UserModel[]
     */
    public function update(int $id, array $data): array
    {
        $stmt = $this->connection->prepare('UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id');
        $stmt->execute(array_merge($data, ['id' => $id]));

        return $this->findById($id);
    }

    public function delete(int $id): void
    {
        $stmt = $this->connection->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
