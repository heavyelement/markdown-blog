<?php
class Articles{
    function parseArticle($json){

        // This grabs the relevant metadata for the article
        // Then passes it back to the main script.

        global $meta;
        $path = _META . $json . '.json';
        $meta = json_decode(file_get_contents($path),true);

        return $meta;

    } // end parseArticle

    function saveArticle(){
        // This function assumes it's been called when $_POST has been submitted

        $hiddenFile = '';

        if($_POST['meta']['hidden'] == "true"){
            $hiddenFile = '.';
        }

        // If $_POST filename is empty, create a file name
        if($_POST['meta']['filename'] == ''){
            $_POST['meta']['filename'] = "../articles/" . md5(json_encode($_POST)) . ".md";
        }

        // Checks if the article submitted is old *or* new, decides on a filename appropriately
        if(empty($_POST['meta']['date'])){
            $_POST['meta']['date'] = time();
        }
        else{
            $_POST['meta']['date'] = strtotime($_POST['meta']['date'] . " " . $_POST['meta']['time']);
        }
        // else{
        //     $_POST['meta']['date'];
        // }

        // A bit of error checking when writing the files...
        if(file_put_contents($_POST['meta']['filename'],$_POST['body'])){
            echo "Saved post as ".$_POST['meta']['filename'] . "<br>";
            if(file_put_contents("../meta/$hiddenFile" . $_POST['meta']['date'] . '.json',json_encode($_POST['meta']))){
                echo "Saved meta file /meta/$hiddenFile" . $_POST['meta']['date'] . '.json<br>';
                if(!empty($_POST['meta']['edit'])){
                    if($_POST['meta']['date'] . ".json" !== $_POST['meta']['edit']){
                        if(unlink("../meta/".$_POST['meta']['edit'])){
                            echo "Date changed on post. Removing unneeded files.";
                        }
                    }
                    else{
                        echo "No need to change or remove files...";
                    }
                }
            }
            else{
                echo "Error writing meta descriptor /meta/" . $_POST['meta']['date'] . '.json';
                exit;
            }
        }
        else{
            echo "Error writing file ".$_POST['meta']['filename'];
            exit;
        }
    }

    function editArticle($id){
        
        if(file_exists('../meta/'.$id.'.json') && $id !== null){
            $meta = json_decode(file_get_contents('../meta/'.$id.'.json'),true);
            $meta['body'] = file_get_contents($meta['filename']);
            return $meta;
        }
        else{
            return $meta = [
                'title' => null,
                'author' => null,
                'date' => null,
                'hidden' => null,
                'filename' => null,
                'body' => null
            ];
        }
    }
    

    function renderArticle($meta,$linkIt){

        // Recieves the meta info from the main script

        // Loads the file referenced in the metadata
        $article = file_get_contents(_ARTICLES . $meta['filename']);

        $parsedown = new Parsedown;
        
        $link = ['before'=>'','after'=>''];

        if($linkIt == true){
            $link = [
                'before' => "<a href='read/?id=".substr($linkIt,0,-5)."'>",
                'after' => "</a>"
            ];
        }

?>
        <article>
            <div class='article-header'>
            <h1 class='title'><?php echo $link['before'] . $meta['title'] . $link['after']; ?></h1>
            <div class='article-meta writer-image'>
                <img src='<?php echo _USERS[$meta['author']]['image'] ?>' width='50px' height='50px' />
            </div>
            <div class='article-meta'>
                <div class='article-writer'>
                    <span class='writer-intro'>Written by: </span>
                    <span class='writer-name'><?php 
                    if(!empty(_USERS[$meta['author']]['fname'])){
                        echo _USERS[$meta['author']]['fname'];
                    }
                    else{
                        echo $meta['author'];
                    }
                    ?></span>
                    <?php
                        echo ($linkIt == true && array_key_exists($_SESSION['uname'],_USERS) ) ? "<a href='/compose/?edit=". $meta['date'] ."' class='ion-edit'></a> " : '';
                    ?>
                </div>
                <div class='article-date'>Posted: <span title='<?php echo $meta['date']; ?>'>
                    <?php echo date('l F jS, Y - g:i A',$meta['date']); ?>
                </span>
                </div>
            </div>
            <?php echo $parsedown->text($article); ?>
        </article>

<?php
    } // end renderArticle

    function renderPage($meta,$linkIt){

        // Recieves the meta info from the main script

        // Loads the file referenced in the metadata
        $article = file_get_contents(_ARTICLES . $meta['filename']);

        $parsedown = new Parsedown;
        
        $link = ['before'=>'','after'=>''];

        if($linkIt == true){
            $link = [
                'before' => "<a href='read/?id=".substr($linkIt,0,-5)."'>",
                'after' => "</a>"
            ];
        }

?>
        <article>
            <div class='article-header'>
            <h1 class='title'><?php echo $link['before'] . $meta['title'] . $link['after']; ?></h1>
            <?php echo $parsedown->text($article); ?>
            <div class='article-meta'>
                <div class='article-date'>Last edit: 
                    <?php echo date('l F jS, Y - g:i A',$meta['date']); ?>
                </div>
            </div>
        </article>

<?php
    }

    function renderMulti($dir){
        
        // Scans $dir for items that are not hidden, ".", or ".."
        $indir = array_filter(scandir($dir,1), function($item) {
            $time = substr($item,0,10);
            if( (int)$time <= time() ){
                return $item[0] !== '.';
            }
        });

        if(isset($_GET['page'])){
            $listing['next'] = htmlspecialchars($_GET['page']);
        }
        else{
            $listing['next'] = 0;
        }

        $listing = [
            'next' => $listing['next'],
            'pageN' => ($listing['next'] + 1),
            'pageG' => ($listing['next'] - 1),
            'group' => ($listing['next'] * 3),
        ];

        $listing['more'] = $listing['group'] + 3;
        
        // if($listing['next'] == 0){
        //     $posts = array_slice($indir, 0, 3);
        // }
        // else{
            $posts = array_slice($indir, $listing['group'], 3);
        // }
        foreach($posts as $article){
                $this->renderArticle(json_decode(file_get_contents($dir . $article),true),$article);
        }

        $classN = '';
        $classP = '';
        switch($listing['pageG']){
            case -1:
                $classP = 'class="disabled"';
            case 0:
                $prevHref = "/";
                break;
            default:
                $prevHref = "?page=" . $listing['pageG'];
                break;
        }
        
        if($listing['more'] < count($indir)){
            $nextHref = '?page=' . $listing['pageN'];
        }
        else{
            $nextHref = '#';
            $classN = 'class="disabled"';
        }
        echo "<div id='navWrapper'>";
            echo "<div id='post-nav' class='gradient-background'>";
            echo "<a href='$nextHref' $classN><span class='ion-android-arrow-dropleft-circle'></span></a>";
            echo "<a href='$prevHref' $classP><span class='ion-android-arrow-dropright-circle'></span></a>";
            echo "</div>";
        echo "</div>";
    }

}
?>