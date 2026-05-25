//Replace ENG Archive Heading title & Label with IND 
function replaceText(selector, oldText, newText) {
    var elements = document.querySelectorAll(selector);
    for (var i = 0; i < elements.length; i++) {
        var elementText = elements[i].innerText;
        var newElementText = elementText.replace(oldText, newText);
        elements[i].innerText = newElementText;
    }
}

window.onload = function () {
    replaceText('.page-title', 'Sustainability Reports', 'Laporan Keberlanjutan');
    replaceText('.page-title', 'Public Disclosures', 'Informasi Publik');
    replaceText('.page-title', 'Press Releases', 'Siaran Pers');
    replaceText('.page-title', 'Financial Reports', 'Laporan Keuangan');
    replaceText('.page-title', 'Annual Reports', 'Laporan Tahunan');
    replaceText('.page-title', 'General Meetings of the Shareholders', 'Rapat Umum Pemegang Saham');
    replaceText('h5.uk-margin-remove', 'Select Year:', 'Pilih Tahun:');
};