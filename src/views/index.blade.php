<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel users online</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
      body {
        padding: 25px;
      }
      h1 {
        font-size: 1.5em;
        margin-top: 0px;
      }
      .stack {
        font-size: 0.85em;
      }
      .date {
        min-width: 75px;
      }
      .text {
        word-break: break-all;
      }
      a.llv-active {
        z-index: 2;
        background-color: #f5f5f5;
        border-color: #777;
      }
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <h1><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Laravel Users Online</h1>
        </div>
        <div class="col-sm-9 col-md-10 table-container">
          <table id="table-user" class="table table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
              </tr>
            </thead>
            <tbody>

            @foreach($users as $key => $user)
                @if($user->isOnline())
                <tr>
                    <td class="date">{{{$user->name}}}</td>
                    <td >{{$user->email}}</td>
                </tr>
                @endif
            @endforeach

            </tbody>
          </table>
          </div>
        </div>
      </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script>
      $(document).ready(function(){
        $('#table-user').DataTable({
          "order": [ 1, 'desc' ],
          "stateSave": true,
          "stateSaveCallback": function (settings, data) {
            window.localStorage.setItem("datatable", JSON.stringify(data));
          },
          "stateLoadCallback": function (settings) {
            var data = JSON.parse(window.localStorage.getItem("datatable"));
            if (data) data.start = 0;
            return data;
          }
        });
        $('.table-container').on('click', '.expand', function(){
          $('#' + $(this).data('display')).toggle();
        });
      });
    </script>
  </body>
</html>

