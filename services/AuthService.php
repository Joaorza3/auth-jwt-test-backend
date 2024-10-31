<?php
class AuthService
{
    /**
     * @return UserModel[]
     */
    public function createUser(UserModel $data): array
    {
        $data->password = password_hash($data->password, PASSWORD_DEFAULT);

        $user_repository = new UserRepository();
        $user = $user_repository->create($data);

        return $user;
    }

    public function login(string $email, string $password): string
    {
        $user_repository = new UserRepository();
        $user = $user_repository->findByEmail($email);

        if (!$user) {
            throw new Exception('User not found');
        }

        if (!password_verify($password, $user->password)) {
            throw new Exception('Invalid password');
        }

        $jwt = new JWTService();
        return $jwt->generateToken($user);
    }

    public function validateToken(string $token): object
    {
        $jwt = new JWTService();
        $data = $jwt->validateToken($token);

        return $data;
    }

    public function logout(string $token): void
    {
        $jwt = new JWTService();
        $jwt->invalidateToken($token);
    }
}
