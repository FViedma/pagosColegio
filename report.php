<?php
include("php/dbconnect.php");
include("php/checklogin.php");

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sistema de Pago Escolar</title>

  <!-- BOOTSTRAP STYLES-->
  <link href="css/bootstrap.css" rel="stylesheet" />
  <!-- FONTAWESOME STYLES-->
  <link href="css/font-awesome.css" rel="stylesheet" />
  <!--CUSTOM BASIC STYLES-->
  <link href="css/basic.css" rel="stylesheet" />
  <!--CUSTOM MAIN STYLES-->
  <link href="css/custom.css" rel="stylesheet" />
  <!-- GOOGLE FONTS-->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

  <link href="css/ui.css" rel="stylesheet" />
  <link href="css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" />
  <link href="css/datepicker.css" rel="stylesheet" />
  <link href="css/datatable/datatable.css" rel="stylesheet" />

  <script src="js/jquery-1.10.2.js"></script>
  <script type='text/javascript' src='js/jquery/jquery-ui-1.10.1.custom.min.js'></script>
  <script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>

  <script src="js/dataTable/jquery.dataTables.min.js"></script>
  <!-- jsPDF library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
  <!-- jsPDF AutoTable plugin -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>


</head>
<?php
include("php/header.php");
?>
<div id="page-wrapper">
  <div id="page-inner">
    <div class="row">
      <div class="col-md-12">
        <h1 class="page-head-line">Reporte Pagos

        </h1>

      </div>
    </div>






    <div class="row" style="margin-bottom:20px;">
      <div class="col-md-12">
        <fieldset class="scheduler-border">
          <legend class="scheduler-border">Búsqueda:</legend>
          <form class="form-inline" role="form" id="searchform">
            <div class="form-group">
              <label for="email">Nombre</label>
              <input type="text" class="form-control" id="student" name="student">
            </div>

            <div class="form-group">
              <label for="email"> Fecha de Ingreso </label>
              <input type="text" class="form-control" id="doj" name="doj">
            </div>

            <div class="form-group">
              <label for="email"> Carrera </label>
              <select class="form-control" id="branch" name="branch">
                <option value="">Selecciona Carrera</option>
                <?php
                $sql = "select * from branch where delete_status='0' order by branch.branch asc";
                $q = $conn->query($sql);

                while ($r = $q->fetch_assoc()) {
                  echo '<option value="' . $r['id'] . '"  ' . (($branch == $r['id']) ? 'selected="selected"' : '') . '>' . $r['branch'] . '</option>';
                }
                ?>
              </select>
            </div>

            <button type="button" class="btn btn-success btn-sm" id="find"> Buscar </button>
            <button type="reset" class="btn btn-danger btn-sm" id="clear"> Limpiar </button>
          </form>
        </fieldset>

      </div>
    </div>

    <script type="text/javascript">
      $(document).ready(function() {

        /*
        $('#doj').datepicker( {
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
                dateFormat: 'mm/yy',
                onClose: function(dateText, inst) { 
                    $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                }
            });
        	1353c-p function 18cp 
        */

        /******************/
        $("#doj").datepicker({

          changeMonth: true,
          changeYear: true,
          showButtonPanel: true,
          dateFormat: 'mm/yy',
          onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('MM yy', new Date(year, month, 1)));
          }
        });

        $("#doj").focus(function() {
          $(".ui-datepicker-calendar").hide();
          $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
          });
        });

        /*****************/

        $('#student').autocomplete({
          source: function(request, response) {
            $.ajax({
              url: 'ajx.php',
              dataType: "json",
              data: {
                name_startsWith: request.term,
                type: 'report'
              },
              success: function(data) {

                response($.map(data, function(item) {

                  return {
                    label: item,
                    value: item
                  }
                }));
              }



            });
          }
          /*,
		      	autoFocus: true,
		      	minLength: 0,
                 select: function( event, ui ) {
						  var abc = ui.item.label.split("-");
						  //alert(abc[0]);
						   $("#student").val(abc[0]);
						   return false;

						  },
                 */



        });


        $('#find').click(function() {
          mydatatable();
        });


        $('#clear').click(function() {

          $('#searchform')[0].reset();
          mydatatable();
        });
        // document.getElementById('printPdf').addEventListener('click', () => {
        //   // Get the table.
        //   var table = document.getElementById('tSortable22');
        //   // Use jsPDF and autoTable to generate PDF.
        //   var doc = new jspdf.jsPDF('landscape');
        //   doc.autoTable({
        //     html: table,
        //     theme: 'grid',
        //     styles: {
        //       overflow: 'linebreak',
        //       fontSize: 8
        //     },
        //     tableWidth: 'auto',
        //   });

        //   // Save the PDF.
        //   doc.save('ReportePagos.pdf');
        // });

        document.getElementById('printPdf').addEventListener('click', () => {
          // Get the modal content.
          var modalContent = document.getElementById('formcontent').innerHTML;

          var data = [];
          var headers = [];
          var rows = table.rows;
          for (var i = 0; i < rows.length; i++) {
            var row = [];
            var cells = rows[i].cells;
            for (var j = 0; j < cells.length; j++) {
              if (i === 0) {
                headers.push(cells[j].innerText.trim());
              } else {
                row.push(cells[j].innerText.trim());
              }
            }
            if (i !== 0) {
              data.push(row);
            }
          }
          var tables = modalContent.match(/<table\b[^>]*>[\s\S]*?<\/table>/gi);
          
          var startY = 300;
          // Create a new jsPDF instance.
          var doc = new jspdf.jsPDF('p', 'pt', 'letter');
          
          tables.forEach(function(tableHtml, index) {
            var table = document.createElement('table');
            table.innerHTML = tableHtml;

            // Asignar valores de las variables del reporte con los datos de la tabla
            // Aquí se suponen estructuras de datos y nombres de variables
            // var studentName = data[0][0]; // Suponiendo que el primer dato de la primera fila es el nombre del estudiante
            // var studentID = data[0][1]; // Suponiendo que el segundo dato de la primera fila es el ID del estudiante
            // var amountReceived = parseFloat(data[1][0]); // Suponiendo que el primer dato de la segunda fila es el monto recibido (y que está en formato numérico)
            // var careerName = data[2][0]; // Suponiendo que el primer dato de la tercera fila es el nombre de la carrera
            // var installmentNumber = parseInt(data[3][0]); // Suponiendo que el primer dato de la cuarta fila es el número de cuota (y que está en formato numérico)
            // var account = data[4][0]; // Suponiendo que el primer dato de la quinta fila es la cuenta
            // var saldo = parseFloat(data[5][0]); // Suponiendo que el primer dato de la sexta fila es el saldo (y que está en formato numérico)
            // var totalAmount = parseFloat(data[6][0]); // Suponiendo que el primer dato de la séptima fila es el monto total (y que está en formato numérico)


            // Set font size and style for the document.
            doc.setFontSize(12);

            // Add title - Centered
            doc.setFillColor(255, 192, 203);
            doc.rect(40, 40, 525, 30, 'F');
            doc.setTextColor(0);
            doc.setFontSize(20);
            var titleWidth = doc.getStringUnitWidth('Comprobante de INGRESO') * 20; // Calculate title width
            var marginLeft = (doc.internal.pageSize.width - titleWidth) / 2; // Calculate left margin for centering
            doc.text('Comprobante de INGRESO', marginLeft, 60);

            // Add date of issuance
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = today.getFullYear();
            var formattedDate = dd + '-' + mm + '-' + yyyy; // Current date
            // Add date of issuance - Right aligned
            doc.text('Fecha de Emisión:', 425, 120);
            doc.text(formattedDate, 425, 140);

            // Add student name and ID card
            var studentName = "Juan Perez";
            var studentID = "123456789";
            doc.text('Recibí de: ' + studentName + '                   CI: ' + studentID, 50, 160);

            // Add amount received
            var amountReceived = 500;
            var amountInWords = convertNumberToWords(amountReceived);
            var amountReceivedText = 'La suma de: ';
            var amountReceivedNumber = '' + amountReceived;
            var amountInWordsSubtitle = 'Cantidad en letras: ';
            var amountInWordsText = '' + amountInWords;
            var maxTextWidth = Math.max(
              doc.getStringUnitWidth(amountReceivedText) * 12, // Assuming font size 12
              doc.getStringUnitWidth(amountReceivedNumber) * 12, // Assuming font size 12
              doc.getStringUnitWidth(amountInWordsSubtitle) * 12, // Assuming font size 12
              doc.getStringUnitWidth(amountInWordsText) * 12, // Assuming font size 12
            );
            doc.text(amountReceivedText, 50, 180);
            doc.text(amountInWordsText, 50 + maxTextWidth - doc.getStringUnitWidth(amountInWordsText) * 12, 200);

            // Add name of the career
            var careerName = "Programación";
            doc.text('Carrera: ' + careerName, 50, 220);

            // Add installment number
            var installmentNumber = 1;
            doc.text('Número de Cuota: ' + installmentNumber, 50, 240);

            // Add account - Right aligned
            var account = "1234567890";
            doc.text('A cuenta: ' + account, 425, 260);

            // Add saldo - Right aligned
            var saldo = 100;
            doc.text('Saldo: ' + saldo, 425, 280);

            // Add total amount - Right aligned
            var totalAmount = 1000;
            doc.text('Monto Total: ' + totalAmount, 425, 300);

            var pageHeight = doc.internal.pageSize.height;
            var tableHeight = (data.length + 1) * 20;
            if (startY + tableHeight > pageHeight) {
              doc.addPage();
              startY = 50;
            }
            startY = doc.autoTable.previous.finalY + 20;
            doc.save('ReportePagos.pdf');
          });


          // tables.forEach(function(tableHtml, index) {
          //   var table = document.createElement('table');
          //   table.innerHTML = tableHtml;

          //   var data = [];
          //   var headers = [];
          //   var rows = table.rows;
          //   for (var i = 0; i < rows.length; i++) {
          //     var row = [];
          //     var cells = rows[i].cells;
          //     for (var j = 0; j < cells.length; j++) {
          //       if (i === 0) {
          //         headers.push(cells[j].innerText.trim());
          //       } else {
          //         row.push(cells[j].innerText.trim());
          //       }
          //     }
          //     if (i !== 0) {
          //       data.push(row);
          //     }
          //   }

          //   var pageHeight = doc.internal.pageSize.height;
          //   var tableHeight = (data.length + 1) * 20;
          //   if (startY + tableHeight > pageHeight) {
          //     doc.addPage();
          //     startY = 50;
          //   }

          //   startY = doc.autoTable.previous.finalY + 20;
          // });



        });
        // Function to convert number to words
        function convertNumberToWords(number) {
          // Convert number to words logic
          return "Quinientos"; // Replace with actual conversion logic
        }

        function mydatatable() {

          $("#subjectresult").html('<table class="table table-striped table-bordered table-hover" id="tSortable22"><thead><tr><th>Name/Contact</th><th>Fees</th><th>Balance</th><th>Branch</th><th>DOJ</th><th>Action</th></tr></thead><tbody></tbody></table>');

          $("#tSortable22").dataTable({
            'sPaginationType': 'full_numbers',
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            'bProcessing': true,
            'bServerSide': true,
            'sAjaxSource': "datatable.php?" + $('#searchform').serialize() + "&type=report",
            'aoColumnDefs': [{
              'bSortable': false,
              'aTargets': [-1] /* 1st one, start by the right */
            }]
          });


        }

        ////////////////////////////
        $("#tSortable22").dataTable({

          'sPaginationType': 'full_numbers',
          "bLengthChange": false,
          "bFilter": false,
          "bInfo": false,

          'bProcessing': true,
          'bServerSide': true,
          'sAjaxSource': "datatable.php?type=report",

          'aoColumnDefs': [{
            'bSortable': false,
            'aTargets': [-1] /* 1st one, start by the right */
          }]
        });

        ///////////////////////////		



      });


      function GetFeeForm(sid) {

        $.ajax({
          type: 'post',
          url: 'getfeeform.php',
          data: {
            student: sid,
            req: '2'
          },
          success: function(data) {
            $('#formcontent').html(data);
            $("#myModal").modal({
              backdrop: "static"
            });
          }
        });


      }
    </script>




    <style>
      #doj .ui-datepicker-calendar {
        display: none;
      }
    </style>

    <div class="panel panel-default">
      <div class="panel-heading">
        Gestionar Reporte de Pagos
      </div>
      <div class="panel-body">
        <div class="table-sorting table-responsive" id="subjectresult">
          <table class="table table-striped table-bordered table-hover" id="tSortable22">
            <thead>
              <tr>

                <th>Nombre</th>
                <th>Pagos</th>
                <th>Balance</th>
                <th>Carrera</th>
                <th>Fecha de Ingreso</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>


    <!-------->

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Reporte de Pagos</h4>
          </div>
          <div class="modal-body" id="formcontent">

          </div>
          <div class="modal-footer">
            <button id="printPdf" class="btn btn-primary">Imprimir PDF</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>


    <!--------->


  </div>
  <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
</div>
<!-- /. WRAPPER  -->

<div id="footer-sec">
  Para más desarrollos gratuitos, accede a <a href="https://www.configuroweb.com/" target="_blank">ConfiguroWeb</a>
</div>


<!-- BOOTSTRAP SCRIPTS -->
<script src="js/bootstrap.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="js/custom1.js"></script>


</body>

</html>