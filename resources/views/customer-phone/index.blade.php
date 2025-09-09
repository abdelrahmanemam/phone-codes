<!DOCTYPE html>
<html>
<head>
    <title>Phone Numbers SPA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        select { padding: 5px; margin-right: 10px; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>

<h1>Phone Numbers</h1>

<div>
    <label>Country:</label>
    <select id="country">
        <option value="">All</option>
    </select>

    <label>State:</label>
    <select id="state">
        <option value="">All</option>
        <option value="valid">Valid</option>
        <option value="invalid">Invalid</option>
    </select>

    <button onclick="loadData()">Filter</button>
</div>

<table id="phones">
    <thead>
    <tr>
        <th>Country</th>
        <th>State</th>
        <th>Country Code</th>
        <th>Phone Number</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

<div id="pagination"></div>

<script>
    let currentPage = 1;
    let lastPage = 1;

    function loadCountries() {
        return fetch('/api/customer-phone')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('country');

                // Convert object to array
                const countriesArray = Object.values(data.meta.countries);

                countriesArray.forEach(c => {
                    const option = document.createElement('option');
                    option.value = '+' + c.code; // match backend country_code
                    option.textContent = `${c.name} (${c.code})`;
                    select.appendChild(option);
                });
            });
    }

    function loadData(page = 1) {
        currentPage = page;

        const country = document.getElementById('country').value;
        const state = document.getElementById('state').value;

        let url = `/api/customer-phone?page=${page}`;
        if (country) url += `&country=${country}`;
        if (state) url += `&state=${state}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#phones tbody');
                tbody.innerHTML = '';

                data.data.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${row.country || '-'}</td>
                    <td>${row.state}</td>
                    <td>${row.country_code || '-'}</td>
                    <td>${row.number || '-'}</td>
                `;
                    tbody.appendChild(tr);
                });

                // Render pagination
                renderPagination(data.meta);
            });
    }

    function renderPagination(meta) {
        const container = document.getElementById('pagination');
        container.innerHTML = '';

        lastPage = Math.ceil(meta.total / meta.per_page);

        // Previous button
        const prev = document.createElement('button');
        prev.textContent = 'Prev';
        prev.disabled = currentPage <= 1;
        prev.onclick = () => loadData(currentPage - 1);
        container.appendChild(prev);

        // Page numbers
        for (let i = 1; i <= lastPage; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.disabled = i === currentPage;
            btn.onclick = () => loadData(i);
            container.appendChild(btn);
        }

        // Next button
        const next = document.createElement('button');
        next.textContent = 'Next';
        next.disabled = currentPage >= lastPage;
        next.onclick = () => loadData(currentPage + 1);
        container.appendChild(next);
    }

    // Filter button
    document.querySelector('button[onclick="loadData()"]').addEventListener('click', () => loadData(1));

    // Initialize
    loadCountries();
    loadData();
</script>

</body>
</html>
