<?php
$host = '127.0.0.1';
$dbname = 'api_db';
if (isset($_SESSION['bd'])) {
    $dbname = $_SESSION['bd'];
}
$user = 'dimon';
$pass = '1234567';
$charset = 'utf8';

class DB

{
    public $pdo;

    public function __construct($db, $username, $password, $host, $options = [])
    {
        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        $options = array_replace($default_options, $options);
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function run($sql, $args = NULL)
    {
        if (!$args) {
            return $this->pdo->query($sql);
        }
        $s = $this->pdo->prepare($sql);
        $s->execute($args);
        return $s;
    }

//печатает всю таблицу с названиями столбцов -> вывод двумерного массива

    public function view($rows, $style = '')
    {
        echo '<table class="' . $style . '"><thead><tr>';
        foreach ($rows[0] as $columnName => $value) :
            echo '<th>' . $columnName . '</th>';
        endforeach;
        echo '</tr></thead>';
        foreach ($rows as $row) :
            echo '<tr>';
            foreach ($row as $value) {
                echo '<td>' . $value . '</td>';
            }
            echo '</tr>';
        endforeach;
        echo '</table>';
    }

//Печатаем новые заголовки в таблице из заданного масива

    public function asView($rows, $as, $style = '')
    {
        echo '<table class="' . $style . '"><thead><tr>';
        $s = array_combine($as, $rows[0]);
        foreach ($s as $columnName => $value) :

            echo '<th>' . $columnName . '</th>';
        endforeach;
        echo '</tr></thead>';
        foreach ($rows as $row) :
            echo '<tr>';
            foreach ($row as $value) {
                echo '<td>' . $value . '</td>';
            }
            echo '</tr>';
        endforeach;
        echo '</table>';
    }

//меняем заголовки столбцов

    public function columnRename($res, $columnAs)
    {
        if (is_array($columnAs) && is_array($res)) {
            $i = 0;
            foreach ($res as $k) {

                $s[$i] = array_combine($columnAs, $res[$i]);
                $i++;
            }
            return $s;
        }
    }

}