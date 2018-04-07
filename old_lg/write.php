<?php
    session_start();
    if($_SESSION['valid'] === true &&  $_SESSION['username'] === file_get_contents('user.txt')){
        if (isset($_POST["update"])){
            $postId = htmlspecialchars($_POST["value"]);
            $filename = "posts/" . $postId . ".md";
            echo $postId;
        }
        else{
            $filename = "posts/" . date("Y-m-d") . ".md";
        }
        if (isset($_POST['preview'])) {
            include '/Parsedown.php';
            $Parsedown = new Parsedown();
            print("<link rel='stylesheet' type='text/css' href='../style.css'><div class='container post'><div class='center preview new-posts'>"); // Creates individual post wrappers
            echo $Parsedown->text($_POST['postContent']); // Imports .md files and pipes it through Parsedown, fails graciously
            print("</div></div>");
        } // End preview
        elseif (isset($_POST['submit'])){
            if(isset($_POST['postContent'])) {
                $data = $_POST['postContent'] . "\n";
                $handle = fopen($filename, 'w');
                $ret = file_put_contents($filename, $data, FILE_APPEND | LOCK_EX);
                fclose($handle);
                if($ret === false) {
                    die('There was an error writing this file');
                }
                else {
                    echo "Your post has been published.";
                }
            }
        } // End submit
        elseif(isset($_POST['update'])){
            if(is_readable($filename)){
                $data = $_POST['postContent'] . "\n";
                $handle = fopen($filename, 'w');
                fwrite($handle,$data);
                fclose($handle);
                echo "Post has been updated.";
            }
        }
        elseif($postId != null){
            unlink("posts/".$postId.".md");
            echo "The post has been deleted.";
        }
    }
    else{
        die("You're not logged in, buddy.");
    }
?>