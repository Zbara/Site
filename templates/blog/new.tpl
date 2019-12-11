<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <title>Створення новини</title>
    <link href="/src/css/bootstrap.css" rel="stylesheet">
    <link href="/src/css/admin.css" rel="stylesheet">
    <link href="/src/css/font-awesome.min.css" rel="stylesheet">
    <script src="//code.jquery.com/jquery-2.1.4.js"></script>
    <script src="/js/jquery.form.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/main.js"></script>
</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-brand" href="/"><b>Блог</b></a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> {$login} <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="/index.php?method=/account/logout"><i class="fa fa-sign-out fa-fw"></i> Вихiд</a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav">
                    <li>
                        <a><i class="fa fa-dashboard fa-fw"></i> Блог </a>
                        <ul class="nav nav-second-level">
                            <li><a class="" href="/index.php?method=/blog/new">Нова новина</a></li>

                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="notifications bottom-right" id="for_alert"></div>

                    <div class="page-header">
                        <h3>Нова новина</h3>
                    </div>
                    <form class="form-horizontal" action="#" id="addForm" method="POST">
                        <div class="form-group">
                            <label for="lastname" class="col-sm-3 control-label">Заголовок:</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="title" placeholder=""
                                       value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text" class="col-sm-3 control-label"></label>
                            <div class="col-sm-7">
            <textarea maxlength="50" placeholder="Текст HTML тот что на сайте поддерживается" class="form-control"
                      name="text" rows="5" style="width:509px;height: 400px;"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-primary">Додати</button>
                            </div>
                        </div>
                    </form>

                    <!-- JS start -->
                    <script>
                        $('#addForm').ajaxForm({
                            url: '/index.php?method=/blog/new',
                            dataType: 'text',
                            success: function (data) {
                                data = $.parseJSON(data);

                                if (data.error) {
                                    $('button[type=submit]').prop('disabled', false);
                                    return showError(data.error.message);
                                }
                                showSuccess(data.success.message);

                                $('button[type=submit]').prop('disabled', false);
                            },
                            beforeSubmit: function (arr, $form, options) {
                                $('button[type=submit]').prop('disabled', true);
                            }
                        });
                    </script>
                    <!-- JS end -->


                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>