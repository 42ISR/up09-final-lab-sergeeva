<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // Автоматически очищает тестовую базу данных после каждого теста
    use RefreshDatabase;

    /**
     * Тест успешной регистрации пользователя.
     */
    public function test_user_can_register(): void
    {
        // 1. Отправляем данные для регистрации на корректный эндпоинт
        $response = $this->postJson('/api/auth/register', [
            'username' => 'testuser',
            'name' => 'Тестовый Пользователь',
            'email' => 'testcheck@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 2. Проверяем успешный статус (200 или 201)
        $response->assertSuccessful();

        // 3. Проверяем наличие пользователя в БД
        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'testcheck@example.com',
        ]);
    }

    /**
     * Тест успешного входа пользователя.
     */
    public function test_user_can_login(): void
    {
        // 1. Создаём тестового пользователя с хешированным паролем
        User::create([
            'username' => 'loginuser',
            'name' => 'Иван Иванов',
            'email' => 'logincheck@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 2. Отправляем POST-запрос на авторизацию (/api/auth/login)
        $response = $this->postJson('/api/auth/login', [
            'username' => 'loginuser',
            'password' => 'password123',
        ]);

        // 3. Проверяем, что вход прошёл успешно (код 200 OK)
        $response->assertSuccessful();
    }
}