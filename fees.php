<?php
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';
if (isset($_POST['save'])) {
  $paid = mysqli_real_escape_string($conn, $_POST['paid']);
  $submitdate = mysqli_real_escape_string($conn, $_POST['submitdate']);
  $transcation_remark = mysqli_real_escape_string($conn, $_POST['transcation_remark']);
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
}

if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "1") {
  $errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Pago realizado exitósamente</div>";
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
              <label for="email"> Carreras </label>
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

            <button type="button" class="btn btn-success btn-sm" id="find"> Búsqueda </button>
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

          $("#subjectresult").html('<table class="table table-striped table-bordered table-hover" id="tSortable22"><thead><tr><th>Name/Contact</th><th>Fees</th><th>Balance</th><th>Branch</th><th>DOJ</th><th>Action</th></tr></thead><tbody></tbody></table>');

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

        ///////////////////////////		
        $('#myModal').on('shown.bs.modal', function() {
          // Agregar el script para generar el reporte PDF
          $('#signupForm1').on('submit', function(e) {
            e.preventDefault();

            var doc = new jspdf.jsPDF('p', 'pt', 'letter');
            var table = document.createElement('table');

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
            // Add date of issuance
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = today.getFullYear().toString();
            // var formattedDate = dd + '-' + mm + '-' + yyyy; // Current date
            // Add date of issuance - Right aligned
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
            var studentName = "Juan Perez";
            var studentID = "123456789";
            doc.text('Recibí de: ' + studentName + '                   CI: ' + studentID, 50, 160);

            // Add amount received
            var amountReceived = 500;
            var amountInWords = "quinientos ";
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
            // var tableHeight = (data.length + 1) * 20;
            // if (startY + tableHeight > pageHeight) {
            //   doc.addPage();
            //   startY = 50;
            // }
            // startY = doc.autoTable.previous.finalY + 20;

            // Añadir el resto del contenido al PDF
            // doc.text("Nombre: " + document.getElementById("name").value, 1, 2);
            // doc.text("Contacto: " + document.getElementById("contact").value, 1, 3);
            // doc.text("Pago Total: " + document.getElementById("totalfee").value, 1, 4);
            // doc.text("Balance: " + document.getElementById("balance").value, 1, 5);
            // doc.text("Cantidad a Pagar: " + document.getElementById("paid").value, 1, 6);
            // doc.text("Fecha: " + document.getElementById("submitdate").value, 1, 7);
            // doc.text("Observación: " + document.getElementById("transaction_remark").value, 1, 8);


            // Guardar el PDF
            doc.save("comprobante.pdf");


            // Después de guardar el PDF, puedes continuar con el envío del formulario
            // this.submit();
          });
        });

        // ... (tu código existente)


      });


      function GetFeeForm(sid) {

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