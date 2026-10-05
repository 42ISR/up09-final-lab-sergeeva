<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Review;
use App\Models\Like;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('qwerty123'),
            'role' => 'admin',
            'bio' => 'Администратор платформы'
        ]);

        $moderator = User::create([
            'username' => 'moderator',
            'email' => 'moderator@example.com',
            'password' => Hash::make('qwerty123'),
            'role' => 'moderator',
            'bio' => 'Модератор контента'
        ]);

        $user = User::create([
            'username' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('qwerty123'),
            'role' => 'user',
            'bio' => 'Киноман'
        ]);

        $action = Genre::create(['name' => 'Боевик']);
        $drama = Genre::create(['name' => 'Драма']);
        $sciFi = Genre::create(['name' => 'Фантастика']);

        $movie1 = Movie::create([
            'title' => 'Начало',
            'director' => 'Кристофер Нолан',
            'year' => 2010,
            'country' => 'США',
            'description' => 'Проникновение в чужие сны с целью кражи тайн.',
            'posterUrl' => 'https://via.placeholder.com/300x450/3498db/ffffff?text=Inception',
            'createdBy' => $admin->id
        ]);
        $movie1->genres()->attach([$action->id, $sciFi->id]);

        $movie2 = Movie::create([
            'title' => 'Побег из Шоушенка',
            'director' => 'Фрэнк Дарабонт',
            'year' => 1994,
            'country' => 'США',
            'description' => 'История надежды и выживания в строгой тюрьме.',
            'posterUrl' => 'https://via.placeholder.com/300x450/e74c3c/ffffff?text=Shawshank',
            'createdBy' => $admin->id
        ]);
        $movie2->genres()->attach([$drama->id]);

        $movie3 = Movie::create([
            'title' => 'Интерстеллар',
            'director' => 'Кристофер Нолан',
            'year' => 2014,
            'country' => 'США',
            'description' => 'Путешествие сквозь червоточину в поиске нового дома.',
            'posterUrl' => 'https://via.placeholder.com/300x450/9b59b6/ffffff?text=Interstellar',
            'createdBy' => $moderator->id
        ]);
        $movie3->genres()->attach([$sciFi->id, $drama->id]);

        $movie4 = Movie::create([
            'title' => 'Темный рыцарь',
            'director' => 'Кристофер Нолан',
            'year' => 2008,
            'country' => 'США',
            'description' => 'Борьба Бэтмена против Джокера в Готэме.',
            'posterUrl' => 'https://via.placeholder.com/300x450/34495e/ffffff?text=Dark+Knight',
            'createdBy' => $user->id
        ]);
        $movie4->genres()->attach([$action->id, $drama->id]);

        $movie5 = Movie::create([
            'title' => 'Матрица',
            'director' => 'Вачовски',
            'year' => 1999,
            'country' => 'США',
            'description' => 'Реальность — это лишь симуляция искусственного интеллекта.',
            'posterUrl' => 'https://via.placeholder.com/300x450/2ecc71/ffffff?text=Matrix',
            'createdBy' => $user->id
        ]);
        $movie5->genres()->attach([$sciFi->id, $action->id]);

        $reviews = [
            ['movieId' => $movie1->id, 'userId' => $user->id, 'rating' => 10, 'title' => 'Гениально!', 'body' => 'Нолан снял абсолютный шедевр.', 'status' => 'approved'],
            ['movieId' => $movie2->id, 'userId' => $user->id, 'rating' => 9, 'title' => 'Классика', 'body' => 'Сильное кино о дружбе.', 'status' => 'approved'],
            ['movieId' => $movie3->id, 'userId' => $moderator->id, 'rating' => 10, 'title' => 'Завораживает', 'body' => 'Музыка Циммера просто невероятна.', 'status' => 'approved'],
            ['movieId' => $movie4->id, 'userId' => $user->id, 'rating' => 9, 'title' => 'Лучший Джокер', 'body' => 'Игра Хита Леджера поражает.', 'status' => 'approved'],
            ['movieId' => $movie5->id, 'userId' => $admin->id, 'rating' => 10, 'title' => 'Культовый фильм', 'body' => 'Изменил представление о фантастике.', 'status' => 'approved'],
        ];

        foreach ($reviews as $rev) {
            $r = Review::create($rev);
            Like::create(['reviewId' => $r->id, 'userId' => $admin->id]);
            Like::create(['reviewId' => $r->id, 'userId' => $user->id]);
        }
    }
}