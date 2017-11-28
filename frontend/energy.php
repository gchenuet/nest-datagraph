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
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <script type="text/javascript" src="js/nest-energy.js"></script>
        <script type="text/javascript" src="js/datetimepicker.js"></script>
    </head>
    <body>
        <?php
        include('php/nestFunctions.php');
        $ini = parse_ini_file(realpath("../config/settings.ini"), true);
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
                                <li><a href="index.php">Overview</a></li>
                                <li class="active"><a href="energy.php">Energy</a></li>
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
                <div class="item active" id="carousel-energy">
                    <div class="container">
                        <div class="carousel-caption">
                            <h1>Energy Panel</h1>
                            <p>Analyze your Nest energy consumption</p>
                            <p><a class="btn btn-lg btn-info" href="#energy" role="button">Discover</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.carousel -->
        <!-- Energy -->
        <div class="container energy" id="energy">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="reportrange" class="pull-right">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span></span> <b class="caret"></b>
                    </div>
                </div>
            </div>
            <div id="test"><span id="myspan"></span></div>
            <div class="panel panel-default"> <!-- Leaf Panel -->
                <div class="panel-heading"> <h3 class="panel-title">Temperature History</h3></div>
                <div class="panel-body">
                    <div class="row">
                        <div id="nest-temp" class="col-md-12 nest-day"></div>
                    </div>
					<div class="btn-group">
						<button type="button" class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Options <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a id="TempFullAll">All</a></li>
    						<li role="separator" class="divider"></li>
							<li><a id="TempFullTarget">Hide Target Temperature</a></li>
							<li><a id="TempFullInt">Hide Interior Temperature</a></li>
							<li><a id="TempFullExt">Hide Exterior Temperature</a></li>
  						</ul>
					</div>
                </div>
            </div> <!-- /.panel -->
            <div class="panel panel-default"> <!-- Leaf Panel -->
                <div class="panel-heading"> <h3 class="panel-title">Humidity History</h3></div>
                <div class="panel-body">
                    <div class="row">
                        <div id="nest-hum" class="col-md-12 nest-day"></div>
                    </div>
					<div class="btn-group">
						<button type="button" class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Options <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a id="HumFullAll">All</a></li>
    						<li role="separator" class="divider"></li>
							<li><a id="HumFullTarget">Hide Target Humidity</a></li>
							<li><a id="HumFullInt">Hide Interior Humidity</a></li>
							<li><a id="HumFullExt">Hide Exterior Humidity</a></li>
  						</ul>
					</div>
                </div>
            </div> <!-- /.panel -->
            <div class="panel panel-default"> <!-- Panel -->
                <div class="panel-heading"> <h3 class="panel-title">Heating Time History</h3></div>
                <div class="panel-body">
                    <div id="nest-heat" class="col-md-12 nest-day"></div>
                </div>
            </div> <!-- /.panel -->
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default panel-values"> <!-- Panel -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <img src="images/fire-cloud.svg" class="img-responsive img-head" alt="Heating">
                                </div>
                                <div class="col-md-12 data-center">
                                    <p><strong><span id="heating-perc-value"></span>%</strong></p>
                                    <p class="desc-value">Heating Time</p>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.panel -->
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default panel-values"> <!-- Panel -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <img src="images/leaf.png" class="img-responsive img-head" alt="Leaf">
                                </div>
                                <div class="col-md-12 data-center">
                                    <p><strong><span id="leaf-value"></span></strong></p>
                                    <p class="desc-value">Nest Leafs</p>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.panel -->
                </div>
            </div>
            <div class="panel panel-default"> <!-- Leaf Panel -->
                <div class="panel-heading"> <h3 class="panel-title">Leaf History</h3></div>
                <div class="panel-body">
                    <div class="row">
                        <div id="nest-leaf" class="col-md-12 nest-day"></div>
                    </div>
                </div>
            </div> <!-- /.panel -->
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default panel-values"> <!-- Panel -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <img src="images/nest-house.svg" class="img-responsive img-head" id="home-img">
                                </div>
                                <div class="col-md-12 data-center">
                                    <p><strong><span id="home-perc-value"></span>%</strong></p>
                                    <p class="desc-value">At Home</p>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.panel -->
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default panel-values"> <!-- Panel -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <img src="images/auto-away.svg" class="img-responsive img-head" id="away-img">
                                </div>
                                <div class="col-md-12 data-center">
                                    <p><strong><span id="away-perc-value"></span>%</strong></p>
                                    <p class="desc-value">Away</p>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.panel -->
                </div>
            </div>
            <div class="panel panel-default"> <!-- Panel -->
                <div class="panel-heading"> <h3 class="panel-title">Presence History</h3></div>
                <div class="panel-body">
                    <div id="nest-away" class="col-md-12 nest-day"></div>
                </div>
            </div> <!-- /.panel -->
            <!-- /.Energy -->
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
