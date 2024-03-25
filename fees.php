<?php
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';

if (isset($_POST['save'])) {
  $paid = mysqli_real_escape_string($conn, $_POST['paid']);
  $submitdate = mysqli_real_escape_string($conn, $_POST['submitdate']);
  $transcation_remark = mysqli_real_escape_string($conn, $_POST['transaction_remark']);
  $sid = mysqli_real_escape_string($conn, $_POST['sid']);

  $sql = "select fees,balance  from student where id = '$sid'";
  $sq = $conn->query($sql);
  $sr = $sq->fetch_assoc();
  $totalfee = $sr['fees'];
  if ($sr['balance'] > 0) {
    $sql = "insert into fees_transaction(stdid,submitdate,transcation_remark,paid) values('$sid','$submitdate','$transcation_remark','$paid') ";
    $conn->query($sql);
    $sql = "SELECT sum(paid) as totalpaid FROM fees_transaction WHERE stdid = '$sid'";
    $tpq = $conn->query($sql);
    $tpr = $tpq->fetch_assoc();
    $totalpaid = $tpr['totalpaid'];
    $tbalance = $totalfee - $totalpaid;

    $sql = "update student set balance='$tbalance' where id = '$sid'";
    $conn->query($sql);

    echo '<script type="text/javascript">window.location="fees.php?act=1";</script>';
  }
} else {
  error_log('Error en la transaccion en base de datos');
}

if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "1") {
  $errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Pago realizado exitósamente</div>";
} else {
  error_log("error");
}

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js" async></script>
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
        <h1 class="page-head-line">Pagos

        </h1>

      </div>
    </div>



    <?php
    echo $errormsg;
    ?>



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
              <label for="email"> Sucursales </label>
              <select class="form-control" id="branch" name="branch">
                <option value="">Selecciona Sucursal</option>
                <?php
                $branch = '';
                $sql = "select * from branch where delete_status='0' order by branch.branch asc";
                $q = $conn->query($sql);

                while ($r = $q->fetch_assoc()) {
                  echo '<option value="' . $r['id'] . '"  ' . (($branch == $r['id']) ? 'selected="selected"' : '') . '>' . $r['branch'] . '</option>';
                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <label for="email"> Carreras </label>
              <select class="form-control" id="career" name="career">
                <option value="">Selecciona Carrera</option>
                <?php
                $career = '';
                $sql = "select * from career where delete_status='0' order by career.career asc";
                $q = $conn->query($sql);

                while ($r = $q->fetch_assoc()) {
                  echo '<option value="' . $r['id'] . '"  ' . (($career == $r['id']) ? 'selected="selected"' : '') . '>' . $r['career'] . '</option>';
                }
                ?>
              </select>
            </div>

            <button type="button" class="btn btn-success btn-sm" id="find"> Búsqueda </button>
            <button type="reset" class="btn btn-danger btn-sm" id="clear"> Limpiar </button>
          </form>
        </fieldset>

      </div>
    </div>

    <script type="text/javascript">
      var careerValue;
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
                type: 'studentname'
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

        function mydatatable() {

          $("#subjectresult").html('<table class="table table-striped table-bordered table-hover" id="tSortable22"><thead><tr><th>Nombre</th><th>Pagos</th><th>Balance</th><th>Sucursal</th><th>Carrera</th><th>Fecha Ingreso</th><th>Action</th></tr></thead><tbody></tbody></table>');

          $("#tSortable22").dataTable({
            'sPaginationType': 'full_numbers',
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            'bProcessing': true,
            'bServerSide': true,
            'sAjaxSource': "datatable.php?" + $('#searchform').serialize() + "&type=feesearch",
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
          'sAjaxSource': "datatable.php?type=feesearch",

          'aoColumnDefs': [{
            'bSortable': false,
            'aTargets': [-1] /* 1st one, start by the right */
          }]
        });

        $('#myModal').on('shown.bs.modal', function() {
          $('#signupForm1').on('submit', function(e) {
            e.preventDefault();
            var studentName = $('#name').val();
            var pagado = $('#paid').val();
            var saldo = $('#balance').val();
            var branch = $('#branchValue').val();
            var career = $('#careerValue').val();
            var studentID = $('#studentId').val();
            var studentCi = $('#ci').val();
            obtenerNumeroPagosEstudiante(studentID, function(numPagos) {

              var doc = new jspdf.jsPDF('p', 'pt', 'letter');

              // Set font size and style for the document.
              doc.setFontSize(16);

              // Add title - Centered
              doc.setFillColor(255, 192, 203);
              doc.rect(40, 40, 525, 30, 'FD'); // 'FD' indicates fill and draw
              doc.setTextColor(0);
              var titleWidth = doc.getStringUnitWidth('Comprobante de INGRESO') * 20; // Calculate title width
              var marginLeft = (doc.internal.pageSize.width - titleWidth) / 2; // Calculate left margin for centering
              doc.text('Comprobante de INGRESO', marginLeft, 60);
              
              doc.setFontSize(12);
              doc.text('Codigo', 425, 90);
              // Add date of issuance
              var today = new Date();
              var dd = String(today.getDate()).padStart(2, '0');
              var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
              var yyyy = today.getFullYear().toString();
              doc.text('Fecha de Emisión:', 425, 120);

              var posX = 425;
              var posY = 125;
              var boxWidth = 20;
              var boxHeight = 20;
              var gap = 5;

              doc.rect(posX, posY, boxWidth + 1, boxHeight);
              doc.text(dd, posX + (boxWidth / 2) - 5, posY + (boxHeight / 2) + 3);

              // Draw month box
              doc.rect(posX + (boxWidth + gap), posY, boxWidth + 1, boxHeight);
              doc.text(mm, posX + (boxWidth + gap) + (boxWidth / 2) - 4, posY + (boxHeight / 2) + 3);

              // Draw year boxes
              doc.rect(posX + 2 * (boxWidth + gap), posY, boxWidth + 15, boxHeight);
              doc.text(yyyy, posX + 2 * (boxWidth + gap) + (boxWidth / 2) - 4, posY + (boxHeight / 2) + 3);


              // Add student name and ID card
              doc.text('RECIBI DE:', 50, 160);
              doc.text('CI: ' + studentCi, 425, 160);
              doc.text(studentName.toUpperCase(), 60, 172);

              var amountReceivedText = 'LA SUMA DE: ';
              var amountReceivedNumber = '' + pagado;
              var amountInWordsSubtitle = 'Cantidad en letras: ';
              var amountInWordsText = numeroEnLetras(pagado);
              var maxTextWidth = Math.max(
                doc.getStringUnitWidth(amountReceivedText) * 12, // Assuming font size 12
                doc.getStringUnitWidth(amountReceivedNumber) * 12, // Assuming font size 12
                doc.getStringUnitWidth(amountInWordsSubtitle) * 12, // Assuming font size 12
                doc.getStringUnitWidth(amountInWordsText) * 12, // Assuming font size 12
              );
              doc.text(amountReceivedText, 50, 193);
              doc.text(amountInWordsText + " BOLIVIANOS", 50, 213);

              var contentWidth = 525; // Width of the content area
              var contentHeight = 80; // Height of the content area
              var borderWidth = 1; // Border width
              var startX = 40; // Starting X position of the border
              var startY = 150; // Starting Y position of the border
              doc.rect(startX, startY, contentWidth, contentHeight, 'S');
              // Draw border around the content
              contentWidth = 505; // Width of the content area
              contentHeight = 20; // Height of the content area
              borderWidth = 1; // Border width
              startX = 45; // Starting X position of the border
              startY = 161; // Starting Y position of the border
              doc.rect(startX, startY, contentWidth, contentHeight, 'S');
              // Draw border around the content
              contentWidth = 505; // Width of the content area
              contentHeight = 20; // Height of the content area
              borderWidth = 1; // Border width
              startX = 45; // Starting X position of the border
              startY = 201; // Starting Y position of the border
              doc.rect(startX, startY, contentWidth, contentHeight, 'S');

              doc.text('Sucursal: ' + branch.toUpperCase(), 395, 250);

              doc.text('Carrera: ' + career.toUpperCase(), 395, 270);

              doc.text('CUOTA: ' + numPagos, 50, 250);

              doc.text('A cuenta: ' + pagado, 395, 280);

              doc.text('Saldo: ' + saldo, 395, 300);

              doc.text('Total: ' + pagado, 395, 320);

              var pageHeight = doc.internal.pageSize.height;

              doc.save("comprobante.pdf");

              // Create a hidden input field to hold the 'save' value
              var saveInput = $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'save')
                .val('save');

              // Append the hidden input field to the form
              $(this).append(saveInput);

              // Submit the form

              this.submit();
            });

          });
        });

      });

      function obtenerNumeroPagosEstudiante(studentID, callback) {
        $.ajax({
          url: 'getNumFees.php',
          method: 'POST',
          data: {
            studentID: studentID
          },
          success: function(response) {
            callback(response);
          },
          error: function(xhr, status, error) {
            console.error("Error en la llamada AJAX:", status, error);
            callback(0);
          }
        });
      }

      function numeroEnLetras(numero) {
        // Mapea los valores numéricos a su representación en palabras
        const unidades = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
        const especiales = ['', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve'];
        const decenas = ['', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
        const centenas = ['', 'cien', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];
        // Mapea los valores de agrupación en palabras
        const agrupacion = ['', 'mil', 'millón', 'mil millones', 'billón', 'mil billones'];
        // Convierte el número a un string para iterar sobre cada dígito
        const numeroStr = numero.toString();

        // Convierte el número a un string para iterar sobre cada dígito
        let resultado = '';

        // Convierte el número en letras de acuerdo a su longitud
        if (numero === 0) {
          resultado = 'cero';
        } else if (numero < 10) {
          resultado = unidades[numero];
        } else if (numero < 20) {
          resultado = especiales[numero - 10];
        } else if (numero < 100) {
          const decena = Math.floor(numero / 10);
          const unidad = numero % 10;
          resultado = decenas[decena];
          if (unidad !== 0) {
            resultado += ' y ' + unidades[unidad];
          }
        } else if (numero < 1000) {
          const centena = Math.floor(numero / 100);
          const resto = numero % 100;
          resultado = centenas[centena];
          if (resto !== 0) {
            resultado += ' ' + numeroEnLetras(resto);
          }
        } else {
          // Encuentra la agrupación correspondiente y divide el número en partes
          let grupo = 0;
          let resto = numero;
          while (resto >= 1000) {
            resto /= 1000;
            grupo++;
          }
          const agrupacionActual = agrupacion[grupo];
          const agrupacionSiguiente = agrupacion[grupo + 1];
          const parteEntera = Math.floor(resto);
          const parteDecimal = resto - parteEntera;

          // Concatena las partes del número en letras
          resultado = numeroEnLetras(parteEntera) + ' ' + agrupacionActual;
          if (parteDecimal > 0) {
            resultado += ' ' + numeroEnLetras(parteDecimal) + ' ' + agrupacionSiguiente;
          }
        }

        return resultado.toUpperCase();
      }

      function GetFeeForm(sid, branch, career) {
        $.ajax({
          type: 'post',
          url: 'getfeeform.php',
          data: {
            student: sid,
            req: '1'
          },
          success: function(data) {
            $('#formcontent').html(data);
            $("#myModal").modal({
              backdrop: "static"
            });
            $('#formcontent').find('#studentId').val(sid);
            $('#formcontent').find('#careerValue').val(career);
            $('#formcontent').find('#branchValue').val(branch);
          }
        });


      }


      ////////////////////////////
    </script>

    <style>
      #doj .ui-datepicker-calendar {
        display: none;
      }
    </style>

    <div class="panel panel-default">
      <div class="panel-heading">
        Gestionar Pagos
      </div>
      <div class="panel-body">
        <div class="table-sorting table-responsive" id="subjectresult">
          <table class="table table-striped table-bordered table-hover" id="tSortable22">
            <thead>
              <tr>

                <th>Nombre</th>
                <th>Pagos</th>
                <th>Balance</th>
                <th>Sucursal</th>
                <th>Carrera</th>
                <th>Fecha Ingreso</th>
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
            <h4 class="modal-title">Tomar Pago</h4>
          </div>
          <div class="modal-body" id="formcontent">

          </div>
          <div class="modal-footer">
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
  CONTACTOS Y REFERENCIAS EN: <a href="https://www.facebook.com/people/Instituto-Del-Carmen-Cochabamba/100084527167834/" target="_blank"><i class="fa fa-facebook-square fa-2x" aria-hidden="true"></i> INSTITUTO DEL CARMEN</a>
</div>


<!-- BOOTSTRAP SCRIPTS -->
<script src="js/bootstrap.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="js/custom1.js"></script>


</body>

</html>