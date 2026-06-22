
const btnGenerate = document.querySelector("#generate-pdf");

btnGenerate.addEventListener("click", () => {

    //FUNCAO PARA CONTEUDO PDF
    const content = document.querySelector("#contentpdf")

    //CONFIGURACAO DA PAGINA DE IMPRESSAO

    const option = {
        margin: [5, 5, 5, 5],
        filename: "RELATORIODEIMPRESSAO.pdf",
        html2canvas: { scale: 5 },
        jsPDF: { unit: "mm", format: "a4", orientation: "portrait" }
    }

    //GERAR E BAIXAR PDF
    html2pdf().set(option).from(content).save();
});


const btnGenerate2 = document.querySelector("#generate-pdf2");

btnGenerate2.addEventListener("click", () => {

    //FUNCAO PARA CONTEUDO PDF
    const content = document.querySelector("#contentpdf")

    //CONFIGURACAO DA PAGINA DE IMPRESSAO

    const option = {
        margin: [5, 5, 5, 5],
        filename: "RELATORIODEIMPRESSAO.pdf",
        html2canvas: { scale: 5 },
        jsPDF: { unit: "mm", format: "a4", orientation: "portrait" }
    }

    //GERAR E BAIXAR PDF
    html2pdf().set(option).from(content).save();
});


    



