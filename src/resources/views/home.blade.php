<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workload</title>
    <meta name="description" content="">
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link href="{{mix('/css/app.css')}}" rel="stylesheet">


</head>
<body class="fix-header fix-sidebar card-no-border">


<div id="app">
    <router-view></router-view>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
<script src="{{mix('/js/app.js')}}"></script>

</body>
</html>
