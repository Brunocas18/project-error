<?php

session_start();

require_once("../route/sys/dbconn.php");             //DB CONN
require_once("../route/sys/sql.php");                //SQL
require_once('../route/sys/global_vars.php');        //GLOBAL VARS
require_once('../route/sys/global_functions.php');   //GLOBAL FUNCTIONS

$getAllteams = getAllTeams();
$getAllStates = getAllStates();
$getAllCompanies = getAllCompanies();
$getTopicAbertura = getTopicoAbertura();
$getAllColumns = getAllColumns();
$getAllUsers = $_SESSION['USERS'];
$getAllSources = getAllSources();

$options_topic_level_0 = TopicLevel(0);
include ('../view/html/bo/ticket_new.html');

?>
<link rel="stylesheet" href="../view/assets/bootstrap/dist/css/bootstrap.css">
<link rel="stylesheet" href="search/jquery-ui.css">
<style>

    .smartflowColor {
        color: #5AADDD;
    }
    .pesquisar {
        background-color: #5AADDD;
        border-color: #5AADDD;
        color: white;
    }
    .pesquisar:hover {
        background-color: #45A2D9;
        border-color: #45A2D9;
    }

    .pesquisar:active {
        background-color: #45A2D9 !important;
        border-color: #45A2D9 !important;
    }

    .pesquisar:focus {
        background-color: #45A2D9 !important;
        border-color: #45A2D9 !important;
    }

    .ui-state-active {
        background-color: #5AADDD !important;
        border-color: #5AADDD !important;
        color: white;
    }
    #ticket_pool_wrapper {
        margin-top: -50px;
    }

</style>
<script src="search/jquery.min.js" type="text/javascript"></script>
<script src="search/jquery-ui.min.js" type="text/javascript"></script>
<!-- select2 -->
<script src="view/assets/select2/dist2/js/select2.min.js"></script>
<!-- Insert this line after script imports -->

<script>
    $(function () {
        $("#tabs").tabs({
            collapsible: true
        });
    });
</script>

<div id="container" class="container-fluid mt-5">
    <h6 class="ml-3 mt-4 font-italic smartflowColor">Preencha no mínimo um dos seguintes campos</h6>
    <form name="form" action="../index.php?state=50" method="POST" enctype="multipart/form-data">

        <!-- FIRST ROW-->
        <div class="row">

            <div class="col">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Thread ID</span>
                    </div>
                    <input type="number" min="1" pattern="[1-9]*" class="form-control" id="ticket_thread_id" name="ticket_thread_id" placeholder="Insira a thread ID" title="Insira a thread ID">
                </div>
            </div>

            <div class="col">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="topicoAbertura">Topico Abertura</label>
                    </div>
                    <select class="custom-select" id="topicoAbertura" name="topicoAbertura" title="Selecione o tópico abertura" class="select2">
                        <option selected disabled value="">Selecione um tópico abertura</option>
                        <?php foreach ($getTopicAbertura as $value): ?>
                            <option value="<?= $value['topic_id'] ?>"><?= $value['topic_description'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="col">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="topicoNivel1">Topico Nível I</label>
                    </div>
                    <select class="custom-select" id="topicoNivel1" name="topicoNivel1" disabled title="Selecione o tópico Nivel I">
                        <option selected value="">Selecione tópico de nivel I</option>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="topicoNivel2">Topico Nível 2</label>
                    </div>
                    <select class="custom-select" id="topicoNivel2" name="topicoNivel2" disabled title="Selecione o tópico Nivel 2">
                        <option selected disabled value="">Selecione tópico de nivel 2</option>
                    </select>
                </div>
            </div>

        </div>
        <!-- SECOND ROW-->
        <div class="row">
            <div class="col">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="emnomede">Em Nome de: </label>
                    </div>
                    <select class="custom-select select2" id="emnomede" title="Em nome de:" name="emnomede" class="select2">
                        <option selected value="">Sem nome</option>
                        <?php foreach ($getAllUsers as $value1): ?>
                            <option value="<?= $value1['user_name'] ?>"><?= $value1['user_name'] . ' - ' . $value1['user_email']?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>


            <div class="col">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="utilizador">Solicitador</label>
                    </div>
                    <select class="custom-select select2" id="utilizador" title="Qual o utilizador" name="utilizador" class="select2">
                        <option selected value="">Sem Solicitador</option>
                        <?php foreach ($getAllUsers as $value2): ?>
                            <option value="<?= $value2['user_id'] ?>"><?= $value2['user_name'] . ' - ' . $value2['user_email']?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="atribuido">Atribuido a</label>
                    </div>
                    <select class="custom-select select2" id="atribuido" title="Em nome de:" name="atribuido">
                        <option selected value="">Sem nome</option>
                        <?php foreach ($getAllUsers as $value1): ?>
                            <option value="<?= $value1['user_id'] ?>"><?= $value1['user_name'] . ' - ' . $value1['user_email']?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>


            <div class="col">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Equipa</label>
                    </div>
                    <select class="custom-select" id="equipa" title="Qual a equipa a procurar?" name="equipa">
                        <option selected value="">Sem Equipa</option>
                        <?php foreach ($getAllteams as $value) : ?>
                            <option value="<?= $value['team_id'] ?>"><?= $value['team_description'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-3">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Origem</label>
                    </div>
                    <select class="custom-select" id="origem" title="Qual a origem?" name="origem">
                        <option selected disabled value="">Escolha a Origem</option>
                        <?php foreach ($getAllSources as $value) : ?>
                            <option value="<?= $value['source_name'] ?>"><?= $value['source_name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="col-3">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Assunto</span>
                    </div>
                    <input type="text" maxlength="128" class="form-control" id="assunto" name="assunto" placeholder="Insira o Assunto" title="Insira o Assunto">
                </div>
            </div>
            <div class="col">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Estado</label>
                    </div>
                    <select class="custom-select" id="estadoTicket" title="Estado do ticket" name="estadoTicket">
                        <option selected value="">Sem estado</option>
                        <?php foreach ($getAllStates as $value): ?>
                            <option value="<?= $value['state_id'] ?>"><?= $value['state_description'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>

        <div id="addBoxes">

        </div>

        <!-- TABS-->

        <div id="tabs">
            <ul>
<!--                <li><a id="show-tabs-1" href="#tabs-1">Outros</a></li>-->
                <li><a href="#tabs-2">Data Criação</a></li>
            </ul>
            <div id="tabs-1">
                <?php /*foreach($getAllColumns as $value) : */?><!--
                    <div class="form-check-inline d-inline-flex align-content-center">
                        <input type="checkbox" class="form-check-input" id="<?/*= $value['COLUMN_NAME'] */?>">
                        <label class="form-check-label" for="exampleCheck1"><?/*= $value['COLUMN_NAME'] */?></label>
                    </div>
                --><?php /*endforeach */?>
            </div>
            <div id="tabs-2">
                <p>Data Criação:&nbsp;&nbsp;<input type="date" id="date" name="date"/></p>
            </div>
        </div>

        <div class="buttons mt-3">
            <button type="reset" id="limpar" class="btn btn-secondary">Limpar</button>
            <button type="submit" class="btn btn-primary pesquisar">Pesquisar</button>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('#show-tabs-1').click()
    })

    $(document).ready(function() {
        //POPULATE TOPIC - NEW TICKET
        $("#form_modal_ticket_new_topico_nivel_0").change(function(){
            $("#form_modal_ticket_new_topico_nivel_1").html(""); document.getElementById('form_modal_ticket_new_topico_nivel_1').style.display = 'none';
            $("#form_modal_ticket_new_topico_nivel_2").html(""); document.getElementById('form_modal_ticket_new_topico_nivel_2').style.display = 'none';
            $("#form_modal_ticket_new_topico_nivel_2_form").html(""); document.getElementById('form_modal_ticket_new_topico_nivel_2_form').style.display = 'none';

            var topic_id=$("#form_modal_ticket_new_topico_nivel_0").val();
            $.ajax({
                type:"post",
                url:"../route/get/get_topic_level_1.php",
                data:"topic_id="+topic_id,
                success:function(data){
                    $("#form_modal_ticket_new_topico_nivel_1").html(data);
                    document.getElementById('form_modal_ticket_new_topico_nivel_1').style.display = 'block';
                }
            });
        });

        //POPULATE TOPIC - NEW TICKET
        $("#form_modal_ticket_new_topico_nivel_1").change(function(){
            var topic_id=$("#form_modal_ticket_new_topico_nivel_1").val();
            $.ajax({
                type:"post",
                url:"../route/get/get_topic_level_2.php",
                data:"topic_id="+topic_id,
                success:function(data){
                    $("#form_modal_ticket_new_topico_nivel_2").html(data);
                    document.getElementById('form_modal_ticket_new_topico_nivel_2').style.display = 'block';
                }
            });
        });

        $("#form_modal_ticket_new_topico_nivel_2").change(function(){
            var topic_id=$("#form_modal_ticket_new_topico_nivel_2").val();
            $.ajax({
                type:"post",
                url:"../route/get/get_topic_level_2_form.php",
                data:"topic_id="+topic_id,
                success:function(data){
                    document.getElementById('form_modal_ticket_new_topico_nivel_2_form').style.display = 'block';
                    $("#form_modal_ticket_new_topico_nivel_2_form").html(data);
                }
            });
        });
    })

    $('#pesquisa').addClass('active');

    if (localStorage) {
        document.getElementById('ticket_thread_id').value = localStorage['ticket_thread_id'];
        document.getElementById('emnomede').value = localStorage['emnomede'] ?? '';
        document.getElementById('utilizador').value = localStorage['utilizador'] ?? '';
        document.getElementById('estadoTicket').value = localStorage['estadoTicket'] ?? '';
        document.getElementById('equipa').value = localStorage['equipa'] ?? '';
        document.getElementById('origem').value = localStorage['origem'] ?? '';
        document.getElementById('date').value = localStorage['date'] ?? '';
        document.getElementById('atribuido').value = localStorage['atribuido'] ?? '';
        document.getElementById('assunto').value = localStorage['assunto'] ?? '';
        document.getElementById('topicoAbertura').value = localStorage['topicoAbertura'] ?? '';
    }

    let form = document.forms[1];
    const elementsAdded = [];

    form.onsubmit = function() {
        localStorage['ticket_thread_id'] = document.getElementById('ticket_thread_id').value;
        localStorage['emnomede'] = document.getElementById('emnomede').value;
        localStorage['utilizador'] = document.getElementById('utilizador').value;
        localStorage['estadoTicket'] = document.getElementById('estadoTicket').value;
        localStorage['equipa'] = document.getElementById('equipa').value;
        localStorage['origem'] = document.getElementById('origem').value;
        localStorage['date'] = document.getElementById('date').value
        localStorage['topicoAbertura'] = document.getElementById('topicoAbertura').value
        localStorage['topicoNivel1'] = document.getElementById('topicoNivel1').value
        localStorage['topicoNivel2'] = document.getElementById('topicoNivel2').value
        localStorage['atribuido'] = document.getElementById('atribuido').value;
        localStorage['assunto'] = document.getElementById('assunto').value;
    }

    function removeAllInputBoxes() {
        for (let i = 0, len = elementsAdded.length; i < len; i++) {
            document.getElementById(`show${elementsAdded[i]}`)?.remove(); // only remove if it exists!!
        }
    }

    function addEventListenerCheckboxes() {
        let allCheckBoxes = document.querySelectorAll("input[type=checkbox]");
        let row;
        let addBoxes = document.getElementById('addBoxes');
        for (let i = 0, len = allCheckBoxes.length; i < len; i++) {
            allCheckBoxes[i].addEventListener('change', function() {

                if (!this.checked) {
                    document.getElementById(`show${this.id}`).remove();
                    elementsAdded.splice(elementsAdded.indexOf(this.id), 1);
                    return
                } else {
                    if (elementsAdded.length % 4 === 0) {
                        row = document.createElement('div');
                        row.className = 'row';
                        addBoxes.appendChild(row);
                    }
                    elementsAdded.push(this.id);
                    console.log(elementsAdded);
                    console.log(elementsAdded.length);
                    let div = document.createElement('div');
                    div.className = 'input-group mb-3 mt-3 col';
                    div.id = `show${this.id}`;
                    let div2 = document.createElement('div');
                    div2.className = 'input-group-prepend';
                    div.appendChild(div2);
                    let span = document.createElement('span');
                    span.className = 'input-group-text';
                    span.innerHTML = this.id;
                    div2.appendChild(span);
                    row.append(div);

                    let input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'form-control';
                    input.id = this.id;
                    input.name = this.id;
                    input.placeholder = `Insira o ${this.id}`
                    input.title = `Insira o ${this.id}`
                    div.appendChild(input);
                }
            });
        }
    }

    addEventListenerCheckboxes();

    $('#topicoAbertura').on('click', function() {
        console.log('fdsfsd')
        $.ajax({
                type:"post",
                url:"../route/get/get_topic_level_1.php",
                data:"topic_id="+this.value,
                success:function(data){
                    console.log(data);
                    $('#topicoNivel1').prop('disabled', false);
                    $("#topicoNivel1").html(data);
                }
            });
    });

    $('#topicoNivel1').change(function() {
        $.ajax({
            type:"post",
            url:"../route/get/get_topic_level_2.php",
            data:"topic_id="+this.value,
            success:function(data){
                console.log(data);
                $("#topicoNivel2").html(data);
                $('#topicoNivel2').prop('disabled', false);
            }
        });
    });

    $('#topicoNivel2').change(function() {
        $.ajax({
            type:"post",
            url:"../route/get/get_columns.php",
            data:"id="+this.value,
            success:function(data){
                $("#tabs-1").html(data);
                removeAllInputBoxes();
                addEventListenerCheckboxes();
                elementsAdded.length = 0;
            }
        });
    });

    let limpar = document.getElementById('limpar');

    limpar.addEventListener('click', function() {
        localStorage.removeItem('ticket_thread_id')
        localStorage.removeItem('emnomede')
        localStorage.removeItem('utilizador')
        localStorage.removeItem('estadoTicket')
        localStorage.removeItem('equipa')
        localStorage.removeItem('origem')
        localStorage.removeItem('date')
        localStorage.removeItem('topicoAbertura')
        localStorage.removeItem('topicoNivel1')
        localStorage.removeItem('topicoNivel2');
        localStorage.removeItem('atribuido');
        localStorage.removeItem('assunto');
        document.getElementById('ticket_pool_wrapper').innerHTML = '';
    })

    $( '.select2' ).select2( {
        theme: 'bootstrap-5'
    } );
</script>