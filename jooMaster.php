<?php

namespace JooMaster;

class JooMaster
{
    var
    $rootPath = __DIR__,
    $_REQ;

    # Bootstrap
    function __construct($_REQ)
    {
        if($_REQ['action']=='index')
        {
            $this->indexPage();
        }
        elseif($_REQ)
        {
            $this->_REQ = $_REQ;
            $this->{$_REQ['action']}();
        }
    }

    
    # Pages
    function indexPage()
    {
        echo 'Глагне';
    }
    function getPHPinfo()
    {
        phpinfo();
    }
    function test()
    {
        echo $this->_REQ['foo'];
        echo $this->_REQ['bar'];
    }
}

new JooMaster($_REQUEST);

if(!$_REQUEST): ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="JooMaster" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <title>JooMaster</title>
    </head>
    <body>
<style>
html,body{
    min-width: 320px;
    min-height: 100%;
    margin: 0;
}

body {
    font-family: "Trebuchet MS", Helvetica, sans-serif;
    font-size: 16px;
}
#header {
    background: #fff;
    height: 43px;
    padding: 10px;
    box-shadow: 0px 2px 1px 0px rgba(102, 93, 93, 0.15);
}
#wrapper {
    width: 100%;
    height: 100%;
    border-collapse: collapse;
}
#wrapper td {
    vertical-align: top;
}

/* vertical-menu */
#menu {
    background: #82ADD3;
    padding: 13px 0px;
}
#menu ul {
    padding-left: 10px;
    padding-right: 10px;
    margin-top: 10px;
}
#menu li{
    list-style: none;
    margin-bottom: 5px;
}
#menu .parent + ul {
    display: none;
}
#menu i.icon {
    margin-right: 10px;
}
#menu .parent i:not(.icon) {
    float: right;
    padding-top: 4px;
}
#menu li.parent, #menu li {
    background: #6e92b1;
    padding: 5px 10px;
    cursor: pointer;
    color: #cddeed;
    border-radius: 3px;
}
#menu li.parent:hover, #menu li:hover {
    background: #61809b;
    color: #fff;
}
/*.vertical-menu li a {
    display: block;
}*/
#menu li.active {
    background: #7B97B0;
}

/*current */
#menu li.current {
    background: #61809b;
    color: #fff;
    border-right: 7px solid #FFFFFF;
}

/* button exit */
#header .buttons-right {
    float: right;
}
#header .buttons-right a, #header .buttons-right i {
    display: block;
    width: 30px;
    height: 32px;
}
#header .buttons-right a {
    padding-top: 11px;
    padding-left: 15px;
    border-radius: 25px;
    font-size: 20px;
    border: 1px solid #D0D0D0;
    color: #949494;
    text-decoration: none;
}
#header .buttons-right a:hover {
    background: #D0D0D0;
    text-decoration: none;
    color: #000;
}


#workspace {
    margin-left: 30px;
    margin: 30px;
}
</style>
            <div id="header">
                <input class="fields" name="foo" type="text" />
                <input class="fields" name="bar" type="text" />
<!--                <img id='logotype' src='/images/gymlogo.png'>-->
                <div class="buttons-right">
                    <a href="/exit"><i class="fa fa-sign-out"></i></a>
                </div>
            </div>
            <table id="wrapper">
                <tbody>
                    <tr>
                        <td width="300px">
                            <div id="menu">
                                <ul>
                                    <li do="test@.fields->#workspace" class="menuitem"><i class="fa fa-puzzle-piece icon"></i>Модули</li>
                                    <li do="getPHPinfo@->#workspace" class="menuitem"><i class="fa fa-file-text icon"></i>Файлы</li>
                                    <li class="menuitem parent"><i class="fa fa-bars icon"></i>Управление<i class="fa fa-folder"></i></li>
                                    <ul>
                                        <li do="put:root/users:console-workspace" class="menuitem"><i class="fa fa-users icon"></i>Пользователи</li>
                                        <li do="#position-2" class="menuitem">Позиция 2</li>
                                        <li do="#position-4" class="menuitem">Позиция 3</li>
                                        <li do="#position-4" class="menuitem">Позиция 4</li>
                                    </ul>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <div id="workspace">
                                тут
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        <script>
            function dataParse(inputgroup)
            {
                var inputs = $(inputgroup).size();
                var data = {};
                for (var i = 0; i < inputs; i++)
                {
                    var obj = $(inputgroup).eq(i);
                    var name = $(obj).attr("name");
                    var tag = $(obj).get(0).tagName;
                    if (tag === "INPUT" || tag === "TEXTAREA")
                    {
                        data[name] = $(obj).val();
                    } else
                    {
                        data[name] = $(obj).text();
                    }
                }
                return data;
            }

            function ajaxDo(command)
            {
                var data;
                var command = command.split('@');
                var action = command[0];
                var command = command[1].split('->');
                var inputs = command[0];
                var wspace = command[1];
                
                if(inputs){
                    data = dataParse(inputs);
                }
                
                //console.log('action='+action+' inputs='+inputs+' wspace='+wspace);
                $.ajax({
                    type: "POST",
                    url: "?action=" + action,
                    cache: false,
                    data: data,
                    success: function(html)
                    {
                        $(wspace).html(html);
                    }
                });
                
            }
            
            $(document).on("click", "[do]", function () {
                ajaxDo($(this).attr('do'));
            });
            
            $(document).on("click","#menu li.parent", function() {
                $(this).next().slideToggle(100);
            });
            $(document).on("click","#menu li:not(.parent)", function() {
                $("#menu li").removeClass("current");
                $(this).addClass("current");
            });
        </script>
    </body>
</html>
<?php endif;