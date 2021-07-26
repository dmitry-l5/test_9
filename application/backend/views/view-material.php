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
            <h1 class="my-md-5 my-4"><?= $data->info['title']?></h1>
            <div class="row mb-3">
                <div class="col-lg-6 col-md-8">
                    <div class="d-flex text-break">
                        <p class="col fw-bold mw-25 mw-sm-30 me-2">Авторы</p>
                        <p class="col"><?= $data->info["authors"]?></p>
                    </div>
                    <div class="d-flex text-break">
                        <p class="col fw-bold mw-25 mw-sm-30 me-2">Тип</p>
                        <p class="col"><?= $data->info["type"]?></p>
                    </div>
                    <div class="d-flex text-break">
                        <p class="col fw-bold mw-25 mw-sm-30 me-2">Категория</p>
                        <p class="col"><?= $data->info["section"]?></p>
                    </div>
                    <div class="d-flex text-break">
                        <p class="col fw-bold mw-25 mw-sm-30 me-2">Описание</p>
                        <p class="col"><?= $data->info["description"]?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <form action="/control/index/add_tag" method="POST">
                        <input type="hidden" name="id_material" value="<?=$data->info["id"]?>">
                        <h3>Теги</h3>
                        <div class="input-group mb-3">
                            <select name="id_tag" class="form-select" id="selectAddTag" aria-label="Добавьте автора">
                                <?php foreach($data->tags_list as $tag):?>
                                    <option value="<?=$tag['id'];?>"><?=$tag['title'];?></option>
                                <?php endforeach;?>
                            </select>
                            <button class="btn btn-primary" type="submit">Добавить</button>
                        </div>
                    </form>
                    <ul class="list-group mb-4">
                        <?php if($data->tags):?>
                            <?php foreach($data->tags as $tag):?>
                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                    <a href="#" class="me-3">
                                        <?=$tag['title'];?>
                                    </a>
                                    <a href="/control/index/remove_tag?id_tag=<?=$tag["id"]?>&id_material=<?=$tag["id_material"]?>" class="text-decoration-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                             class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd"
                                                  d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        </svg>
                                    </a>
                                </li>
                            <?php endforeach;?>
                        <?php endif;?>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-between mb-3">
                        <h3>Ссылки</h3>
                        <a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Добавить</a>
                    </div>
                    <ul class="list-group mb-4">
                        <?php if($data->links):?>
                            <?php foreach($data->links as $link):?>
                                <li class="list-group-item list-group-item-action d-flex justify-content-between">
                                    <a href="<?=$link["link"]?>" class="me-3">
                                        <?=$link["description"]?>
                                    </a>
                                    <span class="text-nowrap">
                                        <a href="#" class="text-decoration-none me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                             class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                            </svg>
                                        </a>
                                        <a href="/control/index/remove_link?id=<?=$link['id']?>" class="text-decoration-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                 class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd"
                                                      d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg>
                                        </a>
                                    </span>
                                </li>
                            <?php endforeach;?>
                        <?php endif;?>
                    </ul>
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

<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
     tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

           <!-- ------------------------------ -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Добавить ссылку</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" placeholder="Добавьте подпись"
                           id="floatingModalSignature">
                    <label for="floatingModalSignature">Подпись</label>
                    <div class="invalid-feedback">
                        Пожалуйста, заполните поле
                    </div>

                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" placeholder="Добавьте ссылку" id="floatingModalLink">
                    <label for="floatingModalLink">Ссылка</label>
                    <div class="invalid-feedback">
                        Пожалуйста, заполните поле
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Я ожидал, что здесь будет форма -->
                <button type="submit" class="btn btn-primary">Добавить</button>
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Закрыть</button>
            </div>
            <!-- ...и тут я решил ... испортить вашу, красивую, вёрстку) -->
            <form action="/control/index/add_link" method="post">
                <input name="id_material" type="hidden" id="" value=<?=$data->info["id"]?>>
                <input name="description" type="text" id="" placeholder="description">
                <input name="link" type="text" name="" id="" placeholder="link">
                <button type="submit">add</button>
            </form>
        </div>
    </div>
    <!-- ------------------------------ -->
</div>
<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
        crossorigin="anonymous"></script>

</body>
</html>