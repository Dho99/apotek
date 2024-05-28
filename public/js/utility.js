const formatRupiah = (number) => {
    const formatter = new Intl.NumberFormat('id-ID',{
        style: 'currency',
        currency: 'IDR'
    });
    return formatter.format(number);
}

const formatDate = (dateString) => {
    const formatter = new Intl.DateTimeFormat('id-ID', {
        dateStyle: 'full'
    });
    return formatter.format(new Date(dateString));
}
