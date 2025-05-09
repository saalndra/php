<?php
require_once 'db.php';

// Функція для отримання всіх факультетів
function getFaculties() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM faculties");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функція для отримання всіх посад
function getPositions() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM positions");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функція для отримання всіх наукових ступенів
function getDegrees() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM degrees");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функція для отримання всіх викладачів з бази даних
function getTeachers($searchTerm = '') {
    global $pdo;
    $searchTerm = "%$searchTerm%";
    $stmt = $pdo->prepare("SELECT t.id, t.surname, t.name, f.name AS faculty, t.birthdate, t.salary, d.name AS degree, p.name AS position
                         FROM teachers t
                         JOIN faculties f ON t.faculty_id = f.id
                         JOIN degrees d ON t.degree_id = d.id
                         JOIN positions p ON t.position_id = p.id
                         WHERE t.surname LIKE ?");
    $stmt->execute([$searchTerm]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функція для додавання викладача
function addTeacher($surname, $name, $faculty_id, $birthdate, $salary, $degree_id, $position_id) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO teachers (surname, name, faculty_id, birthdate, salary, degree_id, position_id) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$surname, $name, $faculty_id, $birthdate, $salary, $degree_id, $position_id]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["surname"])) {
    $surname = trim($_POST["surname"]);
    $name = trim($_POST["name"]);
    $faculty_id = trim($_POST["faculty"]);
    $birthdate = trim($_POST["birthdate"]);
    $salary = trim($_POST["salary"]);
    $degree_id = trim($_POST["degree"]);
    $position_id = trim($_POST["position"]);

    if ($surname && $name && $faculty_id && $birthdate && $salary && $degree_id && $position_id) {
        addTeacher($surname, $name, $faculty_id, $birthdate, $salary, $degree_id, $position_id);
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    } else {
        echo "<p class='error'>Будь ласка, заповніть всі поля!</p>";
    }
}

// Пошук викладачів
$searchTerm = $_GET["search"] ?? "";
$teachers = getTeachers($searchTerm);
$faculties = getFaculties();
$positions = getPositions();
$degrees = getDegrees();
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
        input, button, select {
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
            <?php foreach ($teachers as $teacher): ?>
                <tr>
                    <td><?= htmlspecialchars($teacher['surname']) ?></td>
                    <td><?= htmlspecialchars($teacher['name']) ?></td>
                    <td><?= htmlspecialchars($teacher['faculty']) ?></td>
                    <td><?= htmlspecialchars($teacher['birthdate']) ?></td>
                    <td><?= htmlspecialchars($teacher['salary']) ?></td>
                    <td><?= htmlspecialchars($teacher['degree']) ?></td>
                    <td><?= htmlspecialchars($teacher['position']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <h2>Додати викладача</h2>
        <form method="post">
            <input type="text" name="surname" placeholder="Прізвище" required>
            <input type="text" name="name" placeholder="Ім'я" required>
            <select name="faculty" required>
                <option value="">Оберіть факультет</option>
                <?php foreach ($faculties as $faculty): ?>
                    <option value="<?= $faculty['id'] ?>"><?= htmlspecialchars($faculty['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="birthdate" required>
            <input type="number" name="salary" placeholder="Зарплата" required>
            <select name="degree" required>
                <option value="">Оберіть науковий ступінь</option>
                <?php foreach ($degrees as $degree): ?>
                    <option value="<?= $degree['id'] ?>"><?= htmlspecialchars($degree['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="position" required>
                <option value="">Оберіть посаду</option>
                <?php foreach ($positions as $position): ?>
                    <option value="<?= $position['id'] ?>"><?= htmlspecialchars($position['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Додати</button>
        </form>
    </div>
</body>
</html>