<?php
class RevokedTokenModel
{
    public int $id;
    public string $token;
    public string $revoked_at;

    public function __construct(array $data)
    {
        $this->validate($data);

        $this->id = $data['id'] ?? 0;
        $this->token = $data['token'];
    }

    private function validate(array $data): void
    {
        if (!isset($data['token'])) throw new Exception('Token is required');
    }
}
