const filterRole   = document.getElementById('filterRole');
const rows         = document.querySelectorAll('#userTableBody tr[data-tipo]');
const totalVisible = document.getElementById('totalVisible');
const tipos = ["", "Esporte", "Natureza", "Automotivo", "Tecnologia"]
function filtrar() {
    
    const role   = filterRole.value;
  
    
    let visible  = 0;

    
    rows.forEach(row => {
     
        let tipo = row.dataset.tipo;
        
        const matchRole   = !role   || tipos.indexOf(tipo) == role;
        
        const show        =  matchRole;

        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

}

filterRole.addEventListener('change', filtrar);
