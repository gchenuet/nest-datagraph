<!doctype html>
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
    </head>
    <body>
        <?php
        include('php/nestFunctions.php');
        require_once('php/nest.class.php');
        $ini = parse_ini_file("conf/settings.ini", true);
        define('USERNAME', $ini['nest']['nest_username']);
	define('PASSWORD', $ini['nest']['nest_password']);
        $lastRecord = json_decode(getLastRecord());
	$nest = new Nest();
	$protects = json_decode($nest->getProtectDevices(), true);
        ?>
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
                                <li><a href="energy.php">Energy</a></li>
                                <?php
	                                if ($ini['nest']['nest_protect'] == true) {
		                                echo "<li class='active'><a href='protect.php'>Protect</a></li>";
	                                }
	                            ?>
                            </ul>
                        </div>
                    </div>
                </nav>

            </div>
        </div>
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <div class="item active" id="carousel-protect">
                    <div class="container">
                        <div class="carousel-caption">
                            <h1>Protect Panel</h1>
                            <p>Know what happened with your Nest Protect</p>
                            <p><a class="btn btn-lg btn-info" href="#protect" role="button">Discover</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container protect" id="protect">
	    	<?php
		    	if (count($protects['structure']) > 1) {
			    	echo "<div class='btn-group btn-group-justified' role='group' aria-label='...'>";
		    			echo "<div class='btn-group' role='group'>";
							echo "<button class='btn btn-info filter-button' data-filter='all'>All</button>";
						echo "</div>";
						foreach($protects['structure'] as $value){
							echo "<div class='btn-group' role='group'>";
								echo "<button class='btn btn-default filter-button' data-filter='".$value['id']."'>".$value['name']."</button>";
							echo "</div>";
  					}
  					echo "</div>";
				}
				echo "<div class='row' id='protect-row'>";
				foreach($protects['protect'] as $protect) {
                	echo "<div class='col-md-6 content filter ".$protect['structure']."'>";
                    echo "<div class='panel panel-default'>";
                        echo "<div class='panel-heading'><h3 class='panel-title'>".$protect['name'];
                        if (!empty($protect['description'])) {
	                        echo " (".$protect['description'].")";
	                    }
	                    echo "</h3></div>";
                        echo "<div class='panel-body'>";
                            echo "<div class='row'>";
                                echo "<div class='col-md-12'>";
                                    echo "<img src='images/protect.png' class='img-responsive img-head' id='img-protect' alt='Nest Protect'>";
                                echo "</div>";
                                echo "<div class='col-md-6 protect-values'>";
                                	if ($protect['smoke_status'] == true) {
	                                	echo "<p><strong>Smoke&nbsp;</strong><span class='glyphicon glyphicon-remove-circle' aria-hidden='true'></span></p>";
                                	} else {
                                		echo "<p><strong>Smoke&nbsp;</strong><span class='glyphicon glyphicon-ok-circle' aria-hidden='true'></span></p>";
                                	}
                                echo "</div>";
                                echo "<div class='col-md-6 protect-values'>";
                                	if ($protect['co_status'] == true) {
	                                	echo "<p><strong>CO2&nbsp;&nbsp;</strong><span class='glyphicon glyphicon-remove-circle' aria-hidden='true'></span></p>";
                                	} else {
                                		echo "<p><strong>CO2&nbsp;&nbsp;</strong><span class='glyphicon glyphicon-ok-circle' aria-hidden='true'></span></p>";
                                	}
                                echo "</div>";
                                echo "<div class='col-md-6 protect-values'>";
                                	if ($protect['battery_status'] == true) {
	                                	echo "<p><strong>Battery&nbsp;</strong><span class='glyphicon glyphicon-remove-circle' aria-hidden='true'></span></p>";
                                	} else {
                                		echo "<p><strong>Battery&nbsp;</strong><span class='glyphicon glyphicon-ok-circle' aria-hidden='true'></span></p>";
                                	}
                                echo "</div>";
                                echo "<div class='col-md-6 protect-values'>";
                                	if ($protect['wifi_status'] == false) {
	                                	echo "<p><strong>Wi-Fi&nbsp;</strong><span class='glyphicon glyphicon-remove-circle' aria-hidden='true'></span></p>";
                                	} else {
                                		echo "<p><strong>Wi-Fi&nbsp;</strong><span class='glyphicon glyphicon-ok-circle' aria-hidden='true'></span></p>";
                                	}
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
			}
			echo "</div>";
		    ?>
            <div class="center-block">
                <p id="last-refresh"><span class="label label-info">Last refresh: <?php echo date('Y-m-d H:i:s', time()); ?></span></p>
            </div>
            <footer>
                <p class="pull-right"><a href="#">Back to top</a></p>
                <p>Made with <span class="glyphicon glyphicon-heart-empty"></span/> in Paris &middot; <a href="https://github.com/gchenuet/">Github</a></p>
        </footer>
    </div>
</body>
<script>
	$(document).ready(function(){
		$(".filter-button").click(function(){
			var value = $(this).attr('data-filter');
		  	if(value == "all") {
		  		$('.filter').show('1000');
        	}
			else {
           		$(".filter").not('.'+value).hide('3000');
		   		$('.filter').filter('.'+value).show('3000');
        	}
    	});
	});
</script>
</html>
