<?php
/**
 *  - Static source code analyzer for PHP vulnerabilities.
 * 
 * @author Dario Ghilardi <darioghilardi@webrain.it>
 * @copyright Copyright (c) 2011, Dario Ghilardi
 * 		
 **/

?>

<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>- Static source code analyzer for PHP vulnerabilities.</title>
	<meta name="description" content="">
	<meta name="author" content="">
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	<link rel="stylesheet" href="css/style.css?v=2">

	<script src="js/libs/modernizr-1.7.min.js"></script>
</head>
<body>
	<div id="container">
		<header>

		</header>

		<div id="main" role="main">
            <form id="payment">
                <fieldset>
                    <legend>Launch the scan</legend>
                    <ol>
                        <li>
                            <label for="files">Files/Directory</label>
                            <input id="files" name="files" type=text placeholder="Add here the path to files or a directory..." required autofocus>
                        </li>
                        <li>
                            <button type="submit">Launch</button>
                        </li>
                    </ol>
                </fieldset>
            </form>
                
            <div id="result">
	
                <div style="margin-left:30px;color:#000000;font-size:14px">
                    <h3>Guide:</h3>
                    <p>Locate the path to the PHP files you would like to scan and click the launch button. You can also submit a directory.</p>
                    <p>Note that scanning too many large files may exceed the time limit.</p>
                </div>
            </div>
                
		</div>

		<footer>
            <p> - &copy; 2011 Dario Ghilardi</p>
		</footer>
	</div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.5.1.min.js"%3E%3C/script%3E'))</script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>
	<!--[if lt IE 7 ]>
	<script src="js/libs/dd_belatedpng.js"></script>
	<script> DD_belatedPNG.fix('img, .png_bg');</script>
	<![endif]-->
</body>
</html>