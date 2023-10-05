window.jsPDF = window.jspdf.jsPDF;

function gap_html2pdf() {
  var element = document.getElementById('contentToPrint');
  var opt = {
    margin: 0.3,
    filename: 'hoa_don_chi_tiet_ban_hang.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2, scrollY: 0 },
    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  };
  html2pdf().set(opt).from(element).save();
  //html2pdf(element, opt);
  //html2pdf(element);
}

// Convert HTML content to PDF
function Convert_HTML_To_PDF() {
  var doc = new jsPDF();
  //const myFont = 'PN-Regular2-normal.js' // load the *.ttf font file as binary string

  // add the font to jsPDF
  /* doc.addFileToVFS("MyFont.ttf", myFont);
  doc.addFont("MyFont.ttf", "MyFont", "normal");
  doc.setFont("MyFont"); */
  //doc.setFont('PN-Regular2');
  //doc.setFont("Arial", "normal");
  //doc.setFontType('normal');

  // Source HTMLElement or a string containing HTML.
  var elementHTML = document.querySelector("#contentToPrint");

  doc.html(elementHTML, {
    callback: function (doc) {
      // Save the PDF
      doc.save('document-html.pdf');
    },
    margin: [10, 10, 10, 10],
    autoPaging: 'text',
    x: 0,
    y: 0,
    width: 190, //target width in the PDF document
    windowWidth: 675 //window width in CSS pixels
  });
}