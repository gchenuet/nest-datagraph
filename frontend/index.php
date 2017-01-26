<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="images/nest-ico.png">
        <title>Nest - Data Graph</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript" src="js/nest-index.js"></script>
    </head>
    <body>
	    <?php
        include('php/nestFunctions.php');
        $ini = parse_ini_file("php/params.ini", true);
        $lastRecord = json_decode(getLastRecord());
        ?>
        <!-- NAVBAR -->
        <div class="navbar-wrapper">
            <div class="container">
                <nav class="navbar navbar-inverse navbar-static-top">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="index.php"><img alt="Brand" src="images/nest_logo.svg"></a>
                        </div>
                        <div id="navbar" class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li class="active"><a href="index.php">Overview</a></li>
                                <li><a href="energy.php">Energy</a></li>
                                <?php
	                            if ($ini['common']['protect'] == "true") {
		                            echo "<li><a href='protect.php'>Protect</a></li>";
	                            }
	                            ?>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- /.Navbar -->
        <!-- Carousel -->
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <div class="item active" id="carousel-index">
                    <div class="container">
                        <div class="carousel-caption">
                            <h1>Welcome Home !</h1>
                            <p>Let's have a look to your Nest usage</p>
                            <p><a class="btn btn-lg btn-warning" href="#overview" role="button">Discover</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.carousel -->
        <!-- Overview -->
        <div class="container overview" id="overview">
            <?php
            if ($lastRecord[0]->nest_heat_state == 0) {
                echo "<div class='alert alert-success' role='alert'>Thermostat is currently <strong>IDLE</strong></div>";
            } else {

                echo "<div class='alert alert-danger' role='alert'>Thermostat is currently <strong>HEATING</strong>, ready in ".$lastRecord[0]->time_to_target." minutes</div>";
            }
            ?>
            <div class="panel panel-default">
                <div class="panel-heading"> <h3 class="panel-title">Climate Overview <small>(°C)</small></h3></div>
                <div class="panel-body">
                    <div class="row">
                        <div id="target-gauge" class="col-xs-6 col-sm-4 nest-gauges"></div>
                        <div id="int-gauge" class="col-xs-6 col-sm-4 nest-gauges"></div>
                        <div id="ext-gauge" class="col-xs-6 col-sm-4 nest-gauges"></div>
                    </div>
                </div>
            </div> <!-- /.panel -->
            <div class="panel panel-default">
                <div class="panel-heading"> <h3 class="panel-title">Temperature History</h3></div>
                <div class="panel-body">
	                <div class="row">       
                        <div id="nest-daytemp" class="col-md-12 nest-day"></div>
                    </div>
					<div class="btn-group">
						<button type="button" class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Options <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a id="TempDayAll">All</a></li>
    						<li role="separator" class="divider"></li>
							<li><a id="TempDayTarget">Hide Target Temperature</a></li>
							<li><a id="TempDayInt">Hide Interior Temperature</a></li>
							<li><a id="TempDayExt">Hide Exterior Temperature</a></li>
  						</ul>
					</div>
                </div>
            </div> <!-- /.panel -->
            <div class="panel panel-default">
                <div class="panel-heading"> <h3 class="panel-title">Humidity History</h3></div>
                <div class="panel-body">
                    <div class="row">       
                        <div id="nest-dayhum" class="col-md-12 nest-day"></div>
                    </div>
                    <div class="btn-group">
						<button type="button" class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Options <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a id="HumDayAll">All</a></li>
    						<li role="separator" class="divider"></li>
							<li><a id="HumDayTarget">Hide Target Humidity</a></li>
							<li><a id="HumDayInt">Hide Interior Humidity</a></li>
							<li><a id="HumDayExt">Hide Exterior Humidity</a></li>
  						</ul>
					</div>
                </div>
            </div> <!-- /.panel -->
            <!-- /.Overview -->
            <div class="center-block">
                <p id="last-refresh"><span class="label label-info">Last dataset: <?php echo $lastRecord[0]->date ?></span></p>
            </div>
            <!-- FOOTER -->
            <footer>
                <p class="pull-right"><a href="#">Back to top</a></p>
                <p>Made with <span class="glyphicon glyphicon-heart-empty"></span/> in Paris &middot; <a href="https://github.com/gchenuet/">Github</a></p>
        </footer>
    </div>
</body>
</html>
