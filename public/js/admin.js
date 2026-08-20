const searchInput  = document.getElementById('searchInput');
const filterRole   = document.getElementById('filterRole');
const filterStatus = document.getElementById('filterStatus');
const rows         = document.querySelectorAll('#userTableBody tr[data-nome]');
const totalVisible = document.getElementById('totalVisible');

function filtrar() {
    const search = searchInput.value.toLowerCase().trim();
    const role   = filterRole.value;
    const status = filterStatus.value;
    let visible  = 0;
    
    rows.forEach(row => {
        
        const matchSearch = !search || row.dataset.nome.includes(search) || row.dataset.email.includes(search);
        const matchRole   = !role   || row.dataset.role   === role;
        const matchStatus = !status || row.dataset.status === status;
        const show        = matchSearch && matchRole && matchStatus;

        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    totalVisible.textContent = `${visible} usuário(s)`;
}

searchInput.addEventListener('input', filtrar);
filterRole.addEventListener('change', filtrar);
filterStatus.addEventListener('change', filtrar);
