<?php session_start();
    include "../header.php";
    echo"<article>";
    function composer(){
        echo '<div class="content securestuff"><div id="content" class="editor">'; // Sets up he HTML for the editor
        $postId = htmlspecialchars($_GET["id"]); // Sets the HTML ID as a variable
        $post = "../posts/" . $postId . ".md"; // Appends the relative path of the file to the post ID
        $filename = "../posts/" . date("Y-m-d") . ".md";

        echo '
            <h1>Compose your Post</h1>
            <div id="edit-wrap">
                <form action="writeToFile.php" target="previewpane" method="POST">
                    <textarea id="rawPost" name="postContent" rows="30" cols="30">'; // Creates text area and the form surrounding it
        
        if(isset($_GET["id"]) || is_readable($filename)){
            $deletable = true;
        }
        else{
            $deletable = false;
        }

        if($deletable === true){ // Checks if there's no post ID
            echo file_get_contents($post); // Writes the content of the post to the text area
        }
        echo '</textarea>';
        echo 
            '
                    <p>
                        ';
        if($deletable === true){
            echo '<button name="update" type="submit" value="' . $postId . '">Update</button>';
        }
        else{
            echo '<input name="submit" type="submit"></input>';
        }
        echo '<button name="preview" type="submit">Preview</button>
                    </p>
            ';
        echo '
                </form>
            ';
        
        echo '<iframe src="" name="previewpane"></iframe>'; // This is the preview pane. It's awesome, but could be better.
                    
        echo '</div>';
        if($deletable === true){ // If this is an existing post, enable the delete button.
            echo '<a href="writeToFile.php/?id=' . $postId . '" target="previewpane" >Delete This Post</abutton>';
        }
        echo '</div></div>'; // Close out div blocks
        include '../footer.php'; // Add a footer
    }

    if ($_SESSION['username'] == file_get_contents('../user.txt')){
        composer(); // Checks if the user is logs in, then renders the composer
    }
    else{
        include "../login.php"; // If the user isn't logged in, it pushes you to a login page.
    }
    echo "</article>";
?>