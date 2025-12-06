function getMeta(metaName) {
    const metas = document.getElementsByTagName('meta');

    for (let i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute('name') === metaName) {
            return metas[i].getAttribute('content');
        }
    }

    return '';
}
function toTopPage() {
    window.scrollTo(0, 0);
}
function tolggeCollapse(id,toggle=true) {
    var myCollapse = document.getElementById(id)
    var bsCollapse = new bootstrap.Collapse(myCollapse, {
        toggle: toggle
    })
}

function getDate() {
    var dateObj = new Date();
    var month = ((dateObj.getUTCMonth() + 1) > 10) ? (dateObj.getUTCMonth() + 1) : '0' + (dateObj.getUTCMonth() + 1); //months from 1-12
    var day = dateObj.getUTCDate();
    var year = dateObj.getUTCFullYear();

    // "2022-05-25"
    return year + "-" + month + "-" + day;
}


function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}


export { getMeta, toTopPage ,tolggeCollapse, getDate, formatDate}