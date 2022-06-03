<?php
//GENERATE TICKET POOL JSON FOR DATA TABLE

### SYS
session_start();
require_once ("../sys/sql.php");                //SQL
require_once ('../sys/global_functions.php');   //GLOBAL FUNCTIONS


if (isset($_POST['red']) && $_POST['red']==1) //TICKET REDISTRIBUTIONS POOL
{
    if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']!=99 && $_POST['state_id']!=98)
    {
        print_r(TicketPoolRedistribution($_POST['state_id'],'1,2','1'));
    }
    else if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']==99){
        print_r(TicketPoolbyUser($_SESSION['USER_ID']));
    }
    else if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']==98){
        print_r(TicketPoolbyUserOwn($_SESSION['USER_ID']));
    }
    else
    {
        print_r(TicketPoolbyStateAndTeams(1,$_POST['teams_id'])); // default
    }
}
else if (isset($_POST['teams_id']) && $_POST['teams_id']!='') //TICKET TEAM POOL
{
    if ($_POST['teams_id'] == 0)
    {
        if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']!=99 && $_POST['state_id']!=98)
        {
            print_r(TicketPoolbyState($_POST['state_id']));
        }
        else if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']==99){
            print_r(TicketPoolbyUser($_SESSION['USER_ID']));
        }
        else if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']==98){
            print_r(TicketPoolbyUserOwn($_SESSION['USER_ID']));
        }
        else
        {
            print_r(TicketPoolbyState(1)); // default
        }
    }
    else {

        if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']!=99 && $_POST['state_id']!=98)
        {
            print_r(TicketPoolbyStateAndTeams($_POST['state_id'],$_POST['teams_id']));
        }
        else if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']==99){
            print_r(TicketPoolbyUser($_SESSION['USER_ID']));
        }
        else if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']==98){
            print_r(TicketPoolbyUserOwn($_SESSION['USER_ID']));
        }
        else
        {
            print_r(TicketPoolbyStateAndTeams(1,$_POST['teams_id'])); // default
        }
    }
} else { //ALL TICKETS POOL

    if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']!=99 && $_POST['state_id']!=98)
    {
        print_r(TicketPoolbyState($_POST['state_id']));
    }
    else if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']==99){
        print_r(TicketPoolbyUser($_SESSION['USER_ID']));
    }
    else if (isset($_POST['state_id']) && $_POST['state_id']!='' && $_POST['state_id']==98){
        print_r(TicketPoolbyUserOwn($_SESSION['USER_ID']));
    }
    else
    {
        print_r(TicketPoolbyState(1)); // default
    }

}

exit;