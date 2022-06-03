<?php
    ### CREATE NEW TICKET

    session_start();
    require_once ("../sys/dbconn.php");             //DB CONN
    require_once ("../sys/sql.php");                //SQL
    require_once ('../sys/global_vars.php');        //GLOBAL VARS
    require_once ('../sys/global_functions.php');   //GLOBAL FUNCTIONS

        $isMultiTicket = checkIfMultiTicket($_POST['form_modal_ticket_new_topico_nivel_2']) ?? false;

        if ($isMultiTicket !== false) {
            $topicID = $_POST['form_modal_ticket_new_topico_nivel_2'];
            $ticketDetails = $_POST['form_modal_ticket_new_detalhes'];
            $motherID = ++MaxTicketThreadId()['item'][0]['max_ticket_thread_id'];
            TicketNew(1, $topicID, $_SESSION['USER_ID'], $ticketDetails, NULL, $_SESSION['USER_IP'], $_POST['form_modal_ticket_on_behalf'], $_POST['form_modal_ticket_new_assunto'], $motherID);
            foreach ($_POST['topic'] as $key => $topic) {
                $form = getTopicForm($key)[0];
                $company = $_POST['topic'][array_key_first($_POST['topic'])]['empresa'];
                insert($form, $topic , $company, $motherID);
            }
        }

        else {
            try {
                ### SET LOCAL VARS
                $arr_X[] = '';
                $filetype = "";

                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'X_') !== false) {
                        $key = substr($key, 2); //remove "X_" form key
                        $arr_X[$key] = $value;
                    }
                }

                $__source_id = 1; //ActionFlow
                $__ticket_details = str_replace("'", "", $_POST['form_modal_ticket_new_detalhes']); //clean text
                $__ticket_flags = NULL;

                if (isset($_POST['form_modal_ticket_new_topico_nivel_2']) && $_POST['form_modal_ticket_new_topico_nivel_2'] != '') {
                    $__topic_id = $_POST['form_modal_ticket_new_topico_nivel_2'];
                } else $__topic_id = $_POST['form_modal_ticket_new_topico_nivel_1'];

                //CREATE NEW TICKET THREAD AND DETAIL
                $ticket_new = TicketNew($__source_id, $__topic_id, $_SESSION['USER_ID'], $__ticket_details, $__ticket_flags, $_SESSION['USER_IP'], $_POST['form_modal_ticket_on_behalf'], $_POST['form_modal_ticket_new_assunto'], 'NULL');

                //GET TOPIC FORM
                $ticket_new_form = TopicLevelForm($__topic_id); // GET OPTIONS - TOPICS LEVEL 1

                //CREATE NEW TICKET FORM DATA, IF APPLIED
                if (isset($ticket_new_form['item'][0]['form_data']) && $ticket_new_form['item'][0]['form_data'] != '') //CHECK IF IT HAS FORM TO FILL
                {
                    //GET POST DATA TO ARRAY
                    foreach ($_POST as $key => $value) {
                        if (strpos($key, 'X_') !== false) {
                            $key = substr($key, 2); //remove "X_" form key
                            $arr_X_data[$key] = $value;
                        }
                    }
                    //GET NEW GENERATED ticket_thread_id FOR BIDING
                    $ticket_new_max_thread_id = MaxTicketThreadId();

                    //INSERT FORM DATA
                    $companies = implode(', ', $arr_X_data['empresa']);
                    $arr_X_data['empresa'] = $companies;
                    $ticket_new_data = TicketNewData($ticket_new_max_thread_id['item'][0]['max_ticket_thread_id'], $ticket_new_form['item'][0]['form_data'], $arr_X_data);

                }
            } catch (Exception $e) {
                $_SESSION['RETURN_CODE'] = 900;
            }
        }

        $user = $_SESSION['USER'][0];
        $ticket_thread_id = MaxTicketThreadId()['item'][0]['max_ticket_thread_id'];
        $users = getTeamUsersByThreadID($ticket_thread_id) ?? false;
        $onBehalf = getBehalfEmailByThreadID($ticket_thread_id)[0]['user_name_behalf_email'] ?? 'no';
        $emails = implode(',', array_column($users, 'user_email'));

        if (preg_match("/\b$onBehalf\b/", $emails)) {
            $emails = preg_replace("/\b(\,|\,\,)*$onBehalf(\,|\,\,)*\b/", ',', $emails);
            if ($emails[0] === ',') {
                $emails[0] = ' ';
            };
            $emails .= ",$onBehalf,on_behalf";
            $emails = preg_replace('/,,+/', ',', $emails);
            $emails = trim($emails);
        } else if ($onBehalf !== 'no'){
            $emails .= ",$onBehalf,on_behalf";
        }

        if (!empty($_FILES['customFileLang']) && $_FILES['customFileLang']['error'] !== 4) {
            $filetype = strtolower(pathinfo($_FILES['customFileLang']['name'])['extension']);
        }

        if (!empty($_FILES['customFileLang']) && $_FILES['customFileLang']['error'] !== 4 &&
            $filetype !== 'js' &&
            $filetype !== 'php'  &&
            $filetype !== 'html' &&
            $filetype !== 'bat'
        ) {

            $store = "../../store/".$ticket_thread_id;
            if (!file_exists($store)) {
                mkdir($store);
            }
            $tmp_name = $_FILES['customFileLang']['tmp_name'];
            $name = preg_replace("/\s+/", "", basename($_FILES["customFileLang"]["name"]));
            move_uploaded_file($tmp_name, "$store/$name");
            TicketAttachUpdate($ticket_thread_id, $user['user_id'], $name);
        }

        if ($users !== false) {

            insertIntoEmailPool($emails,
                $user['user_email'],
                "Nova solicitação criada por {$user['user_name']}",
                "{$_POST['form_modal_ticket_new_detalhes']}",
                $ticket_thread_id,
                'novo');

        } else {

            $team = getTeamNameByThreadID($ticket_thread_id)[0]['team_description'];
            insertIntoEmailPool('NULL',
                $user['user_email'],
                "Sem utilizadores na equipa $team",
                "NULL",
                $ticket_thread_id,
                'erro');
        }
        //SET RETURN CODE
        $_SESSION['RETURN_CODE'] = 100;
        //RETURN
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
