export const formattedDate = (dateString) => {
    if(!dateString) return;
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    formattedDate.value = new Intl.DateTimeFormat('en-US', options).format(date);
    return formattedDate.value;
}

export const generateSlug = (movie, id, type='movie') => {
    let normalizedStr = movie.normalize('NFD').replace(/[\u0300-\u036f]/g, '');


    let slug = normalizedStr
        .toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');

    return siteData.siteBase+type+'/'+id+'-'+slug;
}