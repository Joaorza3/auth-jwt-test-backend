<?php
class UserModel
{
    public int $id;
    public string $username;
    public string $email;
    public string $password;
    public string $created_at;

    public function __construct(array $data)
    {
        $this->validate($data);

        $this->id = $data['id'] ?? 0;
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
    }

    private function validate(array $data): void
    {
        if (!isset($data['username'])) throw new Exception('Username is required');
        if (!isset($data['email'])) throw new Exception('Email is required');
        if (!isset($data['password'])) throw new Exception('Password is required');
    }
}
