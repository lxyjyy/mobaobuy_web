<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    {include file ='library/admin_html_head.lbi'}
</head>

<body style="background-color: #31b4e1; overflow:hidden;">
{insert_scripts files="adminIndex.js"}
{include file ='library/pageheader.lbi'}
<div class="admin-main">
    <div class="top-border"></div>
    {include file ='library/admin_main_left.lbi'}
    <div class="admin-main-right">
        <iframe src="index.php?act=main" id="workspace" name="workspace" frameborder="0" width="100%" height="95%" scrolling="yes"></iframe>
    </div>
</div>
<script type="text/javascript" src="js/jquery.purebox.js"></script>
<script type="text/javascript">
    var bodyWidth = $("body").width();

    if(bodyWidth<1380){
        $("#workspace").attr("height","92%");
    }else{
        $("#workspace").attr("height","95%");
    }

    $(window).resize(function(e) {
        bodyWidth = $("body").width();

        if(bodyWidth<1380){
            $("#workspace").attr("height","92%");
        }else{
            $("#workspace").attr("height","95%");
        }
    });
</script>
</body>
</html>
