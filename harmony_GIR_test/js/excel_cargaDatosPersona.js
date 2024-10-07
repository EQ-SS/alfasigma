let selectedFile;
console.log(window.XLSX);
document.getElementById("formFile").addEventListener("change", (event) => {
  selectedFile = event.target.files[0];
});

let data = [
  {
    name: "jayanth",
    data: "scd",
    abc: "sdef",
  },
];

document.getElementById("btnValidaCargaPersona").addEventListener("click", () => {


  setTimeout(function(){

    if (selectedFile == undefined) {
      alert("Seleccione un Archivo");
    } else {
      XLSX.utils.json_to_sheet(data, "out.xlsx");
      if (selectedFile) {
        let fileReader = new FileReader();
        fileReader.readAsBinaryString(selectedFile);
        fileReader.onload = (event) => {
          let data = event.target.result;
          let workbook = XLSX.read(data, { type: "binary" });
          //console.log(workbook);
          workbook.SheetNames.forEach((sheet) => {
            let rowObject = XLSX.utils.sheet_to_row_object_array(
              workbook.Sheets[sheet]
            );
            $("#divRespuesta").load("ajax/cargaExcelPersonas.php", {
              rowObject: rowObject,
            });
  
            
          });
        };
      }
    }
   
}, 1000);
 


});
