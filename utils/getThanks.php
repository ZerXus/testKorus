<?php

ini_set("display_errors", 1);
require_once "pdo.php";

const ROWS_PER_PAGE = 3;

/**
 * @param string $type "to" | "from"
 * @param array $args
 * @param bool $isCount
 * @return array|false
 */
function getThanks(string $type, array $args, bool $isCount = false): bool|array
{
    global $pdo;
    $thanksSQL = buildSQL($type, $args, $isCount);
    $thanks = $pdo->prepare($thanksSQL['sql']);
    $thanks->execute($thanksSQL['options']);

    $mode = PDO::FETCH_NUM;
    if ($isCount) {
        $mode = PDO::FETCH_COLUMN;
    }
    return $thanks->fetchAll($mode);
}

function buildSQL(string $type, array $args, bool $isCount)
{
    $date_from = $args['date_from'];
    $date_to = $args['date_to'];
    $department = $args['department'];
    $is_children = $args['is_children'];
    $offset = ($args['page'] - 1) * ROWS_PER_PAGE;

    $limit = ROWS_PER_PAGE;

    if ("thanksFrom" === $type) {
        $userType = 't.user_from_id';
    } elseif ("thanksTo" === $type) {
        $userType = 't.user_to_id';
    } else {
        die("Not correct type of query");
    }
    $options = [];

    $sql = "FROM user u
    LEFT JOIN thank t ON u.id = $userType
    LEFT JOIN user_department ud ON u.id = ud.user_id
    LEFT JOIN department d ON ud.department_id = d.id
    WHERE 1";
    if ($date_from !== null) {
        $options["date_from"] = $date_from;
        $sql .= " AND t.date >= :date_from";
    }

    if ($date_to !== null) {
        $options["date_to"] = $date_to;
        $sql .= " AND t.date <= :date_to";
    }

    if ($department !== null) {
        $options["department"] = $department;
        $sql .= " AND ((d.id = :department)";
    }

    if ($is_children !== null) {
        $options["is_children"] = $department;
        $sql .= " OR (d.parent = :department))";
    } else {
        $sql .= ')';
    }

    if ($isCount) {
        $sql = "SELECT COUNT(DISTINCT $userType)" . $sql;
    } else {
        $sql = "SELECT u.name, COUNT($userType) AS thanksCount " . $sql;

        $sql .= " GROUP BY u.name";
        $sql .= " ORDER BY thanksCount DESC";
        $sql .= " LIMIT $offset, $limit";
    }
    return [
        "sql" => $sql,
        'options' => $options
    ];
}


if (empty($_POST['type']) || ($_POST['type'] !== 'thanksFrom' && $_POST['type'] !== 'thanksTo')) {
    http_response_code(500);
    die("Thanks type incorrect or missing!");
}

$type = $_POST['type'];

$queryOptions = [
    'date_from' => $_POST["date_from"] ?? null,
    'date_to' => $_POST["date_to"] ?? null,
    'department' => $_POST["department"] ?? null,
    'is_children' => $_POST['is_children'] ?? null,
    'page' => $_POST['page'] ?? 1
];

if ($queryOptions['date_from'] > $queryOptions['date_to']) {
    die("Starting date must be lower than ending");
}

echo json_encode([
    "thanks" => getThanks($type, $queryOptions),
    "count" => getThanks($type, $queryOptions, true),
    "rowsPerPage" => ROWS_PER_PAGE
]);