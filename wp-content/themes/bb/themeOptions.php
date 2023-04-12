<?php
header("Content-type: text/css; charset: UTF-8");



/**
 * EDIT OPTIONS HERE
 */

$themeOptions=array(
    "headerType"=>"f", //f is fixed, s is sticky, anything else = relative
    "footerType"=>"", //f is fixed, s is sticky, anything else = relative
    "enableCentering"=>true
);

/**
 * STOP EDITING HERE
 */



if($themeOptions["headerType"] == "f"){
?>
    #header{    
        position:fixed;
        top:0;
        left:0;
        width:100%;
    }
<?php
}elseif($themeOptions["headerType"] == "s"){
?>

    #header{    
        position:sticky;
        top:0;
        left:0;
        
    }

<?php
}
?>

<?php
if($themeOptions["footerType"] == "f"){
?>
    #footer{    
        position:fixed;
        bottom:0;
        left:0;
        width:100%;
    }
<?php
}elseif($themeOptions["footerType"] == "s"){
?>

    #footer{    
        position:sticky;
        bottom:0;
        left:0;
        
    }

<?php
}
?>


<?php

if($themeOptions["enableCentering"]){
?>
    .content{
        display:flex;
        align-items:center;
        justify-content:center;
    }
<?php
}

if($themeOptions["headerType"]=="f" && $themeOptions["footerType"]=="f"){
?>

    .site-wrapper{
        grid-template-rows:1fr;
    }

<?php
}

if($themeOptions["headerType"]=="s" && $themeOptions["footerType"]=="f"){
    ?>
    
        .site-wrapper{
            grid-template-rows:auto 1fr;
        }
    
    <?php
}



if($themeOptions["headerType"]=="f" && $themeOptions["footerType"]=="s"){
    ?>
    
        .site-wrapper{
            grid-template-rows:1fr auto;
        }
    
    <?php
}

if($themeOptions["headerType"]=="s" && $themeOptions["footerType"]=="s"){
    ?>
    
        .site-wrapper{
            grid-template-rows:auto 1fr auto;
        }
    
    <?php
}
    
    

?>


