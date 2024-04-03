<?php
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';
$action = "add";

$id = "";
$password = "";
$emailid = '';
$username = '';
$name = '';
$role_name = '';


if (isset($_POST['save'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5(mysqli_real_escape_string($conn, $_POST['password']));
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $role_name = mysqli_real_escape_string($conn, $_POST['role_name']);
    $emailid = mysqli_real_escape_string($conn, $_POST['emailid']);


    if ($_POST['action'] == "add") {
        $q1 = $conn->query("INSERT INTO user (username,password,name,emailid,role_name) VALUES ('$username','$password','$name','$emailid','$role_name')");
        $sid = $conn->insert_id;
        // echo '<script type="text/javascript">window.location="users.php?act=1";</script>';
    } else
  if ($_POST['action'] == "update") {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $sql = $conn->query("UPDATE  user  SET username = '$username', password = '$password', name = '$name', emailid  = '$emailid', role_name  = '$role_name' WHERE id = '$id'");
        echo '<script type="text/javascript">window.location="users.php?act=2";</script>';
    }
}




if (isset($_GET['action']) && $_GET['action'] == "delete") {

    $conn->query("UPDATE  user set delete_status = '1'  WHERE id='" . $_GET['id'] . "'");
    header("location: users.php?act=3");
}


$action = "add";
if (isset($_GET['action']) && $_GET['action'] == "edit") {
    $id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

    $sqlEdit = $conn->query("SELECT * FROM user WHERE id='" . $id . "'");
    if ($sqlEdit->num_rows) {
        $rowsEdit = $sqlEdit->fetch_assoc();
        extract($rowsEdit);
        $action = "update";
    } else {
        $_GET['action'] = "";
    }
}


if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "1") {
    $errormsg = "<div class='alert alert-success'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Usuario Agregado Exitósamente</div>";
} else if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "2") {
    $errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <strong>Excelente!</strong> Usuario Editado Exitósamente</div>";
} else if (isset($_REQUEST['act']) && @$_REQUEST['act'] == "3") {
    $errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Usuario Eliminado Exitósamente</div>";
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
    <link href="css/datepicker.css" rel="stylesheet" />

    <script src="js/jquery-1.10.2.js"></script>

    <script type='text/javascript' src='js/jquery/jquery-ui-1.10.1.custom.min.js'></script>


</head>
<?php
include("php/header.php");
?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Usuarios
                    <?php
                    echo (isset($_GET['action']) && @$_GET['action'] == "add" || @$_GET['action'] == "edit") ?
                        ' <a href="users.php" class="btn btn-primary btn-sm pull-right">Volver <i class="glyphicon glyphicon-arrow-right"></i></a>' : '<a href="users.php?action=add" class="btn btn-primary btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Agregar Usuario </a>';
                    ?>
                </h1>

                <?php

                echo $errormsg;
                ?>
            </div>
        </div>



        <?php
        if (isset($_GET['action']) && @$_GET['action'] == "add" || @$_GET['action'] == "edit") {
        ?>

            <script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>
            <div class="row">

                <div class="col-sm-10 col-sm-offset-1">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <?php echo ($action == "add") ? "Agregar Usuario" : "Editar Usuario"; ?>
                        </div>
                        <form action="users.php" method="post" id="signupForm1" class="form-horizontal">
                            <div class="panel-body">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Información Personal:</legend>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="Old">Nombre* </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="Old">Nombre de Usuario* </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="Old">Contraseña* </label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="Old">Correo Electronico* </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="emailid" name="emailid" value="<?php echo $emailid; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="Old">Rol* </label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="role_name" name="role_name">
                                                <option value="">Selecciona Rol</option>
                                                <option value="admin" <?php if ($role_name === "admin") echo "selected"; ?>>Administrador</option>
                                                <option value="secretaria" <?php if ($role_name === "secretaria") echo "selected"; ?>>Secretaria/o</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>



                                <div class="form-group">
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="action" value="<?php echo $action; ?>">

                                        <button type="submit" name="save" class="btn btn-primary">Guardar </button>



                                    </div>
                                </div>





                            </div>
                        </form>

                    </div>
                </div>


            </div>




            <script type="text/javascript">
                $(document).ready(function() {

                            if ($("#signupForm1").length > 0) {

                                <?php if ($action == 'add') {
                                ?>

                                    $("#signupForm1").validate({
                                            rules: {
                                                username: "required",
                                                password: "required",
                                                name: "required",
                                                role_name: "required",


                                                emailid: {
                                                    required: true,
                                                    email: true
                                                },
                                            },
                                        <?php
                                    } else {
                                        ?>

                                            $("#signupForm1").validate({
                                                rules: {
                                                    username: "required",
                                                    password: "required",
                                                    name: "required",
                                                    role_name: "required",



                                                    emailid: {
                                                        required: true,
                                                        email: true
                                                    },

                                                },



                                            <?php
                                        }
                                            ?>

                                            errorElement: "em",
                                            errorPlacement: function(error, element) {
                                                // Add the `help-block` class to the error element
                                                error.addClass("help-block");

                                                // Add `has-feedback` class to the parent div.form-group
                                                // in order to add icons to inputs
                                                element.parents(".col-sm-10").addClass("has-feedback");

                                                if (element.prop("type") === "checkbox") {
                                                    error.insertAfter(element.parent("label"));
                                                } else {
                                                    error.insertAfter(element);
                                                }

                                                // Add the span element, if doesn't exists, and apply the icon classes to it.
                                                if (!element.next("span")[0]) {
                                                    $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                                                }
                                            },
                                            success: function(label, element) {
                                                // Add the span element, if doesn't exists, and apply the icon classes to it.
                                                if (!$(element).next("span")[0]) {
                                                    $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
                                                }
                                            },
                                            highlight: function(element, errorClass, validClass) {
                                                $(element).parents(".col-sm-10").addClass("has-error").removeClass("has-success");
                                                $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
                                            },
                                            unhighlight: function(element, errorClass, validClass) {
                                                $(element).parents(".col-sm-10").addClass("has-success").removeClass("has-error");
                                                $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
                                            }
                                            });

                                        }

                                    });
            </script>



        <?php
        } else {
        ?>

            <link href="css/datatable/datatable.css" rel="stylesheet" />




            <div class="panel panel-default">
                <div class="panel-heading">
                    Administrar Información de los Usuarios
                </div>
                <div class="panel-body">
                    <div class="table-sorting table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="tSortable22">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Nombre de usuario</th>
                                    <th>Contraseña</th>
                                    <th>Correo Electronico</th>
                                    <th>Rol</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "select * from user where delete_status='0'";
                                $q = $conn->query($sql);
                                $i = 1;
                                while ($r = $q->fetch_assoc()) {

                                    echo '<tr>
                                            <td>' . $i . '</td>
                                            <td>' . $r['name'] . '</td>
                                            <td>' . $r['username'] . '</td>
                                            <td>' . $r['password'] . '</td>
                                            <td>' . $r['emailid'] . '</td>
                                            <td>' . $r['role_name'] . '</td>
											<td>
											
											

											<a href="users.php?action=edit&id=' . $r['id'] . '" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span></a>
											
											<a onclick="return confirm(\'Deseas realmente eliminar este registro, este proceso es irreversible\');" href="users.php?action=delete&id=' . $r['id'] . '" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a> </td>
											
                                        </tr>';
                                    $i++;
                                }
                                ?>



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <script src="js/dataTable/jquery.dataTables.min.js"></script>

            <script>
                $(document).ready(function() {
                    $('#tSortable22').dataTable({
                        "bPaginate": true,
                        "bLengthChange": true,
                        "bFilter": true,
                        "bInfo": false,
                        "bAutoWidth": true
                    });

                });
            </script>

        <?php
        }
        ?>



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