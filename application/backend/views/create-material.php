<?php 
    global $view_data; 
    $data = $view_data;
    // echo("<br>");
    // echo("<br>");
    // var_dump($data);
    // echo("<br>");
    // echo("<br>");
?>
<!doctype html>
<html lang="ru">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/bootstrap-utilities.css">
    <link rel="stylesheet" href="/css/style.css">
    <title>Материалы</title>
</head>
<body>
<div class="main-wrapper">
    <div class="content">
    <?php require("navbar.php");?>
        <div class="container">
            <h1 class="my-md-5 my-4">Добавить материал</h1>
            <div class="row">
                <div class="col-lg-5 col-md-8">
                    <form action="/control/index/save_material" method="POST">
                        <div class="form-floating mb-3">
                            <select name="type" class="form-select" id="floatingSelectType">
                                <option selected>Выберите тип</option>
                                <?php foreach($data->types as $type):?>

                                    <option value="<?=$type['id']?>"><?=$type['title']?></option>
                                <?php endforeach?>
                            </select>
                            <label for="floatingSelectType">Тип</label>
                            <div class="invalid-feedback">
                                Пожалуйста, выберите значение
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <select name="section" class="form-select" id="floatingSelectCategory">
                                <option selected>Выберите категорию</option>
                                <?php foreach($data->groups as $group):?>
                                    <option value="<?=$group['id']?>"><?=$group['title']?></option>
                                <?php endforeach?>
                            </select>
                            <label for="floatingSelectCategory">Категория</label>
                            <div class="invalid-feedback">
                                Пожалуйста, выберите значение
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="title" type="text" class="form-control" placeholder="Напишите название" id="floatingName">
                            <label for="floatingName">Название</label>
                            <div class="invalid-feedback">
                                Пожалуйста, заполните поле
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="authors"  type="text" class="form-control" placeholder="Напишите авторов" id="floatingAuthor">
                            <label for="floatingAuthor">Авторы</label>
                            <div class="invalid-feedback">
                                Пожалуйста, заполните поле
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                        <textarea name="description" class="form-control" placeholder="Напишите краткое описание" id="floatingDescription"
                              style="height: 100px"></textarea>
                            <label for="floatingDescription">Описание</label>
                            <div class="invalid-feedback">
                                Пожалуйста, заполните поле
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Добавить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer py-4 mt-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col text-muted">Test</div>
            </div>
        </div>
    </footer>
</div>
<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
        crossorigin="anonymous"></script>

</body>
</html>