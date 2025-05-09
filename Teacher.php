<?php
class Teacher {
    private $surname;
    private $name;
    private $faculty;
    private $birthdate;
    private $salary;
    private $degree;
    private $position;

    public function __construct($surname, $name, $faculty, $birthdate, $salary, $degree, $position) {
        $this->surname = $surname;
        $this->name = $name;
        $this->faculty = $faculty;
        $this->birthdate = $birthdate;
        $this->salary = $salary;
        $this->degree = $degree;
        $this->position = $position;
    }

    public function save() {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO teachers (surname, name, faculty, birthdate, salary, degree, position) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $this->surname, 
            $this->name, 
            $this->faculty, 
            $this->birthdate, 
            $this->salary, 
            $this->degree, 
            $this->position
        ]);
    }

    public static function getByFaculty($faculty) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE faculty LIKE ?");
        $stmt->execute([$faculty]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByAttribute($attribute, $value) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE $attribute LIKE ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>