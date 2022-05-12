let urls = {
    getThanks: "/testKorus/utils/getThanks.php",
    getDepartments: "/testKorus/utils/getDepartments.php"
}

window.onload = () => {
    setDates();
    getDepartments().then(() => console.log("Departments loaded!"));
    form.onsubmit = onSubmitHandler
}

const onSubmitHandler = async event => {
    event.preventDefault();

    await showResults('thanksFrom');
    await showResults('thanksTo');
}

const showResults = async (table, page = "1") => {
    let formData = new FormData(form);
    formData.append('type', table);
    formData.append('page', page.toString());

    let result = await fetchJSON(urls.getThanks, formData);

    let tableBody = document.querySelector(`#${table} tbody`);
    tableBody.innerHTML = '';

    result.thanks.forEach(thank => makeTableRow(tableBody, thank));

    makePagination(table, result.count, result.rowsPerPage, page);
}

const makeTableRow = (tableBody, cellsData) => {
    let row = document.createElement('tr');

    cellsData.forEach(data => {
        let cell = row.insertCell();
        cell.append(data);
    })
    tableBody.append(row);
}

const makePagination = (table, rowsCount, rowsPerPage, currentPage) => {
    let pagination = document.querySelector(`#${table} .pagination-wrapper`);
    pagination.innerHTML = '';
    let pagesCount = getPagesCount(rowsCount, rowsPerPage);

    for (let i = 1; i <= pagesCount; i++) {
        let nextPage = i.toString();
        let paginationLink = makePaginationLink(table, currentPage, nextPage)
        pagination.append(paginationLink);
    }
}

const makePaginationLink = (table, currentPage, nextPage) => {
    let paginationLinkWrapper = document.createElement('div');
    paginationLinkWrapper.classList.add('pagination-link-wrapper');

    let paginationLink = document.createElement('a');
    paginationLink.innerHTML = nextPage;

    if (currentPage !== nextPage) {
        paginationLink.setAttribute('href', '#');

        paginationLink.onclick = async event => {
            event.preventDefault();
            await showResults(table, nextPage);
        }
    }

    paginationLinkWrapper.append(paginationLink);
    return paginationLinkWrapper;
}

const getPagesCount = (rowsCount, rowsPerPage) => {
    let pagesCount = Math.floor(rowsCount / rowsPerPage);
    return rowsCount % rowsPerPage !== 0 ? pagesCount++ : pagesCount;
}

const getDepartments = async () => {
    let departments = await fetchJSON(urls.getDepartments);

    departments.forEach((department) => {
        const option = document.createElement('option');
        option.textContent = department.name;
        option.value = department.id;

        departmentSelect.appendChild(option);
    })
}

const setDates = () => {
    let today = new Date();
    let lastDayDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    let lastDay = new Intl.DateTimeFormat('en', {day: "2-digit"}).format(lastDayDate);
    let year = new Intl.DateTimeFormat('en', {year: 'numeric'}).format(today);
    let month = new Intl.DateTimeFormat('en', {month: '2-digit'}).format(today);

    if (form['date_from'].value.length <= 0) {
        form['date_from'].value = `${year}-${month}-01`;
    }
    if (form['date_to'].value.length <= 0) {
        form['date_to'].value = `${year}-${month}-${lastDay}`;
    }
}

const fetchJSON = async (url, body = null) => {
    let init = {
        method: "post"
    }
    if (body !== null) init.body = body;
    let response = await fetch(url, init);
    if (!response.ok) {
        console.log(await response.json());
        return false;
    }
    return await response.json();
}
