$.nette.init();

var qr = $('#qr');
qr.qrcode({
    size: 150,
    ecLevel: 'M',
    text: 'SPD*1.0*ACC:' + qr.data('iban') + '+' + qr.data('bic') +
    '*AM:' + qr.data('total') +
    '*CC:CZK' +
    '*MSG:PLATBA FAKTURY ' + qr.data('invoiceno') +
    '*X-VS:' + qr.data('vs')
    //see: http://qr-platba.cz/pro-vyvojare/specifikace-formatu/
});

$('.js-add-invoice-column').on('click', function () {
    var original = document.getElementById('duplicatable');
    var clone = original.cloneNode(true);
    clone.id = '';
    original.parentNode.insertBefore(clone, original.nextSibling);
});
