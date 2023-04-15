
// formats the date to Y-m-d format.
export const formatDate = (selectedDate) => {
    const date = new Date(selectedDate);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}
export const formatTimeStamp = (timestamp) => {
    let date = new Date(timestamp * 1000);
    return date.toLocaleDateString('en-gb',
        {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        })
}
