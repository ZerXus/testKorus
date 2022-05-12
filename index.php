<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Test</title>
    <link rel="stylesheet" href="index.css">
    <script src="index.js"></script>
</head>
<body>
<div class="form-wrapper">
    <div class="form-header">
        <h1>Выберите параметры для вывода отчета</h1>
    </div>
    <div class="form">
        <form id="form">
            <div class="input-date-wrapper">
                <div class="input-date-label">
                    <span>Даты</span>
                </div>
                <div class="form-date-from">
                    <label>
                        От:
                        <input type="date" name="date_from">
                    </label>
                </div>
                <div class="form-date-to">
                    <label>
                        До:
                        <input type="date" name="date_to">
                    </label>
                </div>
            </div>
            <div class="input-department-wrapper">
                <label>
                    Департамент:
                    <select name="department" id="departmentSelect">
                    </select>
                </label>
            </div>
            <div class="input-department-wrapper">
                <label>
                    <input type="checkbox" name="is_children">
                    Включая его дочерние департаменты
                </label>
            </div>
            <div class="input-department-wrapper">
                <input type="submit">
            </div>

        </form>
    </div>
</div>
<div class="response-wrapper">
    <div class="response-header">
        <h1>Отчет</h1>
    </div>
    <div class="response-tables">
        <div class="table-wrapper" id="thanksFrom">
            <h3>Поблагодарившие пользователи</h3>
            <table>
                <thead>
                <tr>
                    <td>Имя</td>
                    <td>Количество благодарностей</td>
                </tr>
                </thead>
                <tbody id="thanksFromTBody"></tbody>
            </table>
            <div class="pagination-wrapper"></div>
        </div>
        <div class="table-wrapper"  id="thanksTo">
            <h3>Получившие благодарности</h3>
            <table>
                <thead>
                <tr>
                    <td>Имя</td>
                    <td>Количество благодарностей</td>
                </tr>
                </thead>
                <tbody id="thanksToTBody"></tbody>
            </table>
            <div class="pagination-wrapper"></div>
        </div>
    </div>

</div>

</body>
</html>
