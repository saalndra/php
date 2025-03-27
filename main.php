<?php

$filename = "teachers.txt";

function readTeachers($filename) {
    if (!file_exists($filename)) return [];
    $data = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_map(fn($line) => explode("|", $line), $data);
}

function addTeacher($filename, $teacherData) {
    $line = implode("|", $teacherData) . "\n";
    file_put_contents($filename, $line, FILE_APPEND | LOCK_EX);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["surname"])) {
    $surname = trim($_POST["surname"]);
    $name = trim($_POST["name"]);
    $faculty = trim($_POST["faculty"]);
    $birthdate = trim($_POST["birthdate"]);
    $salary = trim($_POST["salary"]);
    $degree = trim($_POST["degree"]);
    $position = trim($_POST["position"]);
    
    if ($surname && $name && $faculty && $birthdate && $salary && $degree && $position) {
        addTeacher($filename, [$surname, $name, $faculty, $birthdate, $salary, $degree, $position]);
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    } else {
        echo "<p class='error'>Будь ласка, заповніть всі поля!</p>";
    }
}

$teachers = readTeachers($filename);

// Сортуємо викладачів за зарплатою у зростаючому порядку
usort($teachers, fn($a, $b) => $a[4] - $b[4]);

// Обчислюємо кількість доцентів на факультеті ФПМ
$docentCount = count(array_filter($teachers, fn($t) => $t[2] === "ФПМ" && $t[6] === "доцент"));

// Фільтрація викладачів за введеними символами у прізвищі
$searchTerm = $_GET["search"] ?? "";
$filteredTeachers = array_filter($teachers, fn($t) => stripos($t[0], $searchTerm) !== false);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Викладачі університету</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input, button {
            padding: 10px;
            margin: 5px;
            width: 90%;
            max-width: 400px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Пошук викладачів за прізвищем</h2>
        <form method="get">
            <input type="text" name="search" placeholder="Введіть символи для пошуку" value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit">Шукати</button>
        </form>
        
        <h2>Список викладачів</h2>
        <table>
            <tr>
                <th>Прізвище</th>
                <th>Ім'я</th>
                <th>Факультет</th>
                <th>Дата народження</th>
                <th>Зарплата</th>
                <th>Науковий ступінь</th>
                <th>Посада</th>
            </tr>
            <?php foreach ($filteredTeachers as $teacher): ?>
                <tr>
                    <td><?= htmlspecialchars($teacher[0]) ?></td>
                    <td><?= htmlspecialchars($teacher[1]) ?></td>
                    <td><?= htmlspecialchars($teacher[2]) ?></td>
                    <td><?= htmlspecialchars($teacher[3]) ?></td>
                    <td><?= htmlspecialchars($teacher[4]) ?></td>
                    <td><?= htmlspecialchars($teacher[5]) ?></td>
                    <td><?= htmlspecialchars($teacher[6]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <h3>Кількість доцентів на ФПМ: <?= $docentCount ?></h3>
        
        <h2>Додати викладача</h2>
        <form method="post">
            <input type="text" name="surname" placeholder="Прізвище" required>
            <input type="text" name="name" placeholder="Ім'я" required>
            <input type="text" name="faculty" placeholder="Факультет" required>
            <input type="date" name="birthdate" required>
            <input type="number" name="salary" placeholder="Зарплата" required>
            <input type="text" name="degree" placeholder="Науковий ступінь" required>
            <input type="text" name="position" placeholder="Посада" required>
            <button type="submit">Додати</button>
        </form>
    </div>
</body>
</html>
