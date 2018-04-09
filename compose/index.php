<?php
    session_start();
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    require_once "../objects/main.php";
    require_once "../objects/users.php";
    $main = new Components;

    $meta = $main->renderHeader("Composer");
    $users = new Users;
    if( empty($_SESSION['uname'] ) ){
        echo "You need to log in.";
        header("Location: /login/?return=compose");
    }
    elseif( array_key_exists( $_SESSION['uname'], _USERS )){
        $articles = new Articles;

        if(!isset($_GET['edit'])){
            $edit = $articles->editArticle(null);
            $edit['date'] = time();
        }
        else{
            $edit = $articles->editArticle($_GET['edit']);
        }

?>
<article class='composerElement'>
<form id='composer' action='composer.php' method='post'>
    <h1>Write a New Post</h1>
    <fieldset class='compose--grid'>
    <!-- <div class='compose--group'> -->
        <div class='compose--meta col1span2 row1span1'>
            <label class='block'>Article Name</label>
            <input type='text' name='meta[title]' class='compose--title' value='<?php echo htmlspecialchars($edit['title'],ENT_QUOTES) ?>'/>
        </div>
        <div class='compose--meta col3span1 row1span1'>
            <label class='block'>Author</label>
            <select name='meta[author]'>
                <?php foreach(_USERS as $key => $value){
                    echo "<option value='" . $value['uname'] . "'";
                    if($value['uname'] == $edit['meta']['author']){
                        echo " checked";
                    }
                    echo ">".$value['fname']."</option>";
                } ?>
            </select>
            <input type='hidden' name='meta[hidden]' value='false' />
            
        </div>
    <!-- </div> -->
    <!-- <div class='compose--group col1span2 row1span1'> -->
        <div class='compose--meta col1span1 row2span1'>
            <label class='block'>Date</label>
        <?php
            echo "<input type='date' name='meta[date]' value='".date('Y-m-d',$edit['date'])."' />";
            echo "</div>";
            echo "<div class='compose--meta col2span1 row2span1'>";
                echo "<label class='block'>Time</label>";
                echo "<input type='time' name='meta[time]' value='".date('H:i',$edit['date'])."' />";
            echo "</div>";
            echo "<input type='hidden' value='".$_GET['edit'].".json' name='meta[edit]' />";
        if(is_numeric($edit['date'])){
            echo "<input type='hidden' value='".$edit['filename']."' name='meta[filename]' />";
        }
        ?>
        <div class='compose--meta col3span1 row2span1'>
            <div>
                <input type='checkbox' id='metahidden' name='meta[hidden]' value='true' <?php ($edit['hidden'] === "true") ? "checked" : "";?>/>
                <label for='metahidden'>Hidden Post</label><br>
                <input type='checkbox' id='metapage' name='meta[page]' value='true' <?php ($edit['page'] === "true") ? "checked" : "";?>/>
                <label for='metapage'>Is this a page?</label>
            </div>
        </div>
        </div>
    <!-- </div> -->
    <!-- <div class='compose--group col4span1 row1span2'> -->
        <button name='post' class='col4span1 row1span2' id='compose--submit'>Post</button>
    <!-- </div> -->
    </fieldset>
<fieldset>
<h2>Body</h2>
<textarea id='compose' name='body'>
<?php   
    echo $edit['body'];
?>
</textarea>
</fieldset>
</form>
<table class='compose--demo'>
<tr>
<td>
<h4>Before</h4>
<pre>
Markdown
========
Write your post in *markdown*
</pre>
</td>
<td>
<h4>After</h4>
<h3>
Markdown
</h3>
Write your post in <em>markdown</em>
</td>
</tr>
</table>
</article>
<?php
        $articles->renderDirectory();
    }
    $main->renderFooter();

?>