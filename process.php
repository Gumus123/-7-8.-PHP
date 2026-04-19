<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверяем, была ли отправлена форма методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Получаем и очищаем данные
    $login = isset($_POST['login']) ? trim($_POST['login']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $remember = isset($_POST['remember']) ? true : false;

    $errors = [];

    // Проверка логина
    if (empty($login)) {
        $errors[] = "Логин не может быть пустым";
    } elseif (strlen($login) < 3) {
        $errors[] = "Логин должен содержать минимум 3 символа";
    } elseif (strlen($login) > 20) {
        $errors[] = "Логин не должен превышать 20 символов";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
        $errors[] = "Логин может содержать только буквы, цифры и символ подчеркивания";
    }

    // Проверка пароля
    if (empty($password)) {
        $errors[] = "Пароль не может быть пустым";
    } elseif (strlen($password) < 6) {
        $errors[] = "Пароль должен содержать минимум 6 символов";
    }

    // Если есть ошибки — возвращаем на форму
    if (!empty($errors)) {
        $error_string = urlencode(implode("; ", $errors));
        header("Location: index.php?error=" . $error_string);
        exit();
    }

    // ✅ Если ошибок нет — проверяем "учётные данные"
    // (в учебных целях логин/пароль жёстко заданы)
    $valid_login = "student";
    $valid_password = "password123";

    if ($login === $valid_login && $password === $valid_password) {
        // Успешная авторизация
        $success_message = "Добро пожаловать, $login!";
        if ($remember) {
            $success_message .= " Вы выбрали 'Запомнить меня'.";
        }
        header("Location: index.php?success=" . urlencode($success_message));
        exit();
    } else {
        // Неверные учётные данные
        $errors[] = "Неверный логин или пароль";
        $error_string = urlencode(implode("; ", $errors));
        header("Location: index.php?error=" . $error_string);
        exit();
    }
} else {
    // Не POST-запрос
    header("Location: index.php?error=" . urlencode("Неверный метод запроса"));
    exit();
}
?>