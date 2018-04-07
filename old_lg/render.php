<?php
	include 'Parsedown.php';
	$Parsedown = new Parsedown(); // Invokes Parsedown -- Moved from inside the foreach loop
	$dir = 'posts/'; // $dir defines the directory to search for the .md Markdown files

	if ( htmlspecialchars($_GET["id"]) == null ) {
		renderMulti($dir, $Parsedown);
	}
	else {
		renderSingle($dir, $Parsedown);
    }

	function renderMulti($dir, $Parsedown){
		$indir = array_filter(scandir($dir,1), function($item) { // This removes the . and .. directories from the array
			return $item[0] !== '.';
		});

		$next = htmlspecialchars($_GET["next"]); // Grabs ?next= string from url, increments and decrements into separate variables.
		$goNext = $next + 1; 
		$goPrev = $next - 1;
		$grabNext = $next * 3; // grabNext is used to set the first post in the process
		$grabFurther = $grabNext + 3; // Used to determine if there are more posts in the post-nav calculation

		if($next == 0){
			$posts = array_slice($indir, 0, 3); // This grabs the first three entries in the array
		}
		else {
			$posts = array_slice($indir, $grabNext, 3); // This sets the next page
		}

        print("<div id='down' class='container post'>"); // Generates the article wrapper

        foreach ($posts as &$value) {
			print("<div class='body'><div class='center new-posts'>"); // Creates individual post wrappers
			$post = $dir . $value; // Appends 'posts/' to each file
			$fancy = substr($value, 0, -3); // Removes .md from the end of each filename. Used to set the permalink
			
			echo is_readable($post) ? $Parsedown->text(file_get_contents($post)) : "<h1>Can't find ".htmlspecialchars($post)."</h1>"; // Imports .md files and pipes it through Parsedown, fails graciously
			echo "<div class='permalink-wrapper'><a href='?id=" . $fancy . "' alt='Permalink' class='permalink'>" . date("F jS, Y", strtotime($fancy)) . "</a></div>"; // Strips the .md from permalink and adds it to the end of each page
			print("</div></div>"); // Closes each post's wrapper
		}
		print "<div id='post-nav' class='gradient-background'>";
		
		if($goPrev == 0){ // This checks if goPrev will take us to the first page.
			echo "<a href='/'>&#x21B6;</a>"; // Sets URL for vanity's sake instead of displaying /new-arrivals/?next=0
		}
		elseif ($goPrev >= 1){ // Checks if there are previous posts
			echo "<a href='?next=" . $goPrev . "'>&#x21B6;</a>";
		}
		else{ // Disables previous button when appropriate
			print "<div class='nav-btn disabled'>&#x21B6;</div>";
		}
		if($grabFurther < count($indir)){ 
			echo "<a href='?next=" . $goNext . "'>&#x21B7;</a>";
		}
		else{
			print "<div class='nav-btn disabled'>&#x21B7;</div>";
		}
		print "</div><!-- end post-nav --></div><!-- end section -->";
	}
	
	function renderSingle($dir, $Parsedown){
		$post = $dir . htmlspecialchars($_GET["id"]) . ".md"; // Appends 'posts/' to each file
		if (is_readable($post) == true) {
			print("<div class='container post'><div class='center new-posts'>"); // Creates individual post wrappers
			echo $Parsedown->text(file_get_contents($post)); // Imports .md files and pipes it through Parsedown, fails graciously
			$fancy = substr( htmlspecialchars($_GET["id"]), 0, -3);
			if($_SESSION['valid'] === true){
				echo "<a href='compose/?id=".htmlspecialchars($_GET["id"])."' title='Edit this post.'>Edit this post.</a>";
			}
			$printDate = date("F jS, Y", strtotime($fancy));
			if ($printDate != "January 1st, 1970"){
				echo "<div class='permalink-wrapper'>" . $printDate . "</div>";
			}
			print("</div></div>"); // Closes each post's wrapper
		}
		elseif([$_GET["id"]=="bingo-2018"]){
			include("bingo/index.php");
		}
		else{
			include("404.php");
		}
	}
?>