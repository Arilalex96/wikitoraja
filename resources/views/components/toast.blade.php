function fireToast(text, color){
    if(color == 'success')
        color = "#198754";
    else if(color == 'danger')
        color = "#dc3545";
    else if(color == 'warning')
        color = "#ffc107";

    Toastify({
        text: text,
        duration: 3000,
        close: true,
        style: {
            background: color,
        },
        offset: {
            y: 20 // vertical axis - can be a number or a string indicating unity. eg: '2em'
        },
    }).showToast();
}